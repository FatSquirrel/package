<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Processother extends MY_Controller  {
	//上傳圖檔檔名的前置字元
	var $prefix;
	var $data;
	var $pager_config;
	
	function __construct() {
		parent::__construct(false);

		
	}
	
	public function index()
	{
		
		//$arrod = $this->db->get_where('orderdetail', array())->result_array();
		$today = date("Ymd", time());
		$sql = <<<EOT
SELECT 
	a.id, b.name as pname, a.name AS pdname,c.sname as cname, a.isorderdone as isorderdone,
	d.qty as qty, a.tos, a.cfs, a.qty as qty_paper, a.t, a.tvendor,
	 a.prt, a.prtvendor, a.sfc, a.sfcvendor, a.heat, a.heatvendor,
	  a.cf, a.cfvendor, a.pst, a.pstvendor, a.ga, a.gavendor, a.glu, a.gluvendor,
	  a.isotherdone
FROM   `orderdetail` AS a 
       JOIN product AS b 
         ON a.fk_product = b.id 
       JOIN customer AS c 
         ON b.fk_customer = c.id 
       join `order` as d
       on d.id = a.fk_order
    where 
    	a.isneedother = 'X' 
    and d.isdel = '' 
    and '{$today}' <= DATE_ADD(d.createdate, INTERVAL 2 
WEEK) 
EOT;
		$arrother  = $this->db->query($sql)->result_array();
	/*	foreach($item as $k => $){

		}*/
		$this->load->model('vendor_model');
//         WHERE a.isorderdone != 'X'
		$this->data['content'] = $this->load->view('admin/'.$this->controller.'_list',
															array(
																	'arrother'=> $arrother
															),TRUE );


		$this->_render();
	}
	
	public function search() {
		$per_page = $this->pager_config['per_page'];
		$q = $this->input->post('txt_query');
		$q = !empty($q) ? $q : urldecode($this->uri->segment(4) );  
		//表示使用者啥也沒輸入就按下按鈕
		if(empty($q)) {
				redirect(site_url("/{$this->controller}/index"));
				die(); //不知是否可以防止後面程式繼續執行..
		}
		
		$this->pager_config['base_url'] .= $q;
		
		$num_rows = $this->order_model->get_like_name_all($q)->num_rows();


 		$query =  $this->order_model->get_like_name_limit($q , $this->uri->segment(5,0) , $per_page);
		$this->pager_config['uri_segment'] = 5;
		$this->pager_config['total_rows'] = $num_rows;

		$this->pagination->initialize($this->pager_config);
		$this->data['content'] = $this->load->view('admin/'.$this->controller.'_list',array(
																								'data_list'=>$query->result_array()
																							),TRUE );
		$this->_render();
	}
	

	public function add() {
		$this->load->model('customer_model');
		// $this->load->model('product_model');

		$view_data = array(
					'mode' => 'ADD',
					'cdata' => $this->customer_model->get_like_name_all()->result_array(),
					// 'pdata' => $this->product_model->get_all()->result_array()
				);
				
		$this->data['content'] = $this->load->view('admin/'.$this->controller.'_form',$view_data,TRUE);
		$this->_render();	
		
	}
	public function ajax_processother_save() {
		
		// $this->load->model('productdetail_model');
		// $this->load->model('orderdetail_model');

		$items = json_decode($this->input->get_post('items'), true);

		//$newid = $this->order_model->add($fk_customer, $fk_product, $qty, $etd, $prtpr, $prtpr_price, $bladepr, $bladepr_price);
		//因為直接使用CI的update比較快，所以移除掉多餘的欄位(陣列元素)
		//unset($item['selected']); //前端用來標記"已選擇"的旗標
		
		foreach($items as $v) {
			;
			$this->db->where('id', $v['id']);
			unset($v['id']);
			
			$this->db->update('orderdetail', $v);
		}
		echo 'OK';
		// $arrd = $this->productdetail_model->get_rows_by_productid($fk_product)->result_array();
		// $this->orderdetail_model->add($newid, $fk_customer, $fk_product, $arrd);
		// echo 'OK';
		// $str = '';
		// //for($i = 0; $i < count($arrd); $i += 1) {
		// foreach($arrd as $k => $v) {
		// 	$str .= $k. ', ' . $v['name'];// $arrd[$i]['name'];
		// }
	}

	public function edit() {

		$id = $this->uri->segment(4,-1);
		
 		$editing_row = $this->order_model->get_row_by_id($id);
 		$editing_row['od'] = $this->order_model->get_detail_by_id($id)->result_array();
//  		echo print_r($editing_row);
		//TODO:檢查未輸入ID或找不到資料時

		$view_data = array(
				'mode' => 'EDIT',
				'editing_row' =>$editing_row 
		);
		
		$this->data['content'] = $this->load->view('admin/'.$this->controller.'_form',$view_data,TRUE);
		$this->_render();
	}
	
	public function edit_save() {
		//TODO:檢查未輸入ID或找不到資料時及驗證

	    $this->order_model->edit();
		redirect("/admin/{$this->controller}/index");
	}
	
	public function delete() {
		$this->order_model->delete();	
		redirect("/admin/{$this->controller}/index");
	}
	
	private function _render() {
		$this->load->view('admin/admin_main_template',$this->data);
	}
	
}
