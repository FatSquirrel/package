<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Delivery extends MY_Controller  {
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
		//$arrod = $this->db->get_where('orderdetail', array())->result_array();
		$sql = <<<EOT
select a.id as oid, b.id as did, a.etd as etd, c.name as pname , b.name as pdname , d.sname as cname, a.etd ,b.price, a.qty, (b.price * a.qty) as subtotal
, a.prtpr, a.prtpr_price, a.isprtprdeliv as isprtprdeliv, a.bladepr, a.bladepr_price, a.isbladeprdeliv  as isbladeprdeliv
from 
`order` as a join
`orderdetail` as b on a.id = b.fk_order join 
`product` as c on c.id = a.fk_product join
`customer` as d on d.id = a.fk_customer
where a.isdel = '' and b.isdeliv = '' and b.isnotdeliv = ''
order by a.etd desc
EOT;

// $a = array(
// 		array(
// 		'id' => 123,
// 		'name' => 'xxx',
// 		'details' => array(
// 				array('pdn' => 'pdn1', 'price' => 999, 'qty' => 10, 'subtotal' => 9990),
// 				array('pdn' => 'pdn1', 'price' => 998, 'qty' => 10, 'subtotal' => 9980)
// 			)),
// 		array(
// 		'id' => 456,
// 		'name' => 'yyy',
// 		'details' => array(
// 				array('pdn' => 'pdn1', 'price' => 999, 'qty' => 10, 'subtotal' => 9990),
// 				array('pdn' => 'pdn1', 'price' => 998, 'qty' => 10, 'subtotal' => 9980)
// 			)
// 		)
// 	);

		$result  = $this->db->query($sql)->result_array();
		$hs = array();
		$h = array();
		$ds = array();
		$d = array();
		$arrod = array();
		$tmp = array();
		$ids = array();
		foreach($result as $k => $v){
			if(array_search($v['oid'], $ids) === false) {
				$ids[] = $v['oid'];

				$h = array(
						'id' => $v['oid'],
						'etd' => $v['etd'],
						'pn' => $v['pname'],
						'cn' => $v['cname'],
						'qty' => $v['qty'],
						'prtpr' => $v['prtpr'],
						'prtpr_price' => $v['prtpr_price'],
						'bladepr' => $v['bladepr'],
						'bladepr_price' => $v['bladepr_price'],
						'price' => $v['prtpr'] * $v['prtpr_price'] + $v['bladepr'] * $v['bladepr_price'], //等等下面會總結
						'subtotal' => $v['prtpr'] * $v['prtpr_price'] + $v['bladepr'] * $v['bladepr_price'],
						'isprtprdeliv' => $v['isprtprdeliv'],
						'isbladeprdeliv' => $v['isbladeprdeliv'],
						'details' => array(),
				);
				$hs[] = $h;
			}
		}
// print_r($ids);
// echo "<br />";

		foreach($result as $k => $v){
			$idx = array_search($v['oid'], $ids);

			if($idx !== false) {
				//echo $idx . ' -> ' . $v['oid'] . ' -> ' . $v['price'] . '<br />';
				$hs[$idx]['price'] += $v['price'];
				$hs[$idx]['subtotal'] += $v['price'] * $v['qty'];
			}
		}
		
		foreach($hs as $k => $v) {
			$ds = array();
			foreach($result as $k2 => $v2) {
				if($v['id'] === $v2['oid']) {
					$ds[] = array(
							// 'selected' => true,
							'did' => $v2['did'],
							'pdn' => $v2['pdname'],
							'price' => $v2['price'],
							'qty' => $v2['qty'],
							'subtotal' => $v2['price'] * $v2['qty'],
							'isdeliv' => ''
						);

				}
			}
			//此訂單有設定印刷費並且還未出貨
			if($v['prtpr_price'] > 0 && $v['isprtprdeliv'] == '') {
				$ds[] = array(
						// 'selected' => true,
						'did' => 'prt',
						'pdn' => '印刷版費',
						'price' => $v['prtpr_price'],
						'qty' => $v['prtpr'],
						'subtotal' => $v['prtpr_price'] * $v['prtpr'],
						'isdeliv' => ''
					);
			}

			//此訂單有設定刀模費並還未出貨

			if($v['bladepr_price'] > 0 && $v['isbladeprdeliv'] == '') {
				$ds[] = array(
					'did' => 'blade',
					'pdn' => '刀模費',
					'price' => $v['bladepr_price'],
					'qty' => $v['bladepr'],
					'subtotal' => $v['bladepr_price'] * $v['bladepr'],
					'isdeliv' => ''
					//'oid' => $v['id'] //將此項次對應的表頭id也傳進去，這樣傳回來時要才可以設定刀模、印刷費已出貨狀態
					);
			}
			$hs[$k]['details'] = $ds;
		}

		$arrod = $hs;

// print_r(json_encode($arrod));
		$this->data['content'] = $this->load->view('admin/'.$this->controller.'_form',
															array(
																	'arrod'=> $arrod,
																	'vdata' => $this->vendor_model->get_like_name_all()->result_array()
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
		$arr = $this->db->get('po_t')->result_array();
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

	public function ajax_save() {
		
		// $this->load->model('productdetail_model');
		// $this->load->model('orderdetail_model');
		$this->load->helper('date');

		$newdelivno = 'D'.$this->getNextSN();		
		$items = json_decode($this->input->get_post('items'), true);
		$item = array(
				'delivno' => $newdelivno,
				'isdeliv' => 'X'
			);

		foreach($items as $k => $v) {
			//TODO取得訂單id並且將印刷或刀模費設定出貨狀態
			//若傳回項目是刀模費或印刷費的話，就用訂單id將出貨狀態設定X

			if($v['did'] == 'prt') {
				$h = array(
					'isprtprdeliv' => 'X'
				);
				
				$this->db->where('id', $v['oid']);
				$this->db->update('order', $h);
			} else if($v['did'] == 'blade') {
				$h = array(
					'isbladeprdeliv' => 'X'
				);
				$this->db->where('id', $v['oid']);
				$this->db->update('order', $h);
			} else {
				$this->db->where('id', $v['did']);
				$this->db->update('orderdetail', $item);
			}
		}
		
		$todaydate = mdate("%Y%m%d");
		//$remark = $this->input->get_post('remark');
		$delivery = array('id' =>  $newdelivno,
					'date' => $todaydate
				);
		$this->db->insert('delivery', $delivery);

		echo 'OK';

	}

	public function ajax_remove_deliv() {
		
		
		$oid = $this->input->get_post('oid');
		$od = array(
				'isnotdeliv' => 'X'
			);

		$this->db->where('fk_order', $oid);
		$this->db->update('orderdetail', $od);
		
		echo 'OK';
	}

	public function getNextSN($testing = false) {
		$arr = $this->db->get('setting', 1)->result_array();
		$arr = $arr[0];
		$sno = $arr['lastdelivsn'];
		$sno += 1;
		$datestring = "%Y%m%d";
		$todaydate = mdate($datestring);
		$result = $todaydate . fillzero($sno, 4);

		if($testing == false) {
			$arr['lastdelivsn'] = $arr['lastdelivsn'] + 1;
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
