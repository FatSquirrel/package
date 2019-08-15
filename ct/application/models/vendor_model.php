<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Vendor_model extends My_Model {
		var $model_name = "vendor";
	function __construct() {
		parent::__construct();
		
	}
	
	public function add() {
		$newid = $this->uuid->v4();

		$data = array(
					'id' => $newid,
					'name'=>$this->input->post('txt_name'),
					'sname'=>$this->input->post('txt_sname'),
					'companyno'=>$this->input->post('txt_companyno'),
					'address'=>$this->input->post('txt_address'),
					'tel'=>$this->input->post('txt_tel'),
					'fax'=>$this->input->post('txt_fax')
				);
		$this->db->insert($this->controller,$data);
		return $newid;
	}
	
	public function edit() {
		//TODO:需要加上驗證
		$data = array(
					'name'=>$this->input->post('txt_name'),
					'sname'=>$this->input->post('txt_sname'),
					'companyno'=>$this->input->post('txt_companyno'),
					'address'=>$this->input->post('txt_address'),
					'tel'=>$this->input->post('txt_tel'),
					'fax'=>$this->input->post('txt_fax')
		);
		
		$this->db->where('id',$this->input->post('hd_id',-1));
		$this->db->update($this->controller,$data);
		
	}

}