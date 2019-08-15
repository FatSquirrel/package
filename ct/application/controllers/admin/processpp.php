<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Processpp extends MY_Controller  {
	//上傳圖檔檔名的前置字元
	var $prefix;
	var $data;
	var $pager_config;
	var $arrv;
	function __construct() {
		parent::__construct(false);
		$this->load->model('vendor_model');
		$this->arrv = $this->vendor_model->get_like_name_all()->result_array();
		
	}
	
	public function index()
	{

		//$arrod = $this->db->get_where('orderdetail', array())->result_array();
		$sql = <<<EOT
SELECT a.id, b.name AS pname, a.name AS pdname, a.isorderdone AS isorderdone, d.qty AS qty, a.tos, a.knum, a.back, a.tcs, a.cfs, a.qty AS qty_paper, a.t, a.tvendor, a.prt, a.prtvendor, a.sfc, a.sfcvendor, a.heat, a.heatvendor, a.cf, a.cfvendor, a.pst, a.pstvendor, a.ga, a.gavendor, a.glu, a.gluvendor, a.toq, a.toq2, a.toq_n, a.tos2, a.cfqty2, a.isppdone, c.sname AS cusname, a.qty2, a.cfs2
FROM  `orderdetail` AS a
JOIN product AS b ON a.fk_product = b.id
JOIN  `order` AS d ON d.id = a.fk_order
JOIN customer AS c ON c.id = d.fk_customer
WHERE a.isorderdone =  'X'
AND (
a.tvendor !=  ''
OR a.prtvendor !=  ''
OR a.sfcvendor !=  ''
OR a.heatvendor !=  ''
OR a.pstvendor !=  ''
OR a.cfvendor !=  ''
OR a.gavendor !=  ''
OR a.gluvendor !=  ''
)
EOT;
		$arrod_tmp  = $this->db->query($sql)->result_array();
		$arrod = array();
		foreach($arrod_tmp as $k => $v){
			$item = $arrod_tmp[$k];
			$item['tv_name'] = $this->getVName( $v['tvendor']);
			$item['prtv_name'] = $this->getVName( $v['prtvendor']);
			$item['sfcv_name'] = $this->getVName($v['sfcvendor']);
			$item['heatv_name'] = $this->getVName($v['heatvendor']);
			$item['cfv_name'] = $this->getVName($v['cfvendor']);
			$item['pstv_name'] = $this->getVName($v['pstvendor']);
			$item['gav_name'] = $this->getVName($v['gavendor']);
			$item['gluv_name'] = $this->getVName($v['gluvendor']);
			$arrod[$k] = $item;
		}

		// print_r($arrod);
		$this->data['content'] = $this->load->view('admin/'.$this->controller.'_form',
															array(
																	'arrod'=> $arrod
															),TRUE );


		$this->_render();
	}
	public function getVName($vendorid) {

		$name = '';
		foreach($this->arrv as $v) {
			if($vendorid == $v['id']) {
				$name = $v['sname'];
				break;
			}
		}
		return $name;
	}

	public function lists() {
		$arr = $this->db->get('pp')->result_array();
		$this->data['content'] = $this->load->view('admin/'.$this->controller.'_list',
															array(
																	'data_list'=> $arr
															),TRUE );

		$this->_render();
	}
	public function getNextPPSN($testing = false) {
		$arr = $this->db->get('setting', 1)->result_array();
		$arr = $arr[0];
		$sno = $arr['lastppsn'];
		$sno += 1;
		$datestring = "%Y%m%d";
		$todaydate = mdate($datestring);
		$result = $todaydate . fillzero($sno, 4);

		if($testing == false) {
			$arr['lastppsn'] = $arr['lastppsn'] + 1;
			$this->db->empty_table('setting');
			$this->db->insert('setting', $arr);
		}
		return $result;
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

	public function generate() {
		$this->load->model('vendor_model');
		
		$sql = <<<EOT
SELECT a.orderno, a.id, a.donedate, b.name AS pname, a.name AS pdname, a.isorderdone AS isorderdone, d.qty AS qty, a.tos, a.knum, a.back, a.tcs, a.cfs, a.qty AS qty_paper, a.t, a.tvendor, a.prt, a.prtvendor, a.sfc, a.sfcvendor, a.heat, a.heatvendor, a.cf, a.cfvendor, a.pst, a.pstvendor, a.ga, a.gavendor, a.glu, a.gluvendor, a.toq, a.toq2, a.toq_n, a.tos2, a.cfqty2, a.isppdone, c.sname AS cusname, a.qty2, a.cfs2,
a.garemark, a.remark
FROM  `orderdetail` AS a
JOIN product AS b ON a.fk_product = b.id
JOIN  `order` AS d ON d.id = a.fk_order
JOIN customer AS c ON c.id = d.fk_customer

WHERE a.id = '{$this->input->get('id')}'
EOT;
// echo $sql;
		$arrod_tmp  = $this->db->query($sql)->result_array();
		$ppdata = array();
		foreach($arrod_tmp as $k => $v){
			$item = $arrod_tmp[$k];
			$item['tv_name'] = $this->getVName( $v['tvendor']);
			$item['prtv_name'] = $this->getVName( $v['prtvendor']);
			$item['sfcv_name'] = $this->getVName($v['sfcvendor']);
			$item['heatv_name'] = $this->getVName($v['heatvendor']);
			$item['cfv_name'] = $this->getVName($v['cfvendor']);
			$item['pstv_name'] = $this->getVName($v['pstvendor']);
			$item['gav_name'] = $this->getVName($v['gavendor']);
			$item['gluv_name'] = $this->getVName($v['gluvendor']);
			$ppdata[$k] = $item;
		}
if(sizeof($ppdata) == 0) {
	//echo '<script type="text/javascript">alert("找不到資料。");window.close()</script>';
} else {
		$html = $this->load->view('admin/processpp_blank',array(
					'ppdata' => $ppdata[0]
				), true);


		$this->load->library('Pdf');
		$pdf = new TCPDF("L", PDF_UNIT, "A5", true, 'UTF-8', false);
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);
		
		$pdf->setCellHeightRatio(1.95);
		// add a page
		$pdf->AddPage();
		// set font
		$pdf->SetFont('msungstdlight', '', 16);

		// output the HTML content
		$pdf->writeHTML($html, true, false, true, false, '');

		// reset pointer to the last page
		$pdf->lastPage();


		//Close and output PDF document
		$pdf->Output('example_061.pdf', 'I');
}

	}


	public function ajax_processpp_save() {
		
		// $this->load->model('productdetail_model');
		// $this->load->model('orderdetail_model');
		$this->load->helper('date');

		$newpono = 'W'.$this->getNextPPSN();	

		$item_tmp = json_decode($this->input->get_post('item'), true);
		$item = array();
		$item['id'] = $item_tmp['id'];
		$item['isppdone'] = $item_tmp['isppdone'];
		$item['ppno'] = $newpono;
		$this->db->where('id', $item['id']);
		$this->db->update('orderdetail', $item);


		$todaydate = mdate("%Y%m%d");
		$remark = $item_tmp['ppremark'];
		$po = array('id' =>  $newpono,
					'ppdate' => $todaydate,
					'remark' => $remark
				);
		$this->db->insert('pp', $po);
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
