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

  function printThis(id) {
      window.open('/ct/admin/processpo_t/generate?id=' + id);
  }
</script>
<!--           <div class="well" style="display:nonse;">        
            <label for="txt_query">搜尋產品名稱
            </label>        
            <form id="qform" action="<?php echo site_url("/admin/$controller/search");?>" method="post">
            <input type="text" name="txt_query"  id="txt_query" class="search-query" value="<?php echo set_value('txt_query');?>" >        
            <a id="a_query"  class="btn" >
              <i class="icon-search"></i>查詢</a>    
          </div>    -->                                    
          </form>
                  <table class="table table-striped" style="height:80%;">			
                    <thead>
                      <tr style="">				
                        <th style="width:100px;">              		

                        </th>				
                        <th style="width:100px;">                                    採購單號
                        </th>					
                        <th style="width:100px;">                                    採購日期
                        </th>				
                        <th style="width:200px;">                                    備註
                        </th>       
                      </tr>				             
         			</thead>
         			<tbody>
         						<?php foreach($data_list AS $row ):?>
	                      <tr >				
	                        <td>                   
                            <a href="#" class="btn" onclick="printThis('<?php echo $row['id'] ?>')"><i class="icon-plus-sign"></i>匯出PDF</a> 
	                        </td>				
	                        <td>                                   <?php echo $row['id']; ?>
	                        </td>					
	                        <td>                                    <?php echo  $row['podate']; ?>
	                        </td>				
                          <td>                                    <?php echo  $row['remark']; ?>
                          </td>       
	                      </tr>			
								<?php endforeach; ?>
         			</tbody>
          </table>   
          <?php echo $this->pagination->create_links();?>