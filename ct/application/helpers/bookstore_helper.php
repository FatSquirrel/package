<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('insertArrayIndex'))
{
function insertArrayIndex($array, $new_element, $index) {
 /*** get the start of the array ***/
 $start = array_slice($array, 0, $index); 
 /*** get the end of the array ***/
 $end = array_slice($array, $index);
 /*** add the new element to the array ***/
 $start[] = $new_element;
 /*** glue them back together and return ***/
 return array_merge($start, $end);
 }
}

/**
 * get_specified_fieldname_index
 *
 * 取得陣列中field為指定值的index
 *
 * @access	public
 * @param	string 路徑
 * @return	string
 */
if ( ! function_exists('get_specified_fieldname_index'))
{
	function get_specified_fieldname_index($config_arr,	$fieldname)
	{
		//echo print_r( $config_arr );
		foreach($config_arr as $k=>$v) {
			if($v['field'] === $fieldname ) {
				return $k;
			}
		}		
	}
}


/**
 * new_folder
 *
 * 建立指定路徑中的所有資料夾
 *
 * @access	public
 * @param	string 路徑
 * @return	string
 */
if ( ! function_exists('new_folder'))
{
	function new_folder($path='')
	{
		//$tmppath = "../uploads/1/2/.";
		if($path !== '') {
			$arrpath = explode('/',$path);
			$appendedpath = "";
			foreach($arrpath as $v) {
				$appendedpath .= $v.'/';
				if($v !=='..' && $v !== '.') {
// 				echo $appendedpath." <br />";
					if(!is_dir($appendedpath)) {
// 				echo $appendedpath." v<br />";
						mkdir($appendedpath);
					}
				}
			}//end foreach
		}else { die('請載入建立路徑！');		}
		return;
		
	}
}

function fillzero ($num, $zerofill = 5)
{
	return str_pad($num, $zerofill, '0', STR_PAD_LEFT);
}

function getTodayStr() {
	return date("Y/m/d", time());
}

// ----------------以下為此bookstore專案專用的functions-----------------------------------------


/**
 * get_fee
 *
 * 取得運費，
 *
 * @access	public
 * @param	string 路徑
 * @return	string
 */
if ( ! function_exists('get_fee'))
{
	function get_fee()
	{
		$CI =& get_instance();
		$total = $CI->cart->total();
		$fee = 0;
		//未滿千加運費50
		if($total < 1000) {
			$fee = 50;
		} 

		return $fee;
	}
}



/**
 * get_pickeup_ch
 *
 * 將訂單中取貨方式從編號轉成中文
 *
 * @access	public
 * @param	string 路徑
 * @return	string
 */
if ( ! function_exists('get_pickeup_ch'))
{
	function get_pickeup_ch($code)
	{
		$res = "";
		switch($code) {
			case 1:
				$res = "寄送到家";
				break;
			case 2:
				$res = "超商取貨";
				break;
			default:
		}

		return $res;
	}
}

/**
 * get_order_status_ch
 *
 * 將訂單狀態從編號轉成中文
 *
 * @access	public
 * @param	string 路徑
 * @return	string
 */
if ( ! function_exists('get_order_status_ch'))
{
	function get_order_status_ch($code)
	{
		$res = "";
		switch($code) {
			case 1:
				$res = "未處理";
				break;
			case 2:
				$res = "處理中";
				break;
			case 3:
				$res = "訂單完成";
				break;
			case 4:
				$res = "取消訂單";
				break;
			default:
		}
		
		return $res;
	}
}

/**
 * get_member_status_ch
 *
 * 將會員的狀態由代碼轉換成中文
 *
 * @access	public
 * @param	string
 * @return	string
 */
if ( ! function_exists('get_member_status_ch'))
{
	function get_member_status_ch($code = 0)
	{
		$res = "";
		switch($code) {
			case 0:
				$res = "未開通";
				break;
			case 1:
				$res = "正常";
				break;
			case 2:
				$res = "停權中";
				break;
			default:
				//TODO: 要再修 die("bookstore_helper.php/get_member_status_ch");
		}
		
		return $res;
	}
}

/**
 * get_gender_ch
 *
 * 將性別由代碼轉換成中文
 *
 * @access	public
 * @param	string
 * @return	string
 */
if ( ! function_exists('get_gender_ch'))
{
	function get_gender_ch($code = 0)
	{
		$res = "";
		switch($code) {
			case 'F':
				$res = "女";
				break;
			case 'M':
				$res = "男";
				break;
			default:
				//TODO: 要再修 die("bookstore_helper.php/get_gender_ch");
		}

		return $res;
	}
}
// ------------------------------------------------------------------------

/* End of file bookstore_helper.php */
/* Location: ./application/helpers/bookstore_helper.php */