<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Api for CodeIgniter 3.x
 *
 * API to migrate data to SQL Database. It helps to import single file/multiple files
 * and feeds the extracted data to SQL engine.
 *
 *
 *
 * @package data-export-any-to-sql
 * @category    API
 * @author      Hemant Karekar
 * @license     MIT
 * @version     1.0
 * @see         application/libraries/CSVReader
 */


require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Api extends CI_Controller
{
	/**
	 * import_from_csv
	 * 
	 * ### Import data for a table from a single CSV file
	 * This function handles a single .csv file for data migration 
	 * 
	 * @version 1.0.0
	 * Status: Ready to Deploy
	 * 
	 * @return void
	 */
	public function import_from_csv()
	{
		$this->load->library("CSVreader");
		try {
			for ($k = 0; $k < count($_FILES['files']['name']); $k++) {
				if (!in_array(pathinfo($_FILES['files']['name'][$k])["extension"], ["csv", "txt"])) {
					throw new InvalidFileTypeException();
				} else {
					$csvData = $this->csvreader->parse_csv($_FILES['files']['tmp_name'][$k]);
					$query = "INSERT INTO `test_table`(`col_01`, `col_02`, `col_03`, `col_04`, `col_05`, `col_06`, `col_07`, `col_08`, `col_09`, `col_10`) VALUES ";
					$string = "";
					for ($i = 0; $i < count($csvData); $i++) {
						$string = $string . "(";
						for ($j = 0; $j < count($csvData); $j++) {
							if ($csvData[$i][$j] == null) {
								$string = $string .  $csvData[$i][$j] . '""';
							}
							if ($j < count($csvData) - 1) {
								$string = $string .  $csvData[$i][$j] . ",";
							} else {
								$string = $string .  $csvData[$i][$j];
							}
						}
						if ($i < count($csvData) - 1) {
							$string = $string .  "),";
						} else {
							$string = $string .  ")";
						}
					}
					$query = $query . $string;
					// echo $query;
					if ($this->db->query($query)) {
						echo "Data Migration from file " . $_FILES["files"]["name"][$k] . " is Successful";
					} else {
						echo "Data Migration Failed from file " . $_FILES["files"]["name"][$k];
					}
				}
			}
		} catch (InvalidFileTypeException $e) {
			echo 'Error: ' . $e->getMessage();
		}
	}

	/**
	 * import_from_batch_csv
	 *
	 * ## Import Data for a table from a folder full of .csv files
	 * This function handles multiple .csv files and migrate all the data at once.
	 * 
	 * @version 1.1.0
	 * Status In Development
	 *   
	 * @return void
	 */
	public function import_from_batch_csv()
	{
		//STAGES 

		// [1] Load Necessary Libraries
		$this->load->library("CSVreader");

		/* 
		[2]

		Steps:
		-----------
		1. Navigate and open a Folder containing CSV Files
		2. Check if the file is CSV or not.
		3.  
		*/


		//[3] Use CSVReader to read and store data in the form of Array of String
		$csvData = $this->csvreader->parse_csv($_FILES['file'][0]['tmp_name']);

		//[4] Initialise half Query with [Table_name] and [Column_name]s
		$query = "INSERT INTO `test_table`(`col_01`, `col_02`, `col_03`, `col_04`, `col_05`, `col_06`, `col_07`, `col_08`, `col_09`, `col_10`) VALUES ";

		//[5] Initialise a empty string for values part to be apended to the main $query
		$string = "";

		//[6] Perform a recursive operation to generate a string covering each row of the CSV file.
		for ($i = 0; $i < count($csvData); $i++) {
			$string = $string . "(";
			for ($j = 0; $j < count($csvData); $j++) {
				if ($csvData[$i][$j] == null) {
					$string = $string .  $csvData[$i][$j] . '""';
				}
				if ($j < count($csvData) - 1) {
					$string = $string .  $csvData[$i][$j] . ",";
				} else {
					$string = $string .  $csvData[$i][$j];
				}
			}
			if ($i < count($csvData) - 1) {
				$string = $string .  "),";
			} else {
				$string = $string .  ")";
			}
		}

		//[7] append the $string to main $query.
		$query = $query . $string;

		//[8] Use $query to insert bulk data into a Table in Database;
		if ($this->db->query($query)) {
			echo "Data Migration from file " . $_FILES["file"]["name"] . " is Successful";
		} else {
			echo "Data Migration Failed from file " . $_FILES["file"]["name"];
		}
	}

	/**
	 * read_from_excel
	 * 
	 * ### Read Cell Values from Excel
	 * 
	 * @version 1.0.0
	 * Status: In Development
	 * 
	 * Packages Required: 
	 * phpoffice/phpspreadsheet
	 * 
	 * Installation: `composer require phpoffice/phpspreadsheet`
	 * 
	 * Add it to the Library Folder and Customize accordingly.
	 * 
	 * @return void
	 */
	public function read_from_excel()
	{
		// Create a new Reader of the type defined in $inputFileType
		$spreadsheet = IOFactory::load($_FILES['file']['tmp_name']);
		// Use the PhpSpreadsheet object's getSheetCount() method to get a count of the number of WorkSheets in the WorkBook
		$sheetNames = $spreadsheet->getSheetNames();
		$tableNamesArray = [];
		// Loop through the sheet names
		foreach ($sheetNames as $sheetName) {
			array_push($tableNamesArray, strtolower(str_replace(" ", "_", $sheetName)));
		}

		// Create Table Query $tableQuery
		for ($i = 0; $i < count($sheetNames); $i++) {
			$tableName = strtolower(str_replace(" ", "_", $sheetNames[$i]));
			$tableQuery = "CREATE TABLE `" . $tableName . "` (`id` INT(11) NOT NULL AUTO_INCREMENT, ";
			// Select the sheet by index
			$sheet = $spreadsheet->getSheet($i);
			$highestRow = $sheet->getHighestRow();
			$highestColumn = $sheet->getHighestColumn();
			$highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);
			// Loop through each row
			for ($row = 1; $row <= $highestRow; $row++) {
				// $dataQuery = "";
				// Loop through each column
				for ($col = 1; $col <= $highestColumnIndex; $col++) {
					$cellValue = $sheet->getCell([$col, $row])->getValue();
					if ($row == 1) {
						if ($col < $highestColumnIndex) {
							$tableQuery .= "`" . $cellValue . "` VARCHAR(100),";
						} else {
							$tableQuery .= "`" . $cellValue . "` VARCHAR(100)";
						}
					}
				}
			}
			$tableQuery .= ", PRIMARY KEY(`id`))";
			if (!$this->db->table_exists($tableName)){
				if ($this->db->query($tableQuery)) {
					echo "Table `" . $tableName . "` is Successfuly Created. <br>";
				} else {
					echo "Table `" . $tableName . "` is Not Created. <br>";
				}
			}
		}

		echo "<br><br>";

		// Insert Data Query $dataQuery 
		for ($i = 0; $i < count($sheetNames); $i++) {
			$tableName = strtolower(str_replace(" ", "_", $sheetNames[$i]));
			// Select the sheet by index
			$sheet = $spreadsheet->getSheet($i);
			$highestRow = $sheet->getHighestRow();
			$highestColumn = $sheet->getHighestColumn();
			$highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);

			// Loop through each row
			$dataQuery = "";
			for ($row = 1; $row <= $highestRow; $row++) {
				$queryPart01 = "INSERT INTO `" . $tableName . "`(";
				$queryPart02 = "(";
				// Loop through each column
				if ($row == 1) {
					for ($col = 1; $col <= $highestColumnIndex; $col++) {
						$cellValue = $sheet->getCell([$col, $row])->getValue();
						if ($col < $highestColumnIndex) {
							$queryPart01 .= "`" . $cellValue . "`,";
						} else {
							$queryPart01 .= "`" . $cellValue . "`";
						}
					}
					$queryPart01 .= ")";
					$dataQuery.= $queryPart01 . "VALUES ";
				} else {
					for ($col = 1; $col <= $highestColumnIndex; $col++) {
						$cellValue = $sheet->getCell([$col, $row])->getValue();
						
						if ($col < $highestColumnIndex) {
							$queryPart02 .= "'" . $cellValue . "',";
						} else {
							$queryPart02 .= "'" . $cellValue . "'";
						}
					}
					if($row < $highestRow){
						$queryPart02 .= "),";
					} else {
						$queryPart02 .= ")";
					}
					$dataQuery.= $queryPart02 . " ";
				}
			}

			// echo $dataQuery . "<br><br>";
			if ($this->db->query($dataQuery)) {
				echo "Data in `" . $tableName . "` is Successfuly Loaded. <br>";
			} else {
				echo "Data in `" . $tableName . "` is Not Loaded. <br>";
			}
		}
	}

	/**
	 * read_from_excel_batch
	 * 
	 * ### Read Cell Values from a Folder of Excel files.
	 * 
	 * @version 1.0.0
	 * Status: In Development
	 * 
	 * Packages Required: 
	 * phpoffice/phpspreadsheet
	 * 
	 * Installation: `composer require phpoffice/phpspreadsheet`
	 * 
	 * Add it to the Library Folder and Customize accordingly.
	 * 
	 * @return void
	 */
	public function read_from_excel_batch()
	{
	}
}
