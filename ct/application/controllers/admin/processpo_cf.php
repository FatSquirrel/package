<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Processpo_cf extends MY_Controller  {
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
		$this->load->model('vendor_model');
		//$notdone = $this->db->get_where('orderdetail', array())->result_array();
		$sql = <<<EOT
SELECT 
	a.id, b.name as pname, a.name AS pdname, a.isorderdone as isorderdone,
	d.qty as qty, a.tos, a.knum, a.back, a.tcs, a.cfs, a.qty as qty_paper, a.t,
	 a.tvendor, a.prt, a.prtvendor, a.sfc, a.sfcvendor, a.heat, a.heatvendor,
	  a.cf, a.cfvendor, a.pst, a.pstvendor, a.ga, a.gavendor, a.glu, a.gluvendor,
	  a.toq, a.toq2, a.toq_n, a.tos2, a.cfs2, a.cfqty2, a.ispodone, a.po_cf_no, a.ispo_cf_done
          ,e.sname as vname, c.sname as cname, a.orderno
FROM `orderdetail` AS a 
       JOIN product AS b ON a.fk_product = b.id 
       JOIN `order` as d on d.id = a.fk_order
	   join `customer` as c on a.fk_customer = c.id
	   join `vendor` as e on e.id = a.tvendor
    where d.isdel = '' and a.isorderdone = 'X' and a.isneedpo_cf = 'X' and tvendor != ''
EOT;

		$arrod_tmp  = $this->db->query($sql)->result_array();
		$notdone = array();
		$varr_tmp = array();
		$varr = array();

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
			$item['t_nextvendor'] = '';
			$item['cf_nextvendor'] = '';



			//指送：面紙 > 印刷 > 表面 > 燙金 
			if($item['prtvendor'] != '') {
				$item['t_nextvendor'] = $item['prtvendor'];
			}
			if($item['t_nextvendor'] == '' && $item['sfcvendor'] != '') {
				$item['t_nextvendor'] = $item['sfcvendor'];
			}
			if($item['t_nextvendor'] == '' && $item['heatvendor'] != '') {
				$item['t_nextvendor'] = $item['heatvendor'];
			}
			/*
			if($item['t_nextvendor'] == '' && $item['cfvendor'] != '') {
				$item['t_nextvendor'] = $item['cfvendor'];
			}
			if($item['t_nextvendor'] == '' && $item['pstvendor'] != '') {
				$item['t_nextvendor'] = $item['pstvendor'];
			}
			if($item['t_nextvendor'] == '' && $item['gavendor'] != '') {
				$item['t_nextvendor'] = $item['gavendor'];
			}
			if($item['t_nextvendor'] == '' && $item['gluvendor'] != '') {
				$item['t_nextvendor'] = $item['gluvendor'];
			}*/

			// if($item['pstvendor'] != '') {
			// 	$item['cf_nextvendor'] = $item['pstvendor'];
			// }
			// if($item['cf_nextvendor'] == '' && $item['gavendor'] != '') {
			// 	$item['cf_nextvendor'] = $item['gavendor'];
			// }
			// if($item['cf_nextvendor'] == '' && $item['gluvendor'] != '') {
			// 	$item['cf_nextvendor'] = $item['gluvendor'];
			// }
			$nextvendorcn = $this->getVName($item['t_nextvendor']);
			if($nextvendorcn == '') {
				$nextvendorcn = "無需指送";
			}
			$item['t_nextvendor_cn'] = $nextvendorcn;
			//$item['cf_nextvendor_cn'] = $this->getVName($item['cf_nextvendor']);
			$notdone[$k] = $item;
		}

$today = date("Ymd", time());
//開始組成右半邊的完成清單
$sql = <<<EOT

SELECT 
	a.id, b.name as pname, a.name AS pdname, a.isorderdone as isorderdone,
	d.qty as qty, a.tos, a.knum, a.back, a.tcs, a.cfs, a.qty as qty_paper, a.t,
	 a.tvendor, a.prt, a.prtvendor, a.sfc, a.sfcvendor, a.heat, a.heatvendor,
	  a.cf, a.cfvendor, a.pst, a.pstvendor, a.ga, a.gavendor, a.glu, a.gluvendor,
	  a.toq, a.toq2, a.toq_n, a.tos2, a.cfs2, a.cfqty2, a.ispodone, a.po_cf_no, a.ispo_cf_done
          ,e.sname as vname, c.sname as cname, a.po_cf_no, podate, a.orderno
FROM `orderdetail` AS a 
       JOIN product AS b ON a.fk_product = b.id 
       JOIN `order` as d on d.id = a.fk_order
	   join `customer` as c on a.fk_customer = c.id
	   join `vendor` as e on e.id = a.tvendor
	   JOIN po_cf AS f ON a.po_cf_no = f.id
	where d.isdel = '' and a.isorderdone = 'X' and a.isneedpo_cf = 'X' and tvendor != ''  and  '{$today}' <= DATE_ADD(f.podate, INTERVAL 2 
WEEK) 
	order by vname, podate
EOT;

//  echo $sql;

	$res = $this->db->query($sql)->result_array();
	$grpCount = 0;
	$thisIdx = '';
	$lastIdx = '';
	$done = array();
	foreach($res as $item) {
		$idx = $item['po_cf_no'];
		
		if(!isset($done[$idx]))      {
			$done[$idx] = array();
		} 
		$done[$idx][] = $item;

	}// end foreach


	$sql = <<<EOT
SELECT DISTINCT b.id, b.sname, b.name
FROM  `orderdetail` AS a
JOIN  `vendor` AS b ON a.cfvendor = b.id
join `order` as c on a.fk_order = c.id
WHERE a.isorderdone =  'X'
AND a.isneedpo_cf =  'X'
AND ispo_cf_done =  ''
AND cfvendor !=  ''
and c.isdel = ''
EOT;
		// print_r($notdone);
		$varr = $this->db->query($sql)->result_array();
		$this->data['content'] = $this->load->view('admin/'.$this->controller.'_form',
										array(
												'notdone'=> $notdone,
												'done' => $done,
												'vdata' => $varr
												
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
		select a.orderno, b.name as pname, c.sname as cname, a.cf, a.cfs2,a.cfqty2, cf.sname as cfvendor
		from orderdetail as a  
		join product as b on a.fk_product = b.id
		join customer as c on a.fk_customer = c.id
		left join vendor as cf on a.cfvendor = cf.id
		where a.po_cf_no in ({$ids})
EOT;
		$res = $this->db->query($sql)->result_array();

		$html = $this->load->view('admin/processpo_cf_blank_excel',array(
					'arrod'=> $res,
					'b' => $b,
					'e' => $e,
					'gentime' => getTodayStr(),
					'data' => $res
				), true);



	 $file="output".date("YmdHis").".xls";	//這行可以將下載的檔案自動加上匯出時的日期時間，方便檔案管理做區分
	 header("Content-type: application/vnd.ms-excel");	//文件內容為excel格式
	 header("Content-Disposition: attachment; filename=$file;"); //將PHP轉成下載的檔案指定名稱與副檔名.xls
	 echo $html;
	}

	public function generate() {
		$this->load->model('vendor_model');
		
		$sql = <<<EOT
SELECT a.id, a.remark, a.podate, b.cf, b.cfs2, b.cfqty2, b.cfvendor, b.pst, b.pstvendor, b.ga, b.gavendor, b.garemark, b.glu, b.gluvendor, b.orderno
FROM po_cf AS a
JOIN orderdetail AS b ON b.po_cf_no = a.id
JOIN vendor AS c ON c.id = b.cfvendor
where a.id = '{$this->input->get('id')}'
EOT;
// echo $sql;
		$arrod_tmp  = $this->db->query($sql)->result_array();
		$arrod = array();
		$count = 1;
		foreach($arrod_tmp as $k => $v){
			$item = $arrod_tmp[$k];
			$item['itemno'] = $count;
			$count++;
			// $item['tv_name'] = $this->getVName( $v['tvendor']);
			// $item['prtv_name'] = $this->getVName( $v['prtvendor']);
			// $item['sfcv_name'] = $this->getVName($v['sfcvendor']);
			// $item['heatv_name'] = $this->getVName($v['heatvendor']);
			// $item['cfv_name'] = $this->getVName($v['cfvendor']);
			// $item['pstv_name'] = $this->getVName($v['pstvendor']);
			// $item['gav_name'] = $this->getVName($v['gavendor']);
			// $item['gluv_name'] = $this->getVName($v['gluvendor']);

			$item['cf_nextvendor'] = '';
			if($item['pstvendor'] != '') {
				$item['cf_nextvendor'] = $item['pstvendor'];
			}
			if($item['cf_nextvendor'] == '' && $item['gavendor'] != '') {
				$item['cf_nextvendor'] = $item['gavendor'];
			}
			if($item['cf_nextvendor'] == '' && $item['gluvendor'] != '') {
				$item['cf_nextvendor'] = $item['gluvendor'];
			}

			$nextcfvendorcn = $this->getVName($item['cf_nextvendor']);
			if($nextcfvendorcn == '') {
				$nextcfvendorcn = "無需指送";
			}
			$item['cf_nextvendor_cn'] = $nextcfvendorcn;
			$arrod[$k] = $item;
		}

		$html = $this->load->view('admin/processpo_cf_blank',array(
					'arrod' => $arrod
				), true);

if(sizeof($arrod) == 0) {
	echo '<script type="text/javascript">alert("找不到資料。");window.close()</script>';
} else {
		$this->load->library('Pdf');
		if(sizeof($arrod) > 10) {
			$pdf = new TCPDF('P', PDF_UNIT, 'A4', true, 'UTF-8', false);
		} else {
			$pdf = new TCPDF('L', PDF_UNIT, 'A5', true, 'UTF-8', false);
		}
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);
		$tagvs = array(
			'div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n' => 0))
		);
		
		$pdf->setHtmlVSpace($tagvs);
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


	public function ajax_search() {
		$bd = $this->input->get_post('bd');
		$ed = $this->input->get_post('ed');


		
		if($bd == '' && $ed == '') { // 若 起訖沒輸入就用預設撈取 ==> 等於是index的條件，不再重複撰寫
			$today = date("Ymd", time());
			$sql = <<<EOT

			SELECT 
				a.id, b.name as pname, a.name AS pdname, a.isorderdone as isorderdone,
				d.qty as qty, a.tos, a.knum, a.back, a.tcs, a.cfs, a.qty as qty_paper, a.t,
					a.tvendor, a.prt, a.prtvendor, a.sfc, a.sfcvendor, a.heat, a.heatvendor,
					a.cf, a.cfvendor, a.pst, a.pstvendor, a.ga, a.gavendor, a.glu, a.gluvendor,
					a.toq, a.toq2, a.toq_n, a.tos2,a.cfqty2, a.ispodone, a.po_cf_no, a.ispo_cf_done
						,e.sname as vname, c.sname as cname, a.po_cf_no, podate, a.orderno
			FROM `orderdetail` AS a 
					JOIN product AS b ON a.fk_product = b.id 
					JOIN `order` as d on d.id = a.fk_order
					join `customer` as c on a.fk_customer = c.id
					join `vendor` as e on e.id = a.tvendor
					JOIN po_cf AS f ON a.po_cf_no = f.id
				where d.isdel = '' and a.isorderdone = 'X' and a.isneedpo_cf = 'X' and tvendor != ''  and  '{$today}' <= DATE_ADD(f.podate, INTERVAL 2 
			WEEK) 
				order by vname, podate
EOT;
			
		} else {
			$sql = <<<EOT

			SELECT 
				a.id, b.name as pname, a.name AS pdname, a.isorderdone as isorderdone,
				d.qty as qty, a.tos, a.knum, a.back, a.tcs, a.cfs, a.qty as qty_paper, a.t,
					a.tvendor, a.prt, a.prtvendor, a.sfc, a.sfcvendor, a.heat, a.heatvendor,
					a.cf, a.cfvendor, a.pst, a.pstvendor, a.ga, a.gavendor, a.glu, a.gluvendor,
					a.toq, a.toq2, a.toq_n, a.tos2,a.cfqty2, a.ispodone, a.po_cf_no, a.ispo_cf_done
						,e.sname as vname, c.sname as cname, a.po_cf_no, podate, a.orderno
			FROM `orderdetail` AS a 
					JOIN product AS b ON a.fk_product = b.id 
					JOIN `order` as d on d.id = a.fk_order
					join `customer` as c on a.fk_customer = c.id
					join `vendor` as e on e.id = a.tvendor
					JOIN po_cf AS f ON a.po_cf_no = f.id
				where d.isdel = '' and a.isorderdone = 'X' and a.isneedpo_cf = 'X' and tvendor != ''  and 
				f.podate between {$bd} and {$ed}
				order by vname, podate
EOT;
		}		
		//  echo $sql;
		
		$res = $this->db->query($sql)->result_array();
		$grpCount = 0;
		$thisIdx = '';
		$lastIdx = '';
		$done = array();
		foreach($res as $item) {
			$idx = $item['po_cf_no'];
			
			if(!isset($done[$idx]))      {
				$done[$idx] = array();
			} 
			$done[$idx][] = $item;
		}

		echo json_encode($done);
	}


	public function ajax_setnotpurchase() {
		$this->load->model('orderdetail_model');
		$id = $this->input->get_post('id');

		if($id == '') { 
			echo 'NG';
			die();
		}

		$this->orderdetail_model->setnotpurchase_t($id);

		//將採購單主檔上刪除旗標
		$item = array(
			'df' => 'X'
			);

		$this->db->where('id', $id);
		$this->db->update('po_cf', $item);
		echo 'OK';


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
		$arr = $this->db->get('po_cf')->result_array();
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

	public function ajax_processpo_save() {
		
		// $this->load->model('productdetail_model');
		// $this->load->model('orderdetail_model');
		$this->load->helper('date');

		$newpono = 'T'.$this->getNextPOSN();		
		$items = json_decode($this->input->get_post('items'), true);
		$item = array(
				'ispo_cf_done' => 'X'
			);
		foreach($items as $k => $v) {
			$item['po_cf_no'] = $newpono;
			$this->db->where('id', $v['id']);
			$this->db->update('orderdetail', $item);
		}

		$todaydate = mdate("%Y%m%d");
		$remark = $this->input->get_post('remark');
		$po = array('id' =>  $newpono,
					'podate' => $todaydate,
					'remark' => $remark
				);
		$this->db->insert('po_cf', $po);

		echo 'OK';

	}

	public function getNextPOSN($testing = false) {
		$arr = $this->db->get('setting', 1)->result_array();
		$arr = $arr[0];
		$sno = $arr['lastposn'];
		$sno += 1;
		$datestring = "%Y%m%d";
		$todaydate = mdate($datestring);
		$result = $todaydate . fillzero($sno, 4);

		if($testing == false) {
			$arr['lastposn'] = $arr['lastposn'] + 1;
			$this->db->empty_table('setting');
			$this->db->insert('setting', $arr);
		}
		return $result;
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
