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
          <div class="well" style="display:nonse;">        
            <label for="txt_query">搜尋姓名
            </label>        
            <form id="qform" action="<?php echo site_url("/admin/$controller/search");?>" method="post">
            <input type="text" name="txt_query"  id="txt_query" class="search-query" value="<?php echo set_value('txt_query');?>" >        
            <a id="a_query"  class="btn" >
              <i class="icon-search"></i>查詢</a>    
          </div>                                       
          </form>
                  <table class="table table-striped" style="height:80%;">			
                    <thead>
                      <tr style="">				
                        <th style="width:100px;">              		
                        	<a href=<?php echo "/ct/admin/$controller/add" ?> class="btn">
                        		<i class="icon-plus-sign"></i>新增
                        	</a>                  
                        </th>				
                        <th style="width:100px;">                                    email
                        </th>					
                        <th style="width:100px;">                                    Name
                        </th>				
                        <th style="width:50px;">                                    Gender
                        </th>				
                        <th style="width:200px;">                                    Address
                        </th>				
                        <th style="width:100px;">                                    Status
                        </th>			
                      </tr>				             
         			</thead>
         			<tbody>
         						<?php foreach($data_list AS $row ):?>
	                      <tr >				
	                        <td>                   
	                                            <?php echo  anchor("/admin/$controller/edit/{$row['id']}","<i class='icon-edit icon-white'></i> 編輯",array('class'=>'btn btn-primary') );	?>     
                        						<?php echo  anchor("/admin/$controller/delete/{$row['id']}","<i class='icon-remove icon-white'></i>刪除",array('class'=>'btn btn-danger') ); ?>
	                        </td>				
	                        <td>                                   <?php echo $row['email']; ?>
	                        </td>					
	                        <td>                                    <?php echo  $row['name']; ?>
	                        </td>				
	                        <td>                                    <?php echo  get_gender_ch($row['gender'] ); ?>
	                        </td>				
	                        <td>                                    <?php echo  $row['address']; ?>
	                        </td>				
	                        <td>                                    <?php echo  get_member_status_ch($row['status']); ?>
	                        </td>			
	                      </tr>			
								<?php endforeach; ?>
         			</tbody>
          </table>   
          <?php echo $this->pagination->create_links();?>