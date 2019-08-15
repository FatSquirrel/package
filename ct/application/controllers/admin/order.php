<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Order extends MY_Controller  {
	//上傳圖檔檔名的前置字元
	var $prefix;
	var $data;
	var $pager_config;
	
	function __construct() {
		parent::__construct();

		
	}
	
	public function index()
	{

		$arr = $this->order_model->get_all()->result_array();

		$this->data['content'] = $this->load->view('admin/'.$this->controller.'_list',
															array(
																	'data_list'=> $arr 
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
	public function ajax_add_save() {
		
		$this->load->model('productdetail_model');
		$this->load->model('orderdetail_model');
		$this->load->model('setting_model');
		$fk_customer = $this->input->get_post('fk_customer');
		$fk_product = $this->input->get_post('fk_product');
		$qty = $this->input->get_post('qty');
		$etd = $this->input->get_post('etd');
		$prtpr = $this->input->get_post('prtpr');
		$prtpr_price = 0;// $this->input->get_post('prtpr_price');
		$bladepr = $this->input->get_post('bladepr');
		$bladepr_price = 0; // $this->input->get_post('bladepr_price');

		$newid = $this->order_model->add($fk_customer, $fk_product, $qty, $etd, $prtpr, $prtpr_price, $bladepr, $bladepr_price);



		
		$arrd = $this->productdetail_model->get_rows_by_productid($fk_product)->result_array();
		for($i = 0; $i < count($arrd); $i += 1) {
			$arrd[$i]['orderno'] = $this->setting_model->nextorderno();
		}
		
		$this->orderdetail_model->add($newid, $fk_customer, $fk_product, $arrd);
		
		echo 'OK'.$newid;
		// $str = '';
		// //for($i = 0; $i < count($arrd); $i += 1) {
		// foreach($arrd as $k => $v) {
		// 	$str .= $k. ', ' . $v['name'];// $arrd[$i]['name'];
		// }

		
	}
	//修改訂單未作！！！！先不必要
	public function ajax_edit_save() {

		$this->load->model('productdetail_model');
		$this->load->model('orderdetail_model');
		$this->load->model('setting_model');
		
		$id = $this->input->get_post('id');
		$fk_customer = $this->input->get_post('fk_customer');
		$fk_product = $this->input->get_post('fk_product');
		$qty = $this->input->get_post('qty');
		$etd = $this->input->get_post('etd');
		$prtpr = $this->input->get_post('prtpr');
		$prtpr_price = $this->input->get_post('prtpr_price');
		$bladepr = $this->input->get_post('bladepr');
		$bladepr_price = $this->input->get_post('bladepr_price');


		$arrd = json_decode($this->input->get_post('items'), true);

		$this->productdetail_model->edit($id, $arrd);
		echo 'OK';
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
		$id = $this->uri->segment(4,-1);
		// $this->order_model->delete();	
		$item = array(
			'isdel' => 'X'
		);

		$this->db->where('id', $id);
		$this->db->update('order', $item);
		redirect("/admin/{$this->controller}/index");
	}
	
	private function _render() {
		$this->load->view('admin/admin_main_template',$this->data);
	}
	
}
