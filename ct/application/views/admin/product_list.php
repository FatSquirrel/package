<?php 
	//用來串在許多地方，免得常要重複輸入
	$controller = $this->uri->segment(2);
	
?>
<script>



  $(document).ready( function(e) {
    var $txt = $("#txt_query"),
        $txtitem = $("#txt_queryitem"),
        $submit = $("#a_query"),
        $form = $("#qform"),
        $btnRemove = $('.btnRemove'),
        $c = $('#ddl_customer'),
        $exportxls = $('#btnExportxls');

    $exportxls.on('click', function(e) {
      $txt.val($.trim($txt.val()) );
      $txtitem.val($.trim($txtitem.val()) );
      var qs = '/ct/admin/product/excel?n=' + $txt.val() + '&c=' + $c.val() + '&qi=' + $txtitem.val();
      
      window.open(qs);
      e.preventDefault();
    });
    
    $c.change(function(e) {

      query();
    });


    $submit.on('click',function(e) {
      query();
      
      e.preventDefault();
      // $form.submit();
    });

    $btnRemove.on('click', function(e) {
      if(confirm('確定刪除嗎？')) {


      } else {
        e.preventDefault();
      }
    })//end remove Click;
    var query = function() {
           $txt.val($.trim($txt.val()) );
           $txtitem.val($.trim($txtitem.val()) );
           var qs = '/ct/admin/product/search?n=' + $txt.val() + '&c=' + $c.val() + '&qi=' + $txtitem.val();
            
           location.href = qs;
    };
  }); //end document ready
</script>
          <div class="well" style="display:nonse;">        
            <label for="txt_query">搜尋產品
            </label>        
            <form id="qform" action="<?php echo site_url("/admin/$controller/search");?>" method="post">
            客戶：<?php echo form_dropdown('ddl_customer', $customers, $selCust, 'id="ddl_customer"') ?>   <br />
            品名：<input type="text" name="txt_query"  id="txt_query" class="search-query" value="<?php echo $selN;?>" > <br /> 
            品項：<input type="text" name="txt_queryitem"  id="txt_queryitem" class="search-query" value="<?php echo $selQI;?>" >        
            <a id="a_query"  class="btn" >
              <i class="icon-search"></i>查詢</a> 
              <button id="btnExportxls" style="margin:0 0 10px 0;">EXCEL</button>
          </div>                                       
          </form>
                  <table class="table table-striped" style="height:80%;">			
                    <thead>
                      <tr style="">				
                        <th style="width:100px;">              		
                        	<a href="<?php echo site_url("/admin/$controller/add");?>" class="btn">
                        		<i class="icon-plus-sign"></i>新增
                        	</a>                  
                        </th>				
                        <th style="width:100px;">產品名稱</th>					
                        <th style="width:100px;">品項</th>					
                        <th style="width:100px;">所屬客戶</th>				
                        <th style="width:100px;">更新日期</th>				
                        
                      </tr>				             
         			</thead>
         			<tbody>
         						<?php foreach($data_list AS $row ):?>
	                      <tr >				
	                        <td><?php echo  anchor("/admin/$controller/edit/{$row['id']}","<i class='icon-edit icon-white'></i> 編輯",array('class'=>'btn btn-primary') );	?>     
                        	<?php echo  anchor("/admin/$controller/delete/{$row['id']}","<i class='icon-remove icon-white'></i>刪除",array('class'=>'btn btn-danger btnRemove') ); ?>
	                        </td>				
	                        <td><?php echo $row['prodname']; ?>
                          </td>	
                          <td><?php echo $row['itemname']; ?>
	                        </td>							
	                        <td><?php echo  $row['custsname']; ?>
                          </td>	
                          <td><?php echo $row['updatedate'] == '1970-01-01' ? '':$row['updatedate']  ?>
	                        </td>						
	                      </tr>			
								<?php endforeach; ?>
         			</tbody>
          </table>   
          <?php echo $this->pagination->create_links();?>