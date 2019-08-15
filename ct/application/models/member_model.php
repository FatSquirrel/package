<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Member_model extends CI_Model {
	function __construct() {
		parent::__construct();
		
	}
	
	public function get_like_name_all($like='') {
		if($like !== '')
			$this->db->like('name',$like);
		return $this->db->get('member');
	}

	public function get_like_name_limit($like='', $limit=10,$offset=0) {
		$this->db->like('name',$like);
		return $this->db->get('member',$offset,$limit);
	}
	
	//能提供limit及offset值，用在分頁時
	public function get_limited_rows($limit=10,$offset=0) {
		return $this->db->get('member',$offset,$limit);
	}
	
	public function get_row_by_id($id=-1) {
		$this->db->where('id',$id);
		return $this->db->get('member')->row_array(); //row()方法回傳單筆資料(也就是它不是一個二維陣列
	}
	
	public function num_rows() { 
		return $this->db->get('member')->num_rows();
	}
	
	//後台用於新增會員帳號時
	public function add() {
		
		//TODO:需要加上驗證
		$data = array(
					'email'=>$this->input->post('txt_email'),
					'password'=>$this->input->post('txt_password'),
					'name'=>$this->input->post('txt_name'),
					'gender'=>$this->input->post('rb_gender'),
					'address'=>$this->input->post('txt_address'),
					'status'=>$this->input->post('ddl_status')
				
				);
		$this->db->insert('member',$data);
	}
	
	//類似上面的add，但有些微差異
	public function register() {
		
		//TODO:需要加上驗證
		$data = array(
				'email'=>$this->input->post('txt_email'),
				'password'=>$this->input->post('txt_password'),
				'name'=>$this->input->post('txt_name'),
				'gender'=>$this->input->post('rb_gender'),
				'address'=>$this->input->post('txt_address')
		);
		
		$this->db->insert('member',$data);
		
	}
	
	public function edit() {
		//TODO:需要加上驗證
		$data = array(
				'email'=>$this->input->post('txt_email'),
				'password'=>$this->input->post('txt_password'),
				'name'=>$this->input->post('txt_name'),
				'gender'=>$this->input->post('rb_gender'),
				'address'=>$this->input->post('txt_address'),
				'status'=>$this->input->post('ddl_status'),
		);
		
		$this->db->where('id',$this->input->post('hd_id',-1));
		$this->db->update('member',$data);
		
	}
	
	public function email_unique_check($v,$id='') {
		$where_arr['email'] = $v;
		
		if($id!=='') {
			 $where_arr['id != '] = $id; 
		}
		
		$this->db->where($where_arr);
		return $this->db->get('member',1)->num_rows() > 0;
	}
	
	public function delete() {
		
		$this->db->where('id',$this->uri->segment(4,-1))->delete('member');
		
	}
}