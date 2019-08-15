<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Orderdetail_model extends My_Model {
	var $model_name = "orderdetail";
	function __construct() {
		parent::__construct();
		
	}

	public function get_rows_by_productid($id='') {

		$sql = "SELECT * from productdetail where fk_product = '" . $id . "'";

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
	
	public function setnotpurchase_t($id) {


		$item = array(
			'po_t_no' => '',
			'ispo_t_done' => ''
			);

		$this->db->where('po_t_no', $id);
		$this->db->update($this->model_name, $item);
		
		//return $this->db->get('book',$offset,$limit);
	}

	public function setnotpurchase_cf($id) {


		$item = array(
			'po_cf_no' => '',
			'ispo_cf_done' => ''
			);

		$this->db->where('po_cf_no', $id);
		$this->db->update($this->model_name, $item);
		
		//return $this->db->get('book',$offset,$limit);
	}

	public function add($fk_order, $fk_customer, $fk_product, $items) {

		for($i = 0; $i < count($items); $i += 1) {
			$newid = $this->uuid->v4();
			$item = $items[$i];
			
			$data = array(
						'id' => $newid,
						'fk_order' => $fk_order,
						'fk_customer' => $fk_customer,
						'fk_product' => $fk_product,
						'fk_productdetail' => $item['id'],
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
						'isneedother' => $item['other'],
						'ispodone'=>'',
						'isneedpo_t' => '',
						'isneedpo_cf' => '',
						'isppdone'=>'',
						
						'isotherdone'=>'',
						'remark' =>'',
						'orderno'=>$item['orderno']
					);
			$this->db->insert($this->model_name,$data);
		}
	}
	
	public function edit($fk_product, $items) {
		$this->db->delete($this->model_name, array('fk_product'=>$fk_product));

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
						'ga'=>$item['ga'],
						'gavendor'=>$item['gavendor'],
						'garemark'=>$item['garemark'],
						'glu'=>$item['glu'],
						'gluvendor'=>$item['gluvendor'],
						'price'=>$item['price'],
						'other'=>$item['other']
					);
			
				$this->db->insert($this->model_name,$data);
		}
	}
	
	//將傳進來的工單明細的已處理設成未處理狀態
	public function setnotdone($id) {
				$item = array(
					'isorderdone' => ''
					);

				$this->db->where('id', $id);
				$this->db->update($this->model_name, $item);
	}

	//依據傳進來的id 陣列來撈出資料
	public function get_by_ids($ids) {
		$this->db->where_in('id', $ids);
		return $this->db->get('orderdetail');

	}
		
}