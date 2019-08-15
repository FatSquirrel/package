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
	<div style="text-align:center;font-size:20px;">產品列表</div>

	<table border="" style="border-bottom:1px solid #000;">
		<tr>
			<td style="">客戶：<?php echo $cname ?></td>
		</tr>
		<tr>
			<td style="">列表時間：<?php echo $gentime; ?> </td>
		</tr>
	</table>
	
	<table border="" style="border-bottom:1px solid #000;">
	<tr>
		<td>產品名稱</td> 
		<td>品項</td> 
		<td>面紙訂購尺寸</td>   
		<td>面紙</td> 
		<td>K數</td> 
		<td>面紙裁切尺寸</td> 
		<td>紙材尺寸</td> 
		<td>紙材</td> 
		<td>印刷</td> 
		<td>上光</td> 
		<td>售價(元)</td> 
	</tr>
	<?php foreach($data as $k => $v): ?>
		<tr>
			<td><?php echo $v['pname']; ?></td>
			<td><?php echo $v['itemname']; ?></td>
			<td><?php echo $v['tos']; ?></td>
			<td><?php echo $v['t']; ?></td>
			<td><?php echo $v['knum']; ?></td>
			<td><?php echo $v['tcs']; ?></td>
			<td><?php echo $v['cfs']; ?></td>
			<td><?php echo $v['cf']; ?></td>
			<td><?php echo $v['prt']; ?></td>
			<td><?php echo $v['sfc']; ?></td>
			<td><?php echo $v['price']; ?></td>
		</tr>
	<?php endforeach; ?>
	</table>
	
	</table>
