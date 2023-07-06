<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pages extends CI_Controller {

	/**
	 * Login Page for this controller.
	 * 
	 */
	public function login()
	{
		if(isset($_SESSION["username"])){
			header("Location:" . base_url());
		}
		$data["page"] = [
			"title" => "Master Login Page"
		];
		$this->load->view('pages/login', $data);
	}
	public function register()
	{
		if(isset($_SESSION["username"])){
			header("Location:" . base_url());
		}
		$data["page"] = [
			"title" => "Master Register Page"
		];
		$this->load->view('pages/register', $data);
	}
	
	public function index()
	{
		$data["page"] = [
			"title" => "Master Home Page"
		];
		$this->load->view('pages/index', $data);
	}
}
