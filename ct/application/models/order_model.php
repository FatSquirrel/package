<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Order_model extends My_Model {
	var $model_name = "order";
	function __construct() {
		parent::__construct();
		
	}

	public function get_all() {
		//$sql = "SELECT a.id, a.name as prodname , b.name as custname, b.sname as custsname from {$this->controller} as a join customer as b on a.fk_customer = b.id ";

		$sql = <<<EOT
SELECT a.id, 
	   d.name  AS itemname, 
	   d.orderno,
	   b.name  AS prodname,
       c.name  AS custname, 
       c.sname AS custsname,
       DATE_FORMAT(a.createdate, '%Y-%m-%d %H:%i' ) as createdate,
       a.etd,
       a.qty

FROM   `{$this->model_name}` AS a 
	   join orderdetail as d on a.id = d.fk_order
       JOIN product AS b 
         ON a.fk_product = b.id 
       JOIN customer AS c 
		 ON b.fk_customer = c.id 
	   WHERE a.isdel = ''
	   
       order by a.createdate desc , d.orderno desc	
EOT;

		return $this->db->query($sql);
	}

	//override
	//能提供limit及offset值，用在分頁時
	public function get_limited_rows($limit=10,$offset=0, $random=false) {
		//$sql = "SELECT a.id, a.name as prodname , b.name as custname, b.sname as custsname from {$this->controller} as a join customer as b on a.fk_customer = b.id ";

		$sql = <<<EOT
SELECT a.id, 
       b.name  AS prodname,
       c.name  AS custname, 
       c.sname AS custsname

FROM   `{$this->model_name}` AS a 
       JOIN product AS b 
         ON a.fk_product = b.id 
       JOIN customer AS c 
         ON b.fk_customer = c.id 
       WHERE a.isdel = ''
EOT;
		if($random) {
			$sql .= " ORDER BY RAND() ";
		}
		$sql .= " LIMIT $limit,$offset";
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

	public function add($fk_customer, $fk_product, $qty, $etd, $prtpr, $prtpr_price, $bladepr, $bladepr_price) {
		$newid = $this->uuid->v4();

		$data = array(
					'id' => $newid,
					'fk_customer'=>$fk_customer,
					'fk_product'=> $fk_product,
					'qty'=> $qty,
					'etd'=>$etd,
					'prtpr'=>$prtpr,
					'prtpr_price'=>$prtpr_price,
					'bladepr'=>$bladepr,
					'bladepr_price'=>$bladepr_price
				);
		$this->db->insert($this->model_name,$data);
		// print_r($data);
		return $newid;
	}
	
	public function edit($id, $fk_customer, $fk_product, $qty, $etd, $prtpr, $prtpr_price, $bladepr, $bladepr_price) {
		//TODO:需要加上驗證
		$data = array(
					'fk_customer'=>$fk_customer,
					'fk_product'=> $fk_product,
					'qty'=> $qty,
					'etd'=>$etd,
					'prtpr'=>$prtpr,
					'prtpr_price'=>$prtpr_price,
					'bladepr'=>$bladepr,
					'bladepr_price'=>$bladepr_price
				);
		
		$this->db->where('id',$id);
		$this->db->update($this->model_name,$data);
		
	}

}