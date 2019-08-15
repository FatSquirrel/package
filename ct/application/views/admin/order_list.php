<?php 
	//用來串在許多地方，免得常要重複輸入
	$controller = $this->uri->segment(2);
	
?>
<script>
  $(document).ready( function(e) {
    var $txt = $("#txt_query"),
         $submit = $("#a_query"),
         $form = $("#qform"),
         $btnRemove = $('.btnRemove');


    $submit.on('click',function(e) {
      $txt.val($.trim($txt.val()) );
      $form.submit();
    });

    $btnRemove.on('click', function(e) {
      if(confirm('確定刪除嗎？')) {


      } else {
        e.preventDefault();
      }
    })//end remove Click;

  }); //end document ready
</script>
          <!--div class="well" style="display:nonse;">        
            <label for="txt_query">搜尋產品名稱
            </label>        
            <form id="qform" action="<?php echo site_url("/admin/$controller/search");?>" method="post">
            <input type="text" name="txt_query"  id="txt_query" class="search-query" value="<?php echo set_value('txt_query');?>" >        
            <a id="a_query"  class="btn" >
              <i class="icon-search"></i>查詢</a>    
          </div-->                                       
          </form>
          <a href="<?php echo site_url("/admin/$controller/add");?>" class="btn">
                <i class="icon-plus-sign"></i>新增
          </a> 
          <div>
              <table class="table table-striped" style="height:80%;">			
                    <thead>
                      <tr style="">				
                        <th style="width:100px;">              		
                                                                                    
                        </th>				
                        <th style="width:100px;">              		
                                    工單號碼                                                
                        </th>				
                        <th style="width:100px;">                                    產品名稱
                        </th>					
                        <th style="width:100px;">                                    品項欄位
                        </th>					
                        <th style="width:100px;">                                    所屬客戶
                        </th>	
                        <th style="width:100px;">                                    訂購數量
                        </th>   
                        <th style="width:100px;">                                    客戶預交日
                        </th>   

                      </tr>				             
         			</thead>
         			<tbody>
         						<?php foreach($data_list AS $row ):?>
	                      <tr >				
	                        <td>                   
                            <?php echo  anchor("/admin/$controller/delete/{$row['id']}","<i class='icon-remove icon-white'></i>刪除",array('class'=>'btn btn-danger btnRemove') ); ?>
                            
	                        </td>	
                          <td>                                   <?php echo $row['orderno']; ?>
	                        </td>					
	                        <td>                                   <?php echo $row['prodname']; ?>
	                        </td>					
                          <td>                                   <?php echo $row['itemname']; ?>
	                        </td>					
	                        <td>                                    <?php echo  $row['custsname']; ?>
	                        </td>				
                          <td>                                    <?php echo  $row['qty']; ?>
                          </td> 
                          <td>                                    <?php echo  $row['etd']; ?>
     
	                      </tr>			
								<?php endforeach; ?>
         			</tbody>
          </table>   
        </div>
