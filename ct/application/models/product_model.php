<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Product_model extends My_Model {
	var $model_name = "product";
	function __construct() {
		parent::__construct();
		
	}

	//override
	//能提供limit及offset值，用在分頁時
	public function get_limited_rows($limit=10,$offset=0, $random=false) {
		$sql = "SELECT a.id, a.name as prodname , b.name as custname, b.sname as custsname from {$this->model_name} as a join customer as b on a.fk_customer = b.id where a.ISDEL = ''";
		if($random) {
			$sql .= " ORDER BY RAND() ";
		}
		$sql .= "LIMIT $limit,$offset";
		return $this->db->query($sql);
	}
	//override
	public function get_like_name_all($like='') {

		$sql = "SELECT a.id, a.name as prodname , b.name as custname, b.sname as custsname from {$this->model_name} as a join customer as b on a.fk_customer = b.id WHERE a.isdel = '' AND a.name LIKE '%".$this->db->escape_like_str($like)."%'";

		return $this->db->query($sql);
		//$this->db->like('name',$like);
		//return $this->db->get('book');
	}
	public function get_all() {
		$this->db->where('isdel', '');
		return $this->db->get($this->model_name);
	}

	public function get_all_by_custid($cid) {
		$this->db->order_by('name', 'asc');
		return $this->db->get_where($this->model_name, array('fk_customer' => $cid, 'isdel' => ''));
	}

	//override
	public function get_like_name_limit($like='', $limit=10,$offset=0) {
		$sql = "SELECT a.id, a.name as prodname , b.name as custname, b.sname as custsname from {$this->model_name} as a join customer as b on a.fk_customer = b.id  WHERE a.isdel = '' and a.name LIKE '%".$this->db->escape_like_str($like)."%' LIMIT $limit,$offset";
		return $this->db->query($sql);
		//return $this->db->get('book',$offset,$limit);
	}

	public function add($pname, $fk_customer,$price) {
		$newid = $this->uuid->v4();

		$data = array(
					'id' => $newid,
					'name'=> $pname,
					'fk_customer'=>$fk_customer,
					'price'=>$price
					// 'companyno'=>$this->input->post('txt_companyno'),
					// 'address'=>$this->input->post('txt_address'),
					// 'tel'=>$this->input->post('txt_tel'),
					// 'fax'=>$this->input->post('txt_fax'),
					// 'payremark'=>$this->input->post('txt_payremark'),
				);
		$this->db->insert($this->model_name,$data);
		return $newid;
	}
	
	public function edit($id, $pname, $fk_customer,$price) {
		//TODO:需要加上驗證
		$data = array(
					'name'=> $pname,
					'fk_customer'=>$fk_customer,
					'price'=>$price,
					'updatedate' => getTodayStr()
		);
		
		$this->db->where('id',$id);
		$this->db->update($this->model_name,$data);
		
	}

}