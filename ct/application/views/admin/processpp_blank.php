<?php 
	$controller = $this->uri->segment(2);
?>
<!-- EXAMPLE OF CSS STYLE -->
<style>
   * {
   	padding:0;
   	margin:0;
    font-size:11px;
   }

  .div-edit-modal {
  	
  }



  .div-edit-litsec {
    border-bottom:1px solid #000;
  }

</style>

<?php //echo json_encode($arrod); ?>
 
      <table border="0" class="div-edit-litsec">
        <tr>
          <td colspan="3" style="text-align:center;font-size:14px;">紙器公司</td>
        </tr>
        <tr>
          <td style="width:130px;text-align:left;font-size:14px;">工單號碼：<?php echo $ppdata['orderno'] ?></td>
          <td style="width:167px;text-align:right;font-size:14px;">工作傳單</td>
          <td style="width:180px;text-align:right;font-size:10px;">日期：<?php echo $ppdata['donedate']; ?></td>
        </tr>
      </table>
      <table border="0" class="div-edit-litsec">
        <tr>
          <td style="width:320px;">&nbsp;產品名稱：<?php echo $ppdata['pname'] . '-' . $ppdata['pdname']  ?></td>
          <td style="width:219px;">完工數量：<?php echo '足' . $ppdata['qty2'] . '+耗損' ?></td>
        </tr>
      </table>
      <table class="div-edit-litsec">
          <tr><td style="width:70px;">&nbsp;面紙廠商：</td><td style="width:70px;"><?php echo $ppdata['tv_name'] ?></td><td style="width:60px;">面紙尺寸：</td><td style="width:120px;"><?php echo $ppdata['tos2'] . '(' . $ppdata['t'] . ')' ?></td><td style="width:60px;">面紙數量：</td><td style="width:158px;"><?php echo $ppdata['toq2'] ?></td></tr>
          <tr><td>&nbsp;印刷廠商：</td><td><?php echo $ppdata['prtv_name'] ?></td><td style="width:60px;">裁切尺寸：</td><td><?php echo $ppdata['tcs'] ?></td><td>印刷處理：</td><td><?php echo $ppdata['prt'] ?></td></tr>
      </table>

      <table class="div-edit-litsec">
          <tr><td style="width:70px;">&nbsp;上光廠商：</td><td style="width:70px;"><?php echo $ppdata['sfcv_name'] ?></td><td style="width:60px;">上光處理：</td><td style="width:338px;"><?php echo $ppdata['sfc'] ?></td></tr>
          <tr><td>&nbsp;燙金廠商：</td><td><?php echo $ppdata['heatv_name'] ?></td><td>燙金處理：</td><td><?php echo $ppdata['heat'] ?></td></tr>
      </table>
      <table class="div-edit-litsec">
          <tr>
            <td style="width:70px;">&nbsp;褙紙廠商：</td>
            <td style="width:70px;"><?php echo $ppdata['pstv_name'] ?></td>
            <td style="width:60px;">褙紙處理：</td>
            <td style="width:338px;" colspan="3"><?php echo $ppdata['pst'] ?></td>
            
          </tr>
          <tr>
            <td style="width:70px;">&nbsp;紙材廠商：</td>
            <td style="width:70px;"><?php echo $ppdata['cfv_name'] ?></td>
            <td style="width:60px;">紙材尺寸：</td>
            <td style="width:120px;"><?php echo $ppdata['cfs2'] ?></td>
            <td style="width:60px;">紙材數量：</td>
            <td style="width:158px;"><?php echo $ppdata['cfqty2'] ?></td>
          </tr>
          <tr>
            <td>&nbsp;軋盒廠商：</td>
            <td><?php echo $ppdata['gav_name'] ?></td>
            <td>模具規格：</td>
            <td colspan="3"><?php echo $ppdata['ga'] . '模(' . $ppdata['garemark'] . ')' ?></td>
          </tr>
      </table>
      <table class="div-edit-litsec">
      	<tr>
          <td style="width:70px;">&nbsp;糊盒廠商：</td><td style="width:70px;"><?php echo $ppdata['gluv_name'] ?></td><td style="width:60px;">糊盒方式：</td><td style="width:338px;"><?php echo $ppdata['glu'] ?></td>
        </tr>
      </table>
    <div>
      【備註】<br /><?php echo $ppdata['remark'] ?><br />
    </div>

