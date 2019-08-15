<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends MY_Controller {
	var $data;
	
	function __construct() {
		parent::__construct();

	}
	
	public function index()
	{

		$num_rows = 0;
		$msg = $this->input->get('msg');

		$per_page = $this->pager_config['per_page'];


		$query = $this->admin_model->get_limited_rows($this->uri->segment(4,0),$per_page);
		$num_rows = $this->admin_model->num_rows();


		$this->pager_config['total_rows'] = $num_rows;
		$this->pagination->initialize($this->pager_config);
		 
		$this->data['content'] = $this->load->view('admin/'.$this->controller.'_list',
															array(
																	'data_list'=> $query->result_array(),
																	'msg' => $msg
															),TRUE );
		
		$this->_render();
	}
	
	public function search() {
		$per_page = $this->pager_config['per_page'];
		$q = $this->input->post('txt_query');
		$q = !empty($q) ? $q : urldecode($this->uri->segment(4) );  
		//表示使用者啥也沒輸入就按下按鈕
		if(empty($q)) {
				redirect(site_url("/admin/{$this->controller}/index"));
				die(); //不知是否可以防止後面程式繼續執行..
		}
		
		$this->pager_config['base_url'] .= $q;
		
		$num_rows = $this->admin_model->get_like_name_all($q)->num_rows();

 		$query =  $this->admin_model->get_like_name_limit($q , $this->uri->segment(5,0) , $per_page);
		$this->pager_config['uri_segment'] = 5;
		$this->pager_config['total_rows'] = $num_rows;

		$this->pagination->initialize($this->pager_config);
		$this->data['content'] = $this->load->view('admin/'.$this->controller.'_list',array(
																								'data_list'=>$query->result_array()
																							),TRUE );
		$this->_render();
	}
	
	public function add() {
		$view_data = array(
					'mode' => 'ADD'
				);
				
		$this->data['content'] = $this->load->view('/admin/'.$this->controller.'_form',$view_data,TRUE);
		$this->_render();	
	}
	
	public function add_save() {
		
		$newid = $this->model->add();
		redirect("/admin/{$this->controller}/index");
	}
	
	public function edit() {
		//TODO:檢查未輸入ID或找不到資料時
		$id = $this->uri->segment(4,-1);
		
		$view_data = array(
				'mode' => 'EDIT',
				'editing_row' => $this->admin_model->get_row_by_id($id),
				'id'=>$id
		);
		
		$this->data['content'] = $this->load->view('admin/'.$this->controller.'_form',$view_data,TRUE);
		$this->_render();
	}
	
	public function edit_save() {
		$this->model->edit();
		//redirect("/admin/{$this->controller}/edit/{$this->input->post('hd_id')}");
		redirect("/admin/{$this->controller}/index");
	}
	
	public function delete() {
		$num_rows = $this->admin_model->num_rows();
		if($num_rows > 1) {
			$this->model->delete();	
			redirect("/admin/{$this->controller}/index");
		} else {
			redirect("/admin/{$this->controller}/index?msg=您只剩這一組管理員帳號，請勿刪除！");
		}

	}
	
	private function _render() {
		$this->load->view('admin/admin_main_template',$this->data);
	}
	
	function ajax_test() {
			
	}
	
	
	//-----以下為自訂validation callback區
	function ajax_username_unique_check () {
		$v = trim($this->input->post('txt_username'));
		$id = $this->input->post('id');
		
		if($this->admin_model->username_unique_check($v,$id)   ) {
			echo "false";
		}
		else {
			echo "true";
			
		}
	}
	
	function email_unique_check ($v='',$id='') {

		
		if($this->member_model->email_unique_check($v,$id)   ) {
			$this->form_validation->set_message('email_unique_check','您輸入的eMail已經有人使用了。');
			return FALSE;
		}
		else {
			return TRUE;
		}
	}
}
