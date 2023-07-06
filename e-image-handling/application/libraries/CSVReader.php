<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * CSV Reader for CodeIgniter 3.x
 *
 * Library to read the CSV file. It helps to import a CSV file
 * and convert CSV data into an associative array.
 *
 * This library treats the first row of a CSV file
 * as a column header row.
 *
 *
 * @package     CodeIgniter
 * @category    Libraries
 * @author      CodexWorld
 * @license     http://www.codexworld.com/license/
 * @link        http://www.codexworld.com
 * @version     3.0
 */
class CSVReader
{

    // Columns names after parsing
    protected $fields;
    // Separator used to explode each line
    protected $separator = ';';
    // Enclosure used to decorate each field
    protected $enclosure = '"';
    protected $column_separator = ",";
    // Maximum row size to be used for decoding
    protected $max_row_size = 4096;

    /**
     * Parse a CSV file and returns as an array.
     *
     * @access    public
     * @param    filepath    string    Location of the CSV file
     *
     * @return mixed|boolean
     */
    function parse_csv($filepath)
    {

        // If file doesn't exist, return false
        if (!file_exists($filepath)) {
            return FALSE;
        }

        // Open uploaded CSV file with read-only mode
        $csvFile = fopen($filepath, 'r');

        // Get Fields and values
        // Store CSV data in an array
        $csvData = array();
        $i = 0;
        while (($row = fgetcsv($csvFile, $this->max_row_size, $this->separator, $this->enclosure)) !== FALSE) {
            // $csvData = array_merge($csvData, $row);
            $csvData[$i] = explode(",", $row[0]);
            $i++;
        }
        fclose($csvFile);
        return $csvData;
    }

    // function escape_string($data){
    //     $result = array();
    //     foreach($data as $row){
    //         $result[] = str_replace('"', '', $row);
    //     }
    //     return $result;
    // }   
}



/**
 * @package Exceptions
 * 1. InvalidFileTypeException
 */


/**
 * InvalidFileTypeException
 */
class InvalidFileTypeException extends Exception{
    public function __construct($message = "Invalid file type. Only CSV files are allowed.", $code = 0, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
