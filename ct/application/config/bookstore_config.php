<?php
$config['ap_root_path'] = realpath('.');
//封面圖片的檔名
$config['fn_cover'] = 'cover';
$config['fn_cover_default'] = 'cover_default';

$config['cover_width'] = 200;
$config['cover_height'] = 270;

$config['member_status'] = array(
				'1'=>'正常會員',
				'2'=>'停權'
				);
$config['sessionkey_is_login'] = 'islogin';
$config['sessionkey_frontend_is_login'] = 'islogin';
$config['frontend_login_url'] = '/member/login';

$config['product_type'] = array('熱水爐', '系統廚具', '其它');

//------以下為form validation RULES---------
$config['form_validation_rules'] = array(
		'member_edit_add' => array(
				array(
						'field' => 'txt_email',
						'label' => 'eMail/帳號',
						'rules' => 'trim|required|max_length[45]|valid_email|callback_email_unique_check|xss_clean'
				),
				array(
						'field' => 'txt_password',
						'label' => '密碼',
						'rules' => 'trim|required|max_length[20]|xss_clean'
				),
				array(
						'field' => 'txt_name',
						'label' => '姓名',
						'rules' => 'trim|required|max_length[10]|xss_clean'
				),
				array(
						'field' => 'rb_gender',
						'label' => '性別',
						'rules' => 'trim|required|max_length[1]|xss_clean'
				),
				array(
						'field' => 'txt_address',
						'label' => '地址',
						'rules' => 'trim|required|max_length[75]|xss_clean'
				)
		),
		'email' => array(
				array(
						'field' => 'emailaddress',
						'label' => 'EmailAddress',
						'rules' => 'required|valid_email'
				),
				array(
						'field' => 'name',
						'label' => 'Name',
						'rules' => 'required|alpha'
				),
				array(
						'field' => 'title',
						'label' => 'Title',
						'rules' => 'required'
				),
				array(
						'field' => 'message',
						'label' => 'MessageBody',
						'rules' => 'required'
				)
		)
);