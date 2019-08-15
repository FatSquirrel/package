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
	<div style="text-align:center;font-size:20px;">已處理訂單列表</div>

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
		<td>訂單號碼</td> 
		<td>產品名稱</td> 
		<td>品項</td>   
		<td>所屬客戶</td> 
		<td>訂購數量</td> 
		<td>印刷廠商</td> 
		<td>印刷處理</td> 
		<td>上光廠商</td> 
		<td>上光處理</td> 
		<td>褙紙廠商</td> 
		<td>褙紙處理</td> 
		<td>軋盒廠商</td> 
		<td>模具規格</td> 
		<td>糊盒廠商</td> 
		<td>糊盒方式</td> 
	</tr>
	<?php foreach($data as $k => $v): ?>
		<tr>
			<td><?php if($v['orderno'] == '') echo '無工單號'; else echo $v['orderno']; ?></td>
			<td><?php echo $v['pname']; ?></td>
			<td><?php echo $v['name']; ?></td>
			<td><?php echo $v['cname']; ?></td>
			<td><?php echo $v['qty']; ?></td>
			<td><?php echo $v['prtvendor']; ?></td>
			<td><?php echo $v['prt']; ?></td>
			<td><?php echo $v['sfcvendor']; ?></td>
			<td><?php echo $v['sfc']; ?></td>
			<td><?php echo $v['pstvendor']; ?></td>
			<td><?php echo $v['pst']; ?></td>
			<td><?php echo $v['gavendor']; ?></td>
			<td><?php echo $v['ga']; ?></td>
			<td><?php echo $v['gluvendor']; ?></td>
			<td><?php echo $v['glu']; ?></td>
		</tr>
	<?php endforeach; ?>
	</table>
	
	</table>
