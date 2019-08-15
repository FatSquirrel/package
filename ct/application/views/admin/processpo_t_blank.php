<?php 
	$controller = $this->uri->segment(2);
?>


<!-- EXAMPLE OF CSS STYLE -->
<style>
	* {
		padding:0; margin:0;
		font-size:11px;
	}

	.div-edit-litsec {
    	border-bottom:1px solid #000;
  	}
  	div.test {
		color: #CC0000;
		background-color: #FFFF66;
		font-family: helvetica;
		font-size: 10pt;
		border-style: solid solid solid solid;
		border-width: 2px 2px 2px 2px;
		border-color: green #FF00FF blue red;
		text-align: center;
	}


</style>

<?php //echo json_encode($arrod); ?>

  <table border="0" class="div-edit-litsec">
    <tr>
      <td colspan="2" style="text-align:center;font-size:14px;">紙器公司</td>
    </tr>
    <tr>
      <td style="width:305px;text-align:right;font-size:14px;">面紙採購單</td>
      <td style="width:200px;text-align:right;font-size:10px;">日期：<?php echo $arrod[0]['podate']; ?></td>
    </tr>
  </table>
  <table border="0" class="div-edit-litsec">
    <tr><td width="100">&nbsp;工單號碼</td><td width="105">&nbsp;紙材</td><td width="115">&nbsp;尺寸</td><td width="115">&nbsp;數量</td><td width="104">&nbsp;指送</td></tr>	
  </table>
<?php foreach($arrod as $v): ?>
	  <table border="0" class="div-edit-litsec">
    	<tr>
			<td width="100">
				&nbsp;<?php echo $v['orderno'] ?>
			</td>
			<td width="105">
				&nbsp;<?php echo $v['t'] ?>
			</td>
			<td width="115">
				&nbsp;<?php echo $v['tos2'] ?>
			</td>
			<td width="115">
				&nbsp;<?php echo $v['toq2'] ?>
			</td>
			<td width="104">
				&nbsp;<?php echo $v['t_nextvendor_cn'] ?>
			</td>
		</tr>
	  </table>
<?php endforeach ?>
	  <table border="0" class="div-edit-litsec">
    	<tr>
			<td width="539">
				&nbsp;
			</td>
		</tr>
	  </table>
	  <table border="0">
	    <tr>
			<td>&nbsp;【備註】：<?php echo $v['remark'] ?></td>
		</tr>
	  </table>
