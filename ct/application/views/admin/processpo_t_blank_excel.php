<?php 
	$controller = $this->uri->segment(2);
?>


<!-- EXAMPLE OF CSS STYLE -->
<style>
	* {
		font-size:12px;
		
	}


	td.border-bottom {
		border-bottom: 1px solid #000;

	}

	th {
		border-top: 1px solid #000;
		/*border-bottom: 1px solid #000;*/
	}

	.num {
		text-align:right;
	}
	.text {
		text-align:left;
	}
</style>

<?php //echo json_encode($arrod); ?>

	<div style="text-align:center;font-size:25px;">紙器公司</div>
	<div style="text-align:center;font-size:20px;">面紙訂單列表</div>

	<table border="" style="border-bottom:1px solid #000;">
		<tr>
			<td style="">訂單時間：<?php echo $b ?> ~ <?php echo $e ?></td>
		</tr>
		<tr>
			<td style="">列表時間：<?php echo $gentime; ?> </td>
		</tr>
	</table>
	
	<table border="" style="border-bottom:1px solid #000;">
	<tr>
		<td>工單號碼</td> 
		<td>供應廠商</td> 
		<td>產品名稱</td>   
		<td>面紙紙材</td> 
		<td>面紙尺吋</td> 
		<td>面紙數量</td> 
		<td>所屬客戶</td> 
	</tr>
	<?php foreach($data as $k => $v): ?>
		<tr>
			<td><?php if($v['orderno'] == '') echo '無工單號'; else echo $v['orderno']; ?></td>
			<td><?php echo $v['tvendor']; ?></td>
			<td><?php echo $v['pname']; ?></td>
			<td><?php echo $v['t']; ?></td>
			<td><?php echo $v['tos2']; ?></td>
			<td><?php echo $v['toq2']; ?></td>
			<td><?php echo $v['cname']; ?></td>
		</tr>
	<?php endforeach; ?>
	</table>
	
	</table>
