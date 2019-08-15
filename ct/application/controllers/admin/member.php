<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Member extends MY_Controller {
	var $data;
	
	function __construct() {
		parent::__construct();

	}
	
	public function index()
	{

		$num_rows = 0;
		
		$per_page = $this->pager_config['per_page'];


		$query = $this->member_model->get_limited_rows($this->uri->segment(4,0),$per_page);
		$num_rows = $this->member_model->num_rows();


		$this->pager_config['total_rows'] = $num_rows;
		$this->pagination->initialize($this->pager_config);
		 
		$this->data['content'] = $this->load->view('admin/'.$this->controller.'_list',
															array(
																	'data_list'=> $query->result_array() 
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
		
		$num_rows = $this->member_model->get_like_name_all($q)->num_rows();

 		$query =  $this->member_model->get_like_name_limit($q , $this->uri->segment(5,0) , $per_page);
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
					'mode' => 'ADD',
					'status_options' =>$this->config->item('member_status')
				);
				
		$this->data['content'] = $this->load->view('/admin/'.$this->controller.'_form',$view_data,TRUE);
		$this->_render();	
	}
	
	public function add_save() {
		
		$this->load->library('form_validation');
		$valid_config = $this->config->item('form_validation_rules');
		$groupname = 'member_edit_add';
		$idx = get_specified_fieldname_index($valid_config[$groupname], 'txt_email');
		$valid_config[$groupname][$idx]['rules'] =  "trim|required|max_length[45]|valid_email|callback_email_unique_check['']|xss_clean";  
		
 		$this->form_validation->set_rules($valid_config[$groupname]);
 		if($this->form_validation->run() === TRUE ) {
 			$this->member_model->add();
 			redirect("/admin/{$this->controller}/index");
 		}
 		else {	//驗證沒過，秀出原表單並載入錯誤訊息
 			$view_data = array(
 					'mode' => 'ADD',
 					'status_options' =>$this->config->item('member_status')
 					//'editing_row' => $this->member_model->get_row_by_id($id)
 			);
 			$this->data['content'] = $this->load->view('admin/'.$this->controller.'_form',$view_data,TRUE);
 			$this->_render();
 		}

	}
	
	public function edit() {
		//TODO:檢查未輸入ID或找不到資料時
		$id = $this->uri->segment(4,-1);
		
		$view_data = array(
				'mode' => 'EDIT',
				'editing_row' => $this->member_model->get_row_by_id($id),
				'status_options' =>$this->config->item('member_status'),
				'id'=>$id
		);
		
		$this->data['content'] = $this->load->view('admin/'.$this->controller.'_form',$view_data,TRUE);
		$this->_render();
	}
	
	public function edit_save() {
		//TODO:檢查未輸入ID或找不到資料時及驗證
		$id = $this->input->post('hd_id',-1);
		
		$this->load->library('form_validation');
		$valid_config = $this->config->item('form_validation_rules');
		$groupname = 'member_edit_add';
		$idx = get_specified_fieldname_index($valid_config[$groupname], 'txt_email');
		$valid_config[$groupname][$idx]['rules'] =  "trim|required|max_length[45]|valid_email|callback_email_unique_check[$id]|xss_clean";  
		
 		$this->form_validation->set_rules($valid_config[$groupname]);
 		
		if($this->form_validation->run() === TRUE ) {
			$this->member_model->edit();
			redirect("/admin/{$this->controller}/index");
		}
		else {	//驗證沒過，秀出原表單並載入錯誤訊息
			$view_data = array(
					'mode' => 'EDIT',
					'id'=>$id,
					'status_options' =>$this->config->item('member_status')
					//'editing_row' => $this->member_model->get_row_by_id($id)
			);
			$this->data['content'] = $this->load->view('admin/'.$this->controller.'_form',$view_data,TRUE);
			$this->_render();
		}
	}
	
	public function delete() {
		$this->member_model->delete();	
		redirect("/admin/{$this->controller}/index");
	}
	
	private function _render() {
		$this->load->view('admin/admin_main_template',$this->data);
	}
	
	function ajax_test() {
			
	}
	
	
	//-----以下為自訂validation callback區
	function ajax_email_unique_check () {
		$v = $this->input->post('txt_email');
		$id = $this->input->post('id');
		
		if($this->member_model->email_unique_check($v,$id)   ) {
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
