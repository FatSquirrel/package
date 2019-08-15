<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Canvas_model extends CI_Model {
	function __construct() {
		parent::__construct();
		
	}
	 
	public function get_like_name_all($like='') {
		$sql = "SELECT Banners.id AS id , Banners.title, content, createdate FROM Banners WHERE Banners.title LIKE '%".$this->db->escape_like_str($like)."%' OR Banners.content LIKE '%".$this->db->escape_like_str($like)."%'";

		return $this->db->query($sql);
		//$this->db->like('name',$like);
		//return $this->db->get('book');
	}

	public function get_like_name_limit($like='', $limit=10,$offset=0) {
		$sql = "SELECT Banners.id AS id ,Banners.title, content, createdate FROM Banners WHERE Banners.title LIKE '%".$this->db->escape_like_str($like)."%' LIMIT $limit,$offset";
		return $this->db->query($sql);
		//return $this->db->get('book',$offset,$limit);
	}
	
	//能提供limit及offset值，用在分頁時
	public function get_limited_rows($limit=10,$offset=0, $random=false) {
		if($random) {
			$sql = "SELECT Banners.id AS id ,Banners.title as title,Banners.content as content, Banners.createdate FROM Banners ORDER BY RAND() LIMIT $limit,$offset";
			//$sql = "SELECT Banners.id AS id , Banners.name FROM Banners WHERE Banners.title LIKE '%".$this->db->escape_like_str($like)."%' OR Banners.content LIKE '%".$this->db->escape_like_str($like)."%'";

		} else {
			$sql = "SELECT * FROM Banners LIMIT $limit,$offset";
		}
		return $this->db->query($sql);
		//return $this->db->get('book',$offset,$limit);
	}
	
	public function get_row_by_id($id=-1) {
		$this->db->where('id',$id);
		return $this->db->get('Banners')->row_array(); //row()方法回傳單筆資料(也就是它不是一個二維陣列
	}
	
	public function num_rows() { 
		return $this->db->get('Banners')->num_rows();
	}
	
	public function add() {
		$newid = $this->uuid->v4();
		//TODO:需要加上驗證
		$data = array(
					'id'=>$newid,
					'title'=>$this->input->post('txt_title'),
					'content'=>$this->input->post('ta_content')
				);
		$this->db->insert('Banners',$data);
		return $newid;
	}
	
	public function edit() {
		//TODO:需要加上驗證
		$data = array(
					'title'=>$this->input->post('txt_title'),
					'content'=>$this->input->post('ta_content')
		);
		
		$this->db->where('id',$this->input->post('hd_id',-1));
		$this->db->update('Banners',$data);
		
	}
	
	public function delete() {
		
		$this->db->where('id',$this->uri->segment(4,-1))->delete('Banners');
		
	}
}