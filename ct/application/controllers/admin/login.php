<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {
	private $font = 'monofont.ttf';
	function __construct() {
		parent::__construct();
		$this->load->helper(array('url','file','bookstore'));
		$this->load->library('session');
	/*	if($this->isLogin() == 1) {

			redirect('admin/main');
		} else {
			redirect('admin/login/index/您尚未登入。');
		}*/
		
	}

	private function isLogin() {
		return $this->session->userdata($this->config->item('sessionkey_is_login')) == "yes";
	}

	//感覺這類工具程式不該寫在父類別裡..應該有什麼專門的地方在放置這類程式的。
	public function get_today_date() {
		$this->load->helper('date');

		$datestring = "%Y-%m-%d";
		$time = time();

		return mdate($datestring, $time);
	}

	public function index($errormsg='')
	{
		$this->load->view('admin/login',array('errormsg'=>urldecode($errormsg) ) );
		//----自動清除暫存檔案---//
/*		$path = 'uploads/tmppic';
		$arr = get_dir_file_info($path);
		$today_date = $this->get_today_date();
		foreach($arr as $key =>$item) {
			if($key !== $today_date) {
				//刪除這個暫存資料夾
				//echo $key . " 刪 /$path/$key<br />";
				delete_files("$path/$key");
				@rmdir("$path/$key");
				
			} else {
				//不能刪除檔名為 今日日期 的資料夾
				//echo $key . ' 不刪<br />';
			}
		}
		*/

	}

	private function generateCode($characters) {
		/* list all possible characters, similar looking characters and vowels have been removed */
		$possible = '23456789bcdfghjkmnpqrstvwxyz';
		$code = '';
		$i = 0;
		while ($i < $characters) { 
			$code .= substr($possible, mt_rand(0, strlen($possible)-1), 1);
			$i++;
		}
		return $code;
	}

	public function captcha() {
		$width='120';
		$height='40';
		$characters='6';

		$code = $this->generateCode($characters);
		/* font size will be 75% of the image height */
		$font_size = $height * 0.75;
		$image = @imagecreate($width, $height) or die('Cannot initialize new GD image stream');
		/* set the colours */
		$background_color = imagecolorallocate($image, 255, 255, 255);
		$text_color = imagecolorallocate($image, 20, 40, 100);
		$noise_color = imagecolorallocate($image, 100, 120, 180);
		/* generate random dots in background */
		for( $i=0; $i<($width*$height)/3; $i++ ) {
			imagefilledellipse($image, mt_rand(0,$width), mt_rand(0,$height), 1, 1, $noise_color);
		}
		/* generate random lines in background */
		for( $i=0; $i<($width*$height)/150; $i++ ) {
			imageline($image, mt_rand(0,$width), mt_rand(0,$height), mt_rand(0,$width), mt_rand(0,$height), $noise_color);
		}
		/* create textbox and add text */
		$textbox = imagettfbbox($font_size, 0, $this->font, $code) or die('Error in imagettfbbox function');
		$x = ($width - $textbox[4])/2;
		$y = ($height - $textbox[5])/2;
		imagettftext($image, $font_size, 0, $x, $y, $text_color, $this->font , $code) or die('Error in imagettftext function');
		/* output captcha image to browser */
		header('Content-Type: image/jpeg');
		imagejpeg($image);
		imagedestroy($image);
		$this->session->set_userdata('security_code',$code);
	}

	public function process_login()
	{
		$un = $this->input->post("un");
		$pw = $this->input->post("pw");
		$sc = $this->input->post("sc");
		/*20151016 業主提供的空間在產生captcha時總會失敗，沒空研究那麼多。所以就先停用了…*/
		// if($sc !== $this->session->userdata('security_code')) {
		// 	redirect('admin/login/index/登入失敗-驗證碼錯誤。');
		// 	return;
		// }

		$sql = "SELECT id,username,password,nickname FROM admin WHERE username=? AND password=?";
		$rs = $this->db->query($sql,array($un,$pw));
		if($rs->num_rows() > 0) {
			$logindata = array(
				$this->config->item('sessionkey_is_login') => 'yes',
				'who'=> $rs->first_row()->nickname
			);

			$this->session->set_userdata($logindata);
			redirect('admin/main');
		} else {
			//TODO:redirect的參數可能以後會再調整
			redirect('admin/login/index/登入失敗-帳號或密碼錯誤。');
		}
		
	}
	
	public function logout() {
		$logoutdata = array( 
				$this->config->item('sessionkey_is_login')=>'',
				'who'=>''
		);
		$this->session->unset_userdata($logoutdata );
		redirect('admin/login/index/您已成功登出！');
	}
}
