<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main extends MY_Controller {
	//上傳圖檔檔名的前置字元
	var $prefix;
	var $data;

	
	function __construct() {
		parent::__construct();
		$this->load->model('setting_model');
		//每次來到第一頁時都檢查是否是新的一日，並將最後的工單及採購單流水號歸零	
		$this->setting_model->reset();
		

	}
	


	public function index()
	{
		$this->data['content'] = $this->load->view('admin/main',
															array(),TRUE );

		$this->_render();
	}




	private function _render() {
		$this->load->view('admin/admin_main_template',$this->data);
	}
	
}
