<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setting_model extends MY_Model {
	var $model_name = "setting";
	
	function __construct() {
		parent::__construct(); 
		
	}
	
	public function reset() {
		$this->load->helper('date');

		$datestring = "%Y%m%d";
		$todaydate = mdate($datestring);
		
		//每次呼叫都檢查是否是新的一日，並將最後的工單及採購單流水號歸零	
		
		$arr = $this->get_sn();
	
		
		if($arr == null){
			$arr['lastposn'] = 0;
			$arr['lastppsn'] = 0;
			$arr['lastdelivsn'] = 0;
			$arr['lastbillsn'] = 0;
			$arr['lastordersn'] = 0;
			$arr['lastcheckdate'] = $todaydate;
			$this->db->empty_table('setting');
			$this->db->insert('setting', $arr);
		} else {
			
			$arr = $arr[0];
			$arr['lastcheckdate'] = str_replace('-' , '', $arr['lastcheckdate']);
			if($arr['lastcheckdate'] < $todaydate) {
				$arr['lastposn'] = 0;
				$arr['lastppsn'] = 0;
				$arr['lastdelivsn'] = 0;
				$arr['lastbillsn'] = 0;
				$arr['lastordersn'] = 0;
				$arr['lastcheckdate'] = $todaydate;
				$this->db->empty_table('setting');
				$this->db->insert('setting', $arr);
			}
		}

	}

	//取得目前設定
	//此table只會有一行
	public function get_sn() {
		return $this->db->get('setting',1)->result_array();		
	}

	//取得下一個ordersn()並將ordersn計數+1
	public function nextordersn() {
		$arrnow = $this->get_sn()[0];
		$newsn = $arrnow['lastordersn']+1;
		
		$data = array(
					'lastordersn'=>$newsn
					
		);
		
		
		$this->db->update($this->model_name,$data);

		return $newsn;
		
	}

	//傳回下一筆工單號碼．如：18030702  年年月月日日水號
	public function nextorderno() {
		$this->load->helper('date');
		$datestring = "%y%m%d"; //傳回yymmdd 如2018-03-07 -> 180307
		$todaydate = mdate($datestring);
		return $todaydate . fillZero($this->setting_model->nextordersn(), 2);

	}

}