<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Vendor extends MY_Controller {
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


		$query = $this->model->get_limited_rows($this->uri->segment(4,0),$per_page);
		$num_rows = $this->model->num_rows();

		$this->pager_config['total_rows'] = $num_rows;
		$this->pagination->initialize($this->pager_config);
		 
		$this->data['content'] = $this->load->view('admin/'.$this->controller.'_list',
															array(
																	'data_list'=> $query->result_array() 
															),TRUE );

		$this->_render();
	}
	
	public function search() {
		$per_page = $this->pager_config['per_page'];
		$q = $this->input->post('txt_query');
		$q = !empty($q) ? $q : urldecode($this->uri->segment(4) );  
		//表示使用者啥也沒輸入就按下按鈕
		if(empty($q)) {
				redirect(site_url("/admin/{$this->controller}/index"));
				die(); //不知是否可以防止後面程式繼續執行..
		}
		
		$this->pager_config['base_url'] .= $q;
		
		$num_rows = $this->model->get_like_name_all($q)->num_rows();


 		$query =  $this->model->get_like_name_limit($q , $this->uri->segment(5,0) , $per_page);
		$this->pager_config['uri_segment'] = 5;
		$this->pager_config['total_rows'] = $num_rows;

		$this->pagination->initialize($this->pager_config);
		$this->data['content'] = $this->load->view('admin/'.$this->controller.'_list',array(
																								'data_list'=>$query->result_array()
																							),TRUE );
		$this->_render();
	}
	
	public function add() {
		
		$options = array();

		$view_data = array(
					'mode' => 'ADD'
				);
				
		$this->data['content'] = $this->load->view('admin/'.$this->controller.'_form',$view_data,TRUE);
		$this->_render();	
		
	}
	
	public function add_save() {
		//先新增資料並傳回新的id，因為要把這id組在後面的上傳路徑中
		$newid = $this->model->add();
		

		//20140502-因為覺得ckeditor的圖片功能就很好了．雖然有誤砍的可能，但應該不礙事...所以把單張上傳功能拿掉
		/*
		$upload_config['overwrite'] = TRUE;
		$upload_config['allowed_types'] = "jpg";
		$upload_config['file_name'] = $this->config->item('fn_cover');
		$upload_config['upload_path'] = "uploads/{$this->controller}/$newid/";
		new_folder($upload_config['upload_path'] );
		$this->load->library('upload',$upload_config);

		if(!$this->upload->do_upload('fl_cover') && $_FILES['fl_cover']['name'] !==''  ) {
			die('上傳錯誤:' . $this->upload->display_errors());
		}
		else {
				//上傳成功
			if(  $_FILES['fl_cover']['name'] !==''   ) { //表示確實有選擇圖片
				$config['image_library'] = 'gd2';
				$config['source_image']	= $upload_config['upload_path']  . $this->config->item('fn_cover').'.jpg';
				$config['create_thumb'] = FALSE;
				$config['maintain_ratio'] = FALSE;
				$config['width']	 = 200;
				$config['height']	= 270;
				$this->load->library('image_lib',$config);
				if(!$this->image_lib->resize() ) {
					echo '縮圖失敗';
					die('縮圖失敗:' . $this->image_lib->display_errors());
				}
			}
		} 
		*/
		redirect("/admin/{$this->controller}/index");
	}
	
	public function edit() {
		$id = $this->uri->segment(4,-1);
	
	//20140502-因為覺得ckeditor的圖片功能就很好了．雖然有誤砍的可能，但應該不礙事...所以把單張上傳功能拿掉
 	// 	$foldpath = 'uploads/announcement/'.$id ;
		// $covername = $this->config->item('fn_cover');
 	// 	if( get_file_info($foldpath.'/'.$covername.'.jpg') ) {
		// 	$coverpath = 'http://'.$_SERVER['SERVER_NAME'] .'/'.$foldpath.'/'.$covername.'.jpg?'.time();
 	// 	}
 	// 	else {
		// 	$coverpath = 'http://'.$_SERVER['SERVER_NAME'] .'/img/'.$this->config->item('fn_cover_default').'.jpg?'.time();
 	// 	}
		
 		$editing_row = $this->model->get_row_by_id($id);

 		
		//TODO:檢查未輸入ID或找不到資料時

		$view_data = array(
				'mode' => 'EDIT',
				'editing_row' =>$editing_row ,
				'id'=>$id
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
		$this->db->update('vendor', $item);
		//$this->model->delete();
		redirect("/admin/{$this->controller}/index");
	}
	
	private function _render() {
		$this->load->view('admin/admin_main_template',$this->data);
	}
	
}
