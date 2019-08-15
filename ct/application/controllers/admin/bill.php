<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bill extends MY_Controller  {
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

		$today = date("Ymd", time());
		$sql = <<<EOT
select a.id, a.date, a.begindate, a.enddate, a.remark, b.sname as cn, a.tax from bill as a
left join customer as b on a.fk_customer = b.id 
WHERE  '{$today}' <= DATE_ADD(a.enddate, INTERVAL 6 
MONTH) 
 order by id desc 
EOT;

		$bills = $this->db->query($sql)->result_array();
		$this->data['content'] = $this->load->view('admin/'.$this->controller.'_list',
															array(
																	'data_list' => $bills,
																	'customers' => $this->db->get('customer')->result_array()
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


	public function remove() {
		$id = $this->input->post('id');
		
		$item = array(
			'billno' => '',
			'isbill' => ''
		);
		$this->db->where('billno', $id);
		$this->db->update('orderdetail', $item);
		$this->db->delete('bill', array('id' => $id )); 
		
		echo 'OK';
	}


	public function preview() {
		$this->load->model('vendor_model');
		$cid = $this->input->get('cid');
		$resCus = $this->db->get_where('customer', array('id' => $cid))->result_array();
		$cname = $resCus[0]['name'];
		$b = $this->input->get('bstr');
		$e = $this->input->get('estr');
		$tax = $this->input->get('tax');

		$companycode = $resCus[0]['companyno'];
		$sql = <<<EOT
SELECT a.id AS oid, b.id AS did, e.date AS delivdate, e.id AS delivno, c.name AS pname, b.name AS pdname, d.name AS cname, f.price, a.qty, (
f.price * a.qty
) AS subtotal, a.prtpr, a.prtpr_price, a.bladepr, a.bladepr_price
FROM  `order` AS a
JOIN  `orderdetail` AS b ON a.id = b.fk_order
JOIN  `product` AS c ON c.id = a.fk_product
join  `productdetail` as f on b.fk_productdetail = f.id
JOIN  `customer` AS d ON d.id = a.fk_customer
JOIN  `delivery` AS e ON e.id = b.delivno
WHERE b.isdeliv =  'X'
AND e.date >=  '{$b}'
AND e.date <=  '{$e}'
AND a.fk_customer = '{$cid}'

ORDER BY a.id
EOT;

		$result = $this->db->query($sql)->result_array();
		if(sizeof($result) < 1) {
			echo '<script type="text/javascript">alert("此區間內無所選客戶並未建立請款的送貨單，請再擇期.");window.close()</script>';
			//echo $sql;
			return false;
		}


		$idx = 0;
		$sum = 0;
		$lastoid = '';
		$arrfee = array();
		foreach($result as $v) {
			$idx += 1;
			if($v['oid'] != $lastoid) {
				$sum += $v['prtpr'] * $v['prtpr_price'] + $v['bladepr'] * $v['bladepr_price'];
				$lastoid = $v['oid'];
			}
			$sum += $v['subtotal'];
		}//end foreach
		
		$partEnd = explode('/', $e);
		$html = $this->load->view('admin/bill_blank_pdf',array(
					'arrod'=> $result,
					'mon' => $this->n2m($partEnd[1]),
					'b' => $b,
					'e' => $e,
					'cname' => $cname,
					'companycode' => $companycode,
					'remark' => $this->input->get('remark'),
					'sum' => $sum,
					'tax' => $tax,
					'aftertax' => $sum * $tax / 100,
					'total' => $sum * (1+$tax/100)
				), true);

		// $this->load->view('admin/blank',array(
		// 			'arrod'=> $arrod,
		// 			'b' => $this->input->get('b'),
		// 			'e' => $this->input->get('e')
		// 		));

		$this->load->library('Pdf');
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

		// add a page
		$pdf->AddPage();
		// set font
		$pdf->SetFont('msungstdlight', '', 16);

		$pdf->writeHTML($html, true, false, true, false, '');
		// output the HTML content

		// reset pointer to the last page
		$pdf->lastPage();


		//Close and output PDF document
		$pdf->Output('example_061.pdf', 'I');
	}


	public function excel() {
		$this->load->model('vendor_model');
		$resBill = $this->db->get_where('bill', array('id' => $this->input->get('id')), 1)->result_array();
		$resCus = $this->db->get_where('customer', array('id' => $resBill[0]['fk_customer']))->result_array();
		$cname = $resCus[0]['name'];
		$b = $resBill[0]['begindate'];
		$e = $resBill[0]['enddate'];
		$companycode = $resCus[0]['companyno'];
		$tax = $resBill[0]['tax'];
		$sql = <<<EOT
SELECT a.id AS oid, b.id AS did, e.date AS delivdate, e.id AS delivno, c.name AS pname, b.name AS pdname, d.name AS cname, f.price, a.qty, (
f.price * a.qty
) AS subtotal, a.prtpr, a.prtpr_price, a.bladepr, a.bladepr_price
FROM  `order` AS a
JOIN  `orderdetail` AS b ON a.id = b.fk_order
join  `productdetail` as f on b.fk_productdetail = f.id
JOIN  `product` AS c ON c.id = a.fk_product
JOIN  `customer` AS d ON d.id = a.fk_customer
JOIN  `delivery` AS e ON e.id = b.delivno
WHERE b.isdeliv =  'X'
AND e.date >=  '{$b}'
AND e.date <=  '{$e}'
AND a.fk_customer = '{$resBill[0]['fk_customer']}'
AND a.isdel =  ''
ORDER BY a.id
EOT;
// echo $sql;

		$result  = $this->db->query($sql)->result_array();
		$idx = 0;
		$sum = 0;
		$lastoid = '';
		$arrfee = array();
		foreach($result as $v) {
			$idx += 1;
			if($v['oid'] != $lastoid) {
				$sum += $v['prtpr'] * $v['prtpr_price'] + $v['bladepr'] * $v['bladepr_price'];
				$lastoid = $v['oid'];
			}
			$sum += $v['subtotal'];
		}//end foreach
		// print_r(json_encode($result));
		$partEnd = explode('-', $resBill[0]['enddate']);
		$html = $this->load->view('admin/bill_blank_excel',array(
					'arrod'=> $result,
					'mon' => $this->n2m($partEnd[1]),
					'b' => $resBill[0]['begindate'],
					'e' => $resBill[0]['enddate'],
					'cname' => $cname,
					'companycode' => $companycode,
					'remark' => $resBill[0]['remark'],
					'sum' => $sum,
					'tax' => $tax,
					'aftertax' => $sum * $tax / 100,
					'total' => $sum * (1+$tax/100)
				), true);

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

	public function testExcelHtml() {
		//load our new PHPExcel library
$filename = 'mytest.xls';
header('Content-Type: application/vnd.ms-excel'); //mime type
header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
header('Cache-Control: max-age=0'); //no cache

$html = <<<EOT
	<table border="1">
		<tr><td>123</td></tr>
		<tr><td>222</td></tr>
		<tr><td>333</td></tr>
	</table>
EOT;
echo $html;

	}

	public function testExcel() {
		//load our new PHPExcel library
$this->load->library('excel');
//activate worksheet number 1
$this->excel->setActiveSheetIndex(0);
//name the worksheet
$this->excel->getActiveSheet()->setTitle('test worksheet');
//set cell A1 content with some text
$this->excel->getActiveSheet()->setCellValue('A1', 'This is just some text value');
//change the font size
$this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);
//make the font become bold
$this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
//merge cell A1 until D1
$this->excel->getActiveSheet()->mergeCells('A1:D1');
//set aligment to center for that merged cell (A1 to D1)
$this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$filename='just_some_random_name.xls'; //save our workbook as this file name
header('Content-Type: application/vnd.ms-excel'); //mime type
header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
header('Cache-Control: max-age=0'); //no cache
//save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
//if you want to save it as .XLSX Excel 2007 format
$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
//force user to download the Excel file without writing it to server's HD
$objWriter->save('php://output');

	}
	public function generate() {
		$this->load->model('vendor_model');
		$resBill = $this->db->get_where('bill', array('id' => $this->input->get('id')), 1)->result_array();
		$resCus = $this->db->get_where('customer', array('id' => $resBill[0]['fk_customer']))->result_array();
		$cname = $resCus[0]['name'];
		$b = $resBill[0]['begindate'];
		$e = $resBill[0]['enddate'];
		$companycode = $resCus[0]['companyno'];
		$tax = $resBill[0]['tax'];
		$sql = <<<EOT
SELECT a.id AS oid, b.id AS did, e.date AS delivdate, e.id AS delivno, c.name AS pname, b.name AS pdname, d.name AS cname, f.price, a.qty, (
f.price * a.qty
) AS subtotal, a.prtpr, a.prtpr_price, a.bladepr, a.bladepr_price
FROM  `order` AS a
JOIN  `orderdetail` AS b ON a.id = b.fk_order
JOIN  `product` AS c ON c.id = a.fk_product
join  `productdetail` as f on b.fk_productdetail = f.id
JOIN  `customer` AS d ON d.id = a.fk_customer
JOIN  `delivery` AS e ON e.id = b.delivno
WHERE b.isdeliv =  'X'
AND e.date >=  '{$b}'
AND e.date <=  '{$e}'
AND a.fk_customer = '{$resBill[0]['fk_customer']}'
AND a.isdel =  ''
ORDER BY a.id
EOT;
// echo $sql;

		$result  = $this->db->query($sql)->result_array();
		$idx = 0;
		$sum = 0;
		$lastoid = '';
		$arrfee = array();
		foreach($result as $v) {
			$idx += 1;
			if($v['oid'] != $lastoid) {
				$sum += $v['prtpr'] * $v['prtpr_price'] + $v['bladepr'] * $v['bladepr_price'];
				$lastoid = $v['oid'];
			}
			$sum += $v['subtotal'];
		}//end foreach
		// print_r(json_encode($result));
		$partEnd = explode('-', $resBill[0]['enddate']);
		$html = $this->load->view('admin/bill_blank_pdf',array(
					'arrod'=> $result,
					'mon' => $this->n2m($partEnd[1]),
					'b' => $resBill[0]['begindate'],
					'e' => $resBill[0]['enddate'],
					'cname' => $cname,
					'companycode' => $companycode,
					'remark' => $resBill[0]['remark'],
					'sum' => $sum,
					'tax' => $tax,
					'aftertax' => $sum * $tax / 100,
					'total' => $sum * (1+$tax/100)
				), true);

		// $this->load->view('admin/blank',array(
		// 			'arrod'=> $arrod,
		// 			'b' => $this->input->get('b'),
		// 			'e' => $this->input->get('e')
		// 		));

		$this->load->library('Pdf');
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

		// add a page
		$pdf->AddPage();
		// set font
		$pdf->SetFont('msungstdlight', '', 16);

		$pdf->writeHTML($html, true, false, true, false, '');
		// output the HTML content

		// reset pointer to the last page
		$pdf->lastPage();


		//Close and output PDF document
		$pdf->Output('example_061.pdf', 'I');
		

	}

	public function pdf(){
		$this->load->library('Pdf');

		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
		$pdf->SetTitle('My Title');
		$pdf->SetHeaderMargin(30);
		$pdf->SetTopMargin(20);
		$pdf->setFooterMargin(20);
		$pdf->SetAutoPageBreak(true);
		$pdf->SetAuthor('Author');
		$pdf->SetDisplayMode('real', 'default');
		$pdf->SetFont('msungstdlight','',16);
		$pdf->Write(5, 'Some 中文測試試試 text');
		$pdf->Output('My-File-Name.pdf', 'I');

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

	public function ajax_save() {
		

		$this->load->helper('date');

		$newbillno = 'B'.$this->getNextSN();	
		$tax = $this->input->post('tax');
				$sql = <<<EOT
SELECT a.id AS oid, b.id AS did, e.date AS delivdate, e.id AS delivno, c.name AS pname, b.name AS pdname, d.name AS cname, b.price, a.qty, (
b.price * a.qty
) AS subtotal
FROM  `order` AS a
JOIN  `orderdetail` AS b ON a.id = b.fk_order
JOIN  `product` AS c ON c.id = a.fk_product
JOIN  `customer` AS d ON d.id = a.fk_customer
JOIN  `delivery` AS e ON e.id = b.delivno
WHERE b.isdeliv =  'X'
AND e.date >=  '{$this->input->post('begindate')}'
AND e.date <=  '{$this->input->post('enddate')}'
AND a.fk_customer = '{$this->input->post('customerid')}'
AND b.billno = ''
ORDER BY a.id
EOT;

$query = $this->db->query($sql)->result_array();
if(sizeof($query) < 1) {
	echo '此區間內無所選客戶並未建立請款的送貨單，請再擇期.';
	return false;
}

foreach($query as $v) {
	$item = array(
		'billno' => $newbillno,
		'isbill' => 'X'
	);
	$this->db->where('id', $v['did']);
	$this->db->update('orderdetail', $item);
}
	
		$todaydate = mdate("%Y%m%d");
		//$remark = $this->input->get_post('remark');
		$bill = array('id' =>  $newbillno,
					'date' => $todaydate,
					'fk_customer' => $this->input->post('customerid'),
					'begindate' => $this->input->post('begindate'),
					'enddate' => $this->input->post('enddate'),
					'remark' => $this->input->post('remark'),
					'tax' => $tax
				);
		$this->db->insert('bill', $bill);

		echo 'OK';

	}

	public function getNextSN($testing = false) {
		$arr = $this->db->get('setting', 1)->result_array();
		$arr = $arr[0];
		$sno = $arr['lastbillsn'];
		$sno += 1;
		$datestring = "%Y%m%d";
		$todaydate = mdate($datestring);
		$result = $todaydate . fillzero($sno, 4);

		if($testing == false) {
			$arr['lastbillsn'] = $arr['lastbillsn'] + 1;
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
	

	public function n2m($n){
		//轉換數字月份成中文月份
		switch($n){
			case '01':
				return '一';
			case '02':
				return '二';
			case '03':
				return '三';
			case '04':
				return '四';
			case '05':
				return '五';
			case '06':
				return '六';
			case '07':
				return '七';
			case '08':
				return '八';
			case '09':
				return '九';
			case '10':
				return '十';
			case '11':
				return '十一';
			case '12':
				return '十二';
			default:
				return $n;
		}
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
