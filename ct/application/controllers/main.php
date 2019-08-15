<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main extends Frontend_Controller {
	//上傳圖檔檔名的前置字元
	var $prefix;
	var $data;
	var $controller;

	function __construct() {
		parent::__construct();

		
	}
	

	public function index()
	{

		$this->load->view('frontend_main_HP_template',$this->view_data);
	}
	

	
}
