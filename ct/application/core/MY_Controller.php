<?php


class MY_Controller extends CI_Controller {
	var $pager_config;
	var $controller;
	var $model;
//感覺這類工具程式不該寫在父類別裡..應該有什麼專門的地方在放置這類程式的。
	public function get_today_date() {
		$this->load->helper('date');

		$datestring = "%Y-%m-%d";
		$time = time();

		return mdate($datestring, $time);
	}

	public function __construct($loadmodel=true) {
		parent::__construct();

		//初屎化
		$this->_init($loadmodel);

		//檢查有無登入，沒有的話就redirect到登入頁
		$this->_check_is_login();

	}

	private function _init($loadmodel=true) {
		$this->controller = $this->uri->segment(2);

			$this->config->set_item('model_name', $this->controller."_model");
		$this->load->helper(array('form','url','bookstore','file','html'));
		$this->load->library(array('pagination','session') );
		if($loadmodel == true) {
			$this->load->model($this->config->item('model_name'));
			$this->model = $this->{$this->config->item('model_name')}; //縮短並統一model語法
		}

		//設定分頁資訊
		$this->_pager_init();
	}


	//設定分頁資訊
	private function _pager_init() {

		//預防有時會直接省略預設動作時的情形
		$action = $this->uri->segment(3) ? $this->uri->segment(3) : 'index';

		//-------pager settings (有部分會拆在其它地方設-------
				
// 		$this->pager_config['query_string_segment'] = "page";
// 		$this->pager_config['page_query_string'] = TRUE;
		$this->pager_config['uri_segment'] = 4;
		$this->pager_config['base_url'] = "/ct/admin/{$this->controller}/$action/";
		$this->pager_config['per_page'] = 10;
		$this->pager_config['last_link'] = FALSE;
		$this->pager_config['first_link'] = FALSE;
		$this->pager_config['full_tag_open'] = '<div class="pagination"><ul>';
		$this->pager_config['full_tag_close'] = '</ul></div>';
		$this->pager_config['prev_link'] = '&lt;';
		$this->pager_config['prev_tag_open'] = '<li>';
		$this->pager_config['prev_tag_close'] = '</li>';
		$this->pager_config['next_link'] = '&gt;';
		$this->pager_config['next_tag_open'] = '<li>';
		$this->pager_config['next_tag_close'] = '</li>';
		$this->pager_config['cur_tag_open'] = '<li class="active"><a href="">';
		$this->pager_config['cur_tag_close'] = '</a></li>';
		$this->pager_config['num_tag_open'] = '<li>';
		$this->pager_config['num_tag_close'] = '</li>';
	}


	private function _check_is_login() {
		if($this->session->userdata($this->config->item('sessionkey_is_login') ) !== 'yes' ) {
			redirect('admin/login/index/您尚未登入');
		}
	}

	public function index() {
		echo 'true index is in the parent class';
	}
}


class Frontend_Controller extends CI_Controller {
	var $view_data;
	var $controller;
	public function __construct() {
		parent::__construct();
		$this->_init();

	}
	
	private function _init() {
		$this->controller =$this->uri->segment(1);
	
	}
	
	protected function _check_if_frontend_logged_in() {
		if($this->session->userdata($this->config->item('sessionkey_frontend_is_login') ) !== 'yes' ) {
// 			redirect($this->config->item('frontend_login_url'));
			return false;
		}
		else {
			return true;
		}
	}




}
/*----------------------------------------------------------*/

