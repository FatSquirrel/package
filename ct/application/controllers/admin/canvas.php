<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Canvas extends MY_Controller {
	//上傳圖檔檔名的前置字元
	var $prefix;
	var $data;

	
	function __construct() {
		parent::__construct();
		

	}
	
	public function index()
	{
		

	}

	public function page() {


		 
		$this->data['content'] = $this->load->view('admin/canvas_test',
															array(),TRUE );

		$this->_render();
	}
	public function xxx() {
		echo $this->uri->segment(4, -1);
	}

	private function _render() {
		$this->load->view('admin/admin_main_template',$this->data);
	}
	
}
