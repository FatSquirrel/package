<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Productdetail_model extends My_Model {
	var $model_name = "productdetail";
	function __construct() {
		parent::__construct();
		
	}

	public function get_rows_by_productid($id='') {

		$sql = "SELECT * from productdetail where fk_product = '". $id . "' AND isdel = '' ";

		return $this->db->query($sql);
		//$this->db->like('name',$like);
		//return $this->db->get('book');
	}

	//override
	//能提供limit及offset值，用在分頁時
	public function get_limited_rows($limit=10,$offset=0, $random=false) {
		$sql = "SELECT a.id, a.name as prodname , b.name as custname, b.sname as custsname from {$this->controller} as a join customer as b on a.fk_customer = b.id ";
		if($random) {
			$sql .= " ORDER BY RAND() ";
		}
		$sql .= "LIMIT $limit,$offset";
		return $this->db->query($sql);
	}
	//override
	public function get_like_name_all($like='') {

		$sql = "SELECT a.id, a.name as prodname , b.name as custname, b.sname as custsname from {$this->controller} as a join customer as b on a.fk_customer = b.id WHERE a.name LIKE '%".$this->db->escape_like_str($like)."%'";

		return $this->db->query($sql);
		//$this->db->like('name',$like);
		//return $this->db->get('book');
	}
	//override
	public function get_like_name_limit($like='', $limit=10,$offset=0) {
		$sql = "SELECT a.id, a.name as prodname , b.name as custname, b.sname as custsname from {$this->controller} as a join customer as b on a.fk_customer = b.id  WHERE a.name LIKE '%".$this->db->escape_like_str($like)."%' LIMIT $limit,$offset";
		return $this->db->query($sql);
		//return $this->db->get('book',$offset,$limit);
	}
	public function add($fk_product, $items) {
		

		for($i = 0; $i < count($items); $i += 1) {
			$newid = $this->uuid->v4();
			$item = $items[$i];
			$data = array(
						'id' => $newid,
						'fk_product' => $fk_product,
						'name'=>$item['name'],
						'tos'=>$item['tos'],
						'knum'=>$item['knum'],
						'back'=>$item['back'],
						'tcs'=>$item['tcs'],
						'cfreq'=>$item['cfreq'],
						'cfs'=>$item['cfs'],
						'qty'=>$item['qty'],
						't'=>$item['t'],
						'tvendor'=>$item['tvendor'],
						'prt'=>$item['prt'],
						'prtvendor'=>$item['prtvendor'],
						'sfc'=>$item['sfc'],
						'sfcvendor'=>$item['sfcvendor'],
						'heat'=>$item['heat'],
						'heatvendor'=>$item['heatvendor'],
						'cf'=>$item['cf'],
						'cfvendor'=>$item['cfvendor'],
						'pst'=>$item['pst'],
						'pstvendor'=>$item['pstvendor'],
						'ga'=>$item['ga'],
						'gavendor'=>$item['gavendor'],
						'garemark'=>$item['garemark'],
						'glu'=>$item['glu'],
						'gluvendor'=>$item['gluvendor'],
						'price'=>$item['price'],
						'other'=>$item['other'],
						'otherremark'=>$item['otherremark']
					);
			$this->db->insert($this->model_name,$data);
		}
	}
	
	public function delete($id){
		$item = array(
				'isdel' => 'X'
			);

		$this->db->where('id', $id);
		$this->db->update('productdetail', $item);

	}

	public function edit($fk_product, $items) {
		//$this->db->delete($this->model_name, array('fk_product'=>$fk_product));

		for($i = 0; $i < count($items); $i += 1) {
			$newid = $this->uuid->v4();
			$item = $items[$i];
			$data = array(
						'fk_product' => $fk_product,
						'name'=>$item['name'],
						'tos'=>$item['tos'],
						'knum'=>$item['knum'],
						'back'=>$item['back'],
						'tcs'=>$item['tcs'],
						'cfreq'=>$item['cfreq'],
						'cfs'=>$item['cfs'],
						'qty'=>$item['qty'],
						't'=>$item['t'],
						'tvendor'=>$item['tvendor'],
						'prt'=>$item['prt'],
						'prtvendor'=>$item['prtvendor'],
						'sfc'=>$item['sfc'],
						'sfcvendor'=>$item['sfcvendor'],
						'heat'=>$item['heat'],
						'heatvendor'=>$item['heatvendor'],
						'cf'=>$item['cf'],
						'cfvendor'=>$item['cfvendor'],
						'pst'=>$item['pst'],
						'pstvendor'=>$item['pstvendor'],
						'ga'=>$item['ga'],
						'gavendor'=>$item['gavendor'],
						'garemark'=>$item['garemark'],
						'glu'=>$item['glu'],
						'gluvendor'=>$item['gluvendor'],
						'price'=>$item['price'],
						'other'=>$item['other'],
						'otherremark'=>$item['otherremark']
					);
				if(isset($item['id']) && $item['id'] != '') {
					$this->db->where('id', $item['id']);
					$this->db->update('productdetail', $data);
				} else {
					$data['id'] = $newid;
					$this->db->insert($this->model_name,$data);
				}
		}
		
		
		
	}

}