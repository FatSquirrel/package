<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_model extends CI_Model {
	function __construct() {
		parent::__construct();
		
	}
	
	public function get_like_name_all($like='') {
		if($like !== '')
			$this->db->like('name',$like);
		return $this->db->get('admin');
	}

	public function get_like_name_limit($like='', $limit=10,$offset=0) {
		$this->db->like('name',$like);
		return $this->db->get('admin',$offset,$limit);
	}
	
	//能提供limit及offset值，用在分頁時
	public function get_limited_rows($limit=10,$offset=0) {
		return $this->db->get('admin',$offset,$limit);
	}
	
	public function get_row_by_id($id=-1) {
		$this->db->where('id',$id);
		return $this->db->get('admin')->row_array(); //row()方法回傳單筆資料(也就是它不是一個二維陣列
	}
	
	public function num_rows() { 
		return $this->db->get('admin')->num_rows();
	}
	
	//後台用於新增會員帳號時
	public function add() {
		
		//TODO:需要加上驗證
		$data = array(
					'username'=>$this->input->post('txt_username'),
					'password'=>$this->input->post('txt_password'),
					'nickname'=>$this->input->post('txt_nickname')
				
				);
		$this->db->insert('admin',$data);
	}
	

	
	public function edit() {
		//TODO:需要加上驗證
		$data = array(
					'username'=>$this->input->post('txt_username'),
					'password'=>$this->input->post('txt_password'),
					'nickname'=>$this->input->post('txt_nickname')
		);
		
		$this->db->where('id',$this->input->post('hd_id',-1));
		$this->db->update('admin',$data);
		
	}
	
	public function username_unique_check($v,$id='') {
		$where_arr['username'] = $v;
		
		if($id !== '') {
			 $where_arr['id != '] = $id; 
		}
		
		$this->db->where($where_arr);
		return $this->db->get('admin',1)->num_rows() > 0;
	}
	
	public function delete() {
		
		$this->db->where('id',$this->uri->segment(4,-1))->delete('admin');
		
	}
}