<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Model extends CI_Model {
	
	function __construct() {
		parent::__construct();

		$this->_init();
	}
	
	private function _init() {
	}

	public function foo() {
		return $this->model_name;
	}

	public function num_rows() { 
		return $this->db->get($this->model_name)->num_rows();
	}
	
	public function get_row_by_id($id=-1) {
		$this->db->where('id',$id);
		return $this->db->get($this->model_name)->row_array(); //row()方法回傳單筆資料(也就是它不是一個二維陣列
	}
	
	//能提供limit及offset值，用在分頁時
	public function get_limited_rows($limit=10,$offset=0, $random=false) {
		$sql = "SELECT * from {$this->model_name}  WHERE ISDEL = '' ";
		if($random) {
			$sql .= "ORDER BY RAND() ";
		}
		$sql .= "LIMIT $limit,$offset";
		return $this->db->query($sql);
	}


	public function get_like_name_all($like='') {
		$sql = "SELECT * from {$this->model_name} WHERE name LIKE '%".$this->db->escape_like_str($like)."%' AND ISDEL = '' ";
		return $this->db->query($sql);
		//$this->db->like('name',$like);
		//return $this->db->get('book');
	}

	public function get_like_name_limit($like='', $limit=10,$offset=0) {
		$sql = "SELECT * from {$this->model_name} WHERE name LIKE '%".$this->db->escape_like_str($like)."%' AND ISDEL = '' LIMIT $limit,$offset";
		return $this->db->query($sql);
		//return $this->db->get('book',$offset,$limit);
	}
	


	public function add() {
		//TODO:需要加上驗證
		$data = array(
					'name'=>$this->input->post('txt_name'),
					'fk_cateid'=>$this->input->post('ddl_bookcate'),
					'unitprice'=>$this->input->post('txt_unitprice'),
					'description'=>$this->input->post('ta_description')
				);
		$this->db->insert('book',$data);
		return $this->db->insert_id();
	}
	
	public function edit() {
		//TODO:需要加上驗證
		$data = array(
					'name'=>$this->input->post('txt_name'),
					'fk_cateid'=>$this->input->post('ddl_bookcate'),
					'unitprice'=>$this->input->post('txt_unitprice'),
					'description'=>$this->input->post('ta_description')
		);
		
		$this->db->where('id',$this->input->post('hd_id',-1));
		$this->db->update('book',$data);
		
	}
	
	public function delete() {
		
		$this->db->where('id',$this->uri->segment(4,-1))->delete($this->model_name);
		
	}
}