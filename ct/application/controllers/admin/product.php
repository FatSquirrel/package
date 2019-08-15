<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Product extends MY_Controller {
	//上傳圖檔檔名的前置字元
	var $prefix;
	var $data;

	
	function __construct() {
		parent::__construct();
	}
	
	public function index()
	{

		$num_rows = 0;

		$per_page = $this->pager_config['per_page'];
/*
select a.name, b.sname from product as a join customer as b on a.fk_customer = b.id 
*/
$this->db->select("product.id as id, product.name as prodname, customer.sname as custsname, productdetail.name as itemname, product.updatedate");
$this->db->from('product');
$this->db->join('customer', 'product.fk_customer = customer.id');
$this->db->join('productdetail',  'productdetail.fk_product = product.id');
$this->db->where('product.isdel', '');
$this->db->limit($per_page, $this->uri->segment(4,0));

$res = $this->db->get()->result_array();

		$num_rows = $this->model->get_all()->num_rows();

		$this->pager_config['total_rows'] = $num_rows;
		$this->pagination->initialize($this->pager_config);
$sql = <<<EOT
	SELECT id, sname FROM customer WHERE ISDEL = ''
EOT;
		$custraw = $this->db->query($sql)->result_array();
		$customers = array('0' => '~請選擇~');
		foreach($custraw as $c) {
			$customers[$c['id']] = $c['sname'];
		}


		$this->data['content'] = $this->load->view('admin/'.$this->controller.'_list',
															array(
																	'data_list'=> $res,
																	'customers' => $customers,
																	'selCust' => '',
																	'selN' => '',
																	'selQI' => ''
															),TRUE );

		$this->_render();
	}
	
	public function search() {
		//20180408 搜尋模式下把分頁取消改成全部列出
		$per_page = 9999;//$this->pager_config['per_page'];
		$q = $this->input->get('n');
		$qi = $this->input->get('qi');
		$c = $this->input->get('c');

		$q = !empty($q) ? $q : urldecode($this->uri->segment(4) );  
		$qi = !empty($qi) ? $qi : urldecode($this->uri->segment(4) );  
		//表示使用者啥也沒輸入就按下按鈕
		if(empty($q) && $c == '0' && empty($qi)) {
				redirect(site_url("/admin/{$this->controller}/index"));
				die(); //不知是否可以防止後面程式繼續執行..
		}
		
		$this->db->select("product.id as id, product.name as prodname, customer.sname as custsname, productdetail.name as itemname, product.updatedate");
		$this->db->from('product');
		$this->db->join('customer', 'product.fk_customer = customer.id');
		$this->db->join('productdetail',  'productdetail.fk_product = product.id');
		$this->db->where("product.isdel = ''");
		if(!empty($q)) {
			$this->db->like('product.name', $q);
		}
		if(!empty($qi)) {
			$this->db->like('productdetail.name', $qi);
		}
		// $this->db->or_like('productdetail.name', $qi);
		$this->db->limit($per_page, $this->uri->segment(4,0));
		

		if($c != '0') {
			
			$this->db->where('product.fk_customer', $c);
		}

		$res = $this->db->get()->result_array();
		$this->pager_config['base_url'] .= $q;
		
		$num_rows = sizeof($res);

 		//$query =  $this->model->get_like_name_limit($q , $this->uri->segment(5,0) , $per_page);
		
 		
		$this->pager_config['uri_segment'] = 5;
		$this->pager_config['total_rows'] = $num_rows;

		//$this->pagination->initialize($this->pager_config);


//setting customer dropdown data
$sql = <<<EOT
	SELECT id, sname from customer where isdel = ''
EOT;
		$custraw = $this->db->query($sql)->result_array();
		$customers = array('0' => '~請選擇~');
		$selCust = '';
		if($c != '0') {
			$selCust = $c;
		}

		foreach($custraw as $c) {
			$customers[$c['id']] = $c['sname'];
		}

		$this->data['content'] = $this->load->view('admin/'.$this->controller.'_list',array(
																								'data_list' => $res,
																								'customers' => $customers,
																								'selCust' => $selCust,
																								'selN' => $q,
																								'selQI' => $qi


																							),TRUE );
		$this->_render();
	}
	
	public function add() {
		$this->load->model('customer_model');
		$this->load->model('vendor_model');



		
// echo json_encode($this->vendor_model->get_like_name_all()->result_array());

		$view_data = array(
					'mode' => 'ADD',
					'cdata' => $this->customer_model->get_like_name_all()->result_array(),
					'vdata' => $this->vendor_model->get_like_name_all()->result_array(),
					's' => '',
					'okurl' => '/ct/admin/product/index'
				);
				
		$this->data['content'] = $this->load->view('admin/'.$this->controller.'_form',$view_data,TRUE);
		$this->_render();	
		
	}
	
	public function add_save() {

		//$newid = $this->model->add();
		

		//redirect("/admin/{$this->controller}/index");
	}

	public function ajax_add_save() {

		$this->load->model('product_model');
		$this->load->model('productdetail_model');

		//echo gettype($this->input->get_post('items'));
		$pname = $this->input->get_post('pname');
		$price = $this->input->get_post('price');
		$fk_customer = $this->input->get_post('fk_customer');
		$newid = $this->product_model->add($pname, $fk_customer, $price);

		$arrd = json_decode($this->input->get_post('items'), true);
		$arrd_final = array();
		foreach($arrd as $k => $v){

			if($v['knum'] == null || $v['knum'] == '') {
				$v['knum'] = 1;
			}
			if($v['back'] == null || $v['back'] == '') {
				$v['back'] = 1;
			}
			if($v['cfreq'] == null || $v['cfreq'] == '') {
				$v['cfreq'] = 0;
			}
			if($v['ga'] == null || $v['ga'] == '') {
				$v['ga'] = 1;
			}
			if($v['price'] == null || $v['price'] == '') {
				$v['price'] = 0;
			}

			if($v['other'] == true) {
				$v['other'] = 'X';
			} else {
				$v['other'] = '';
			}

			$arrd_final[$k] = $v;
		}

		$this->productdetail_model->add($newid, $arrd_final);
		echo 'OK';
		// $str = '';
		// //for($i = 0; $i < count($arrd); $i += 1) {
		// foreach($arrd as $k => $v) {
		// 	$str .= $k. ', ' . $v['name'];// $arrd[$i]['name'];
		// }

		
	}
	
	public function ajax_get_all_by_custid() {
		$cid = $this->input->get_post('cid');
		if($cid != '') {
			echo json_encode($this->product_model->get_all_by_custid($cid)->result_array());
		} else {
			echo '';
		}
	}

	public function ajax_edit_save() {

		$this->load->model('product_model');
		$this->load->model('productdetail_model');

		//echo gettype($this->input->get_post('items'));
		$id = $this->input->get_post('id');
		$pname = $this->input->get_post('pname');
		$price = $this->input->get_post('price');
		$fk_customer = $this->input->get_post('fk_customer');

		$this->product_model->edit($id, $pname, $fk_customer, $price);

		$deleted = json_decode($this->input->post('deleted'), true);
		$arrd = json_decode($this->input->get_post('items'), true);

		$arrd_final = array();
		//print_r($arrd);
		foreach($arrd as $k => $v){

			if($v['other'] == true) {
				$v['other'] = 'X';
			} else {
				$v['other'] = '';
			}
			$arrd_final[$k] = $v;
		}
		
		foreach($deleted as $v) {
			$this->productdetail_model->delete($v);
		}

		$this->productdetail_model->edit($id, $arrd_final);

		echo 'OK';
		// $str = '';
		// //for($i = 0; $i < count($arrd); $i += 1) {
		// foreach($arrd as $k => $v) {
		// 	$str .= $k. ', ' . $v['name'];// $arrd[$i]['name'];
		// }

		
	}

	public function edit() {
		$id = $this->uri->segment(4,-1);
		$this->load->model('customer_model');
		$this->load->model('vendor_model');
		$this->load->model('productdetail_model');
 		$editing_row = $this->model->get_row_by_id($id);
 		$detail_rows = $this->productdetail_model->get_rows_by_productid($id)->result_array();
 		
 		// print_r($editing_row);
 		// print_r(json_encode($detail_rows));
		$arr2 = array();
 		foreach($detail_rows as $k => $v) {
 			if($v['other'] != '') {
 				$v['other'] = true;
 			}
 			$arr2[$k] = $v;
		 }
		 //20180502 為了提供他們操作順暢的修正列表，加了一些參數來讓儲存後的導向能較順利一些
		$s = $this->input->get('s');
		$okurl = ($s == '6' ? '/ct/admin/product/w' : '/ct/admin/product/index');
		$view_data = array(
				
				'mode' => 'EDIT',
				'cdata' => $this->customer_model->get_like_name_all()->result_array(),
				'vdata' => $this->vendor_model->get_like_name_all()->result_array(),
				'editing_row' =>$editing_row,
				'detail_rows' =>$arr2,//$detail_rows,
				'id'=>$id,
				's' => $s,
				'okurl' => $okurl

		);
		
		$this->data['content'] = $this->load->view('admin/'.$this->controller.'_form',$view_data,TRUE);
		$this->_render();
	}
	
	public function edit_save() {
		//TODO:檢查未輸入ID或找不到資料時及驗證
		//20140502-因為覺得ckeditor的圖片功能就很好了．雖然有誤砍的可能，但應該不礙事...所以把單張上傳功能拿掉
		// $upload_config['overwrite'] = TRUE;
		// $upload_config['upload_path'] = "uploads/{$this->controller}/{$this->input->post('hd_id')}";
		// $upload_config['allowed_types'] = "jpg";
		// $upload_config['file_name'] = $this->config->item('fn_cover');
		// new_folder($upload_config['upload_path'] );
		
		// $this->load->library('upload',$upload_config);
		
		
		// if(!$this->upload->do_upload('fl_cover') && $_FILES['fl_cover']['name'] !== '' ) {
		// 	die('上傳錯誤:' . $this->upload->display_errors());
		// }
		// else {
		// //上傳成功
		// // 
		// 	/*
		// 	if(  $_FILES['fl_cover']['name'] !==''   ) { //表示確實有選擇圖片

		// 		$config['image_library'] = 'gd2';
		// 		$config['source_image']	= $upload_config['upload_path'] . '/' . $this->config->item('fn_cover').'.jpg';
		// 		$config['create_thumb'] = FALSE;
		// 		$config['maintain_ratio'] = FALSE;
		// 		$config['width']	= 200;
		// 		$config['height']	= 270;
		// 		$this->load->library('image_lib',$config);

		// 		if(!$this->image_lib->resize() ) {
		// 			die('縮圖失敗:' . $this->image_lib->display_errors()); 
		// 		}
		// 	}
		// 	*/
		// }


		$this->model->edit();
		//redirect("/admin/{$this->controller}/edit/{$this->input->post('hd_id')}");
		redirect("/admin/{$this->controller}/index");
	}
	
	public function delete() {
		$id = $this->uri->segment(4,-1);
		$item = array(
			'isdel' => 'X'
			);
		$this->db->where('id', $id);
		$this->db->update('product', $item);
		redirect("/admin/{$this->controller}/index");
	}
	
	private function _render() {
		$this->load->view('admin/admin_main_template',$this->data);
	}
	
	
	public function excel() {
		$id = $this->input->get_post('id');
		$per_page = $this->pager_config['per_page'];
		$q = $this->input->get('n');
		$qi = $this->input->get('qi');
		$c = $this->input->get('c');

		$q = !empty($q) ? $q : urldecode($this->uri->segment(4) );  
		$qi = !empty($qi) ? $qi : urldecode($this->uri->segment(4) );  
		//表示使用者啥也沒輸入就按下按鈕
		// if(empty($q) && $c == '0' && empty($qi)) {
		// 		echo '產生 excel 錯誤';
		// 		die(); //不知是否可以防止後面程式繼續執行..
		// }
		
		//$res = $this->orderdetail_model->get_by_ids($ids)->result_array();
		
		$sql = <<<EOT
		select c.sname as cname, a.name as pname, b.name as itemname, b.tos, b.knum, b.tcs, b.cfs, b.t, b.prt, b.cf, b.sfc, b.price
        from product as a
        join productdetail as b on a.id = b.fk_product
		join customer as c on a.fk_customer = c.id
		where a.id = '{$id}' and b.isdel = ''
EOT;

$this->db->select("c.sname as cname, a.name as pname, b.name as itemname, b.tos, b.knum, b.tcs, b.cfs, b.t, b.prt, b.cf, b.sfc, b.price");
$this->db->from('product as a');
$this->db->join('productdetail as b', 'a.id = b.fk_product');
$this->db->join('customer as c',  'a.fk_customer = c.id');
$this->db->where("a.isdel = ''");
$this->db->where("b.isdel = ''");
if(!empty($q)) {
	$this->db->like('a.name', $q);
}
if(!empty($qi)) {
	$this->db->like('b.name', $qi);
}
if($c != '0') {
			
	$this->db->where('a.fk_customer', $c);
}
		$res = $this->db->get()->result_array();
		// $this->output->enable_profiler(TRUE);
		$cname = '';
		if(count($res) > 0) {
			$cname = $res[0]['cname'];
		}

		$html = $this->load->view('admin/product_blank_excel',array(
					'arrod'=> $res,
					'cname'=> $cname,
					'gentime' => getTodayStr(),
					'data' => $res
				), true);



	 $file="output".date("YmdHis").".xls";	//這行可以將下載的檔案自動加上匯出時的日期時間，方便檔案管理做區分
	 header("Content-type: application/vnd.ms-excel");	//文件內容為excel格式
	 header("Content-Disposition: attachment; filename=$file;"); //將PHP轉成下載的檔案指定名稱與副檔名.xls
	 echo $html;
	}

	//20180502 - 來信表說有些訂單的品名寫0，以及有些產品不見的問題，我看大多是因為在建產品主檔時沒有輸入產品名稱 或是 沒有選擇對應的客戶，所以產品無法和客戶建立關聯。
	//這邊列出一系列沒有品名或沒有選擇客戶的超連結給他修正
	public function w() {
		$sql = <<<EOT
			SELECT * FROM product where (name = '0' or fk_customer = '0' ) and isdel = ''
EOT;
		$res = $this->db->query($sql)->result_array();

		

		$view_data = array(
			'data_list' => $res
		);
		
		$this->data['content'] = $this->load->view('admin/w',$view_data,TRUE);
		$this->_render();	
	}
	
}
