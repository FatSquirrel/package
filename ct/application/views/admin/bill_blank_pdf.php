<?php 
	$controller = $this->uri->segment(2);
?>


<!-- EXAMPLE OF CSS STYLE -->
<style>
	* {
		font-size:12px;
		
	}

td {

}
	td.border-bottom {
		border-bottom: 1px solid #000;

	}

	th {
		border-top: 1px solid #000;
		line-height:10px;
		/*border-bottom: 1px solid #000;*/
	}
</style>

<?php //echo json_encode($arrod); ?>

<div style="">
	<div style="text-align:center;font-size:25px;">紙器公司</div>
	<div style="text-align:center;font-size:20px;"><?php echo $mon ?>月請款單</div>
	<table border="" style="width:550px;">
		<tr>
			<td style="width:170px;">客戶名稱：<?php echo $cname ?></td><td style="width:130px;">統一編號：<?php echo $companycode ?></td><td style="width:230px;">請款期間：<?php echo $b ?> ~ <?php echo $e ?></td>
		</tr>
	</table>

	<table border="0" style="width:530px;">
		<tr>
			<th style="width:80px;">&nbsp;出貨日期&nbsp;</th><th style="width:280px;">&nbsp;品名&nbsp;</th><th style="width:50px;">&nbsp;單價&nbsp;</th><th style="width:50px;">&nbsp;數量&nbsp;</th><th style="width:70px;">&nbsp;金額&nbsp;</th>
		</tr>
		<?php $lastoid = ''; ?>
		<?php foreach($arrod as $k => $v): ?>
		<?php
		if($v['oid'] != $lastoid) {
			//每張訂單開始的那列加上上框線表示訂單間的分隔線。所以上面<th>的下底線就拿掉。
			echo '<tr><td colspan="5" style="line-height:10px;border-bottom:1px solid #000;">&nbsp;</td></tr>';
		}
		?>
			<tr>
				<td>&nbsp;<?php if($v['oid'] != $lastoid) echo $v['delivdate'] ?>&nbsp;</td><td>&nbsp;<?php echo $v['pname'].'-'.$v['pdname'] ?>&nbsp;</td><td style="text-align:left;">&nbsp;<?php echo $v['price'] ?>&nbsp;</td><td>&nbsp;<?php echo $v['qty'] ?>&nbsp;</td><td>&nbsp;<?php echo $v['subtotal'] ?>&nbsp;</td>
			</tr>

		<?php 
			if(((isset($arrod[$k + 1]) && $arrod[$k + 1]['oid'] != $v['oid']) && ($v['prtpr_price'] != '' && $v['prtpr_price'] != 0) || (sizeof($arrod) - 1 == $k)) && ($v['prtpr_price'] != '' && $v['prtpr_price'] != 0)) {
		?>
			<tr>
				<td></td><td>&nbsp;印刷版費&nbsp;</td><td>&nbsp;<?php echo $v['prtpr_price'] ?>&nbsp;</td><td>&nbsp;<?php echo $v['prtpr'] ?>&nbsp;</td><td>&nbsp;<?php echo $v['prtpr'] * $v['prtpr_price'] ?>&nbsp;</td>
			</tr>
		<?php 
			}
			if(((isset($arrod[$k + 1]) && $arrod[$k + 1]['oid'] != $v['oid']) && ($v['bladepr_price'] != '' && $v['bladepr_price'] != 0) || (sizeof($arrod) - 1 == $k)) && ($v['bladepr_price'] != '' && $v['bladepr_price'] != 0)) {
		?>
			<tr>
				<td></td><td>&nbsp;刀模費&nbsp;</td><td>&nbsp;<?php echo $v['bladepr_price'] ?>&nbsp;</td><td>&nbsp;<?php echo $v['bladepr'] ?>&nbsp;</td><td>&nbsp;<?php echo $v['bladepr'] * $v['bladepr_price'] ?>&nbsp;</td>
			</tr>

		<?php 
			}


			$lastoid = $v['oid']; 
		?>
		<?php endforeach; ?>
		<tr>
			<td colspan="5" style="border-top:1px solid #000;border-bottom:1px solid #000;">
				<table>
					<tr>
						<td width="80px">合計金額：</td><td><?php echo $sum; ?></td>
						<?php if($tax != '0') { ?>	
						<td width="50px">稅率：</td><td><?php echo $tax ?>%</td>
						<td width="50px">稅額：</td><td><?php echo ceil($aftertax) ?></td>
						<?php } ?>	
						<td width="70px">總金額：</td><td><?php echo ceil($total) ?></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="5" style="border-top:1px solid #000;border-bottom:1px solid #000;">
				備註事項：<?php echo $remark ?>
			</td>
		</tr>
	</table>
	
</div>