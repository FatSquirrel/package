<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Processorder extends MY_Controller  {
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
	d.qty as qty, a.tos, a.knum, a.back, a.cfs, a.qty as qty_paper, a.t, a.tvendor, a.prt,
	a.prtvendor, a.sfc, a.sfcvendor, a.heat, a.heatvendor, a.cf, a.cfvendor, a.pst, a.pstvendor,
	a.ga, a.gavendor, a.glu, a.gluvendor, a.cfreq, a.orderno

FROM   `orderdetail` AS a 
       JOIN product AS b 
         ON a.fk_product = b.id 
       JOIN customer AS c 
         ON b.fk_customer = c.id 
       join `order` as d
       on d.id = a.fk_order 
       
       where d.isdel = '' 
       	and a.isneedother = '' 
       	and '{$today}' <= DATE_ADD(d.createdate, INTERVAL 2 WEEK) 
       order by a.donedate desc
EOT;

		$arrod  = $this->db->query($sql)->result_array();
	/*	foreach($item as $k => $){

		}*/
		$this->load->model('vendor_model');
//         WHERE a.isorderdone != 'X'
		$this->data['content'] = $this->load->view('admin/'.$this->controller.'_list',
															array(
																	'arrod'=> $arrod,
																	'vdata' => $this->vendor_model->get_like_name_all()->result_array()
															),TRUE );


		$this->_render();
	}
	
	public function excel() {
		$ids = $this->input->get_post('ids');
		$ids = explode(',' , $ids);
		$b = $this->input->get_post('bd');
		$e = $this->input->get_post('ed');
		$this->load->model('orderdetail_model');

		
		//$res = $this->orderdetail_model->get_by_ids($ids)->result_array();


		foreach($ids as $k => $v) {
			$ids[$k] = '\'' . $v . '\'';
		}
		$ids = implode(',', $ids);
		$sql = <<<EOT
		select a.orderno, b.name as pname, a.name, c.sname as cname, a.qty, prt.sname as prtvendor, a.prt, sfc.sname as sfcvendor, sfc, pst.sname as pstvendor, pst, ga.sname as gavendor, ga, glu.sname as gluvendor, glu
		from orderdetail as a  
		join product as b on a.fk_product = b.id
		join customer as c on a.fk_customer = c.id
		left join vendor as prt on a.prtvendor = prt.id
		left join vendor as sfc on a.sfcvendor = sfc.id
		left join vendor as pst on a.pstvendor = pst.id
		left join vendor as ga on a.gavendor = ga.id
		left join vendor as glu on a.gluvendor = glu.id
		where a.id in ({$ids})
EOT;

		$res = $this->db->query($sql)->result_array();

		$html = $this->load->view('admin/processorder_blank_excel',array(
					'arrod'=> $res,
					'b' => $b,
					'e' => $e,
					'gentime' => getTodayStr(),
					'data' => $res
				), true);
;
		// $this->load->view('admin/blank',array(
		// 			'arrod'=> $arrod,
		// 			'b' => $this->input->get('b'),
		// 			'e' => $this->input->get('e')
		// 		));

	 $file="output".date("YmdHis").".xls";	//這行可以將下載的檔案自動加上匯出時的日期時間，方便檔案管理做區分
	 header("Content-type: application/vnd.ms-excel");	//文件內容為excel格式
	 header("Content-Disposition: attachment; filename=$file;"); //將PHP轉成下載的檔案指定名稱與副檔名.xls
	 echo $html;
	}
	public function ajax_search() {
		$bd = $this->input->get_post('bd');
		$ed = $this->input->get_post('ed');


		
		if($bd == '' && $ed == '') { // 若 起訖沒輸入就用預設撈取 ==> 等於是index的條件，不再重複撰寫
			redirect("/admin/{$this->controller}/index");
			die();
		}
		$sql = <<<EOT
SELECT 
	a.id, b.name as pname, a.name AS pdname,c.sname as cname, a.isorderdone as isorderdone,
	d.qty as qty, a.tos, a.knum, a.back, a.cfs, a.qty as qty_paper, a.t, a.tvendor, a.prt,
	a.prtvendor, a.sfc, a.sfcvendor, a.heat, a.heatvendor, a.cf, a.cfvendor, a.pst, a.pstvendor,
	a.ga, a.gavendor, a.glu, a.gluvendor, e.cfreq

FROM   `orderdetail` AS a 
       JOIN product AS b 
         ON a.fk_product = b.id 
       JOIN customer AS c 
         ON b.fk_customer = c.id 
       join `order` as d
       on d.id = a.fk_order 
       JOIN  `productdetail` AS e ON e.id = a.fk_productdetail
       where d.isdel = '' 
       	and a.isneedother = '' 
		   and d.createdate >= {$bd} and d.createdate <= {$ed}
		   and a.isorderdone = 'X'
       order by a.donedate desc
EOT;

		$arrod  = $this->db->query($sql)->result_array();
		
		echo json_encode($arrod);


	}
	
	public function ajax_setnotdone() {
		$this->load->model('orderdetail_model');
		$id = $this->input->get_post('id');

		if($id == '') { 
			echo 'NG';
			die();
		}

		$this->orderdetail_model->setnotdone($id);
		echo 'OK';


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
	public function ajax_processorder_save() {
		
		// $this->load->model('productdetail_model');
		// $this->load->model('orderdetail_model');

		$item = json_decode($this->input->get_post('item'), true);

		//$newid = $this->order_model->add($fk_customer, $fk_product, $qty, $etd, $prtpr, $prtpr_price, $bladepr, $bladepr_price);
		//因為直接使用CI的update比較快，所以移除掉多餘的欄位(陣列元素)
		unset($item['pname']);
		unset($item['pdname']);
		unset($item['cname']);
		unset($item['qty']);
		unset($item['qty_paper']);
		unset($item['toqFormula']);
		unset($item['cfqtyFormula']);

		$item['isorderdone'] = 'X';
		$item['donedate'] = date('Y-m-d');
		if($item['tvendor'] != '') {
			$item['isneedpo_t'] = 'X';
		}
		if($item['cfvendor'] != '') {
			
			$item['isneedpo_cf'] = 'X';
		}

		$this->db->where('id', $item['id']);
		$this->db->update('orderdetail', $item);


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
