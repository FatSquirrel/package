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
    var msg = '<?php echo $msg?>';
    if(msg != '') {
      alert(msg);

    }
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
                                    
          </form>
                  <table class="table table-striped" style="height:80%;">			
                    <thead>
                      <tr style="">				
                        <th style="width:100px;">              		
                        	<a href=<?php echo "/ct/admin/$controller/add" ?> class="btn">
                        		<i class="icon-plus-sign"></i>新增
                        	</a>                  <span style="color:#ccc;font-size:13px;">*帳號愈少愈好，密碼愈嚴愈好。</span>
                        </th>				
                        <th style="width:100px;"> 帳號
                        </th>					
                        <th style="width:100px;"> 密碼
                        </th>				
                        <th style="width:50px;"> 名稱
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
	                        <td>    <?php echo $row['username']; ?>
	                        </td>		
	                        <td>    <?php echo $row['password']; ?>
	                        </td>		
	                        <td>    <?php echo $row['nickname']; ?>
	                        </td>			
	                      </tr>			
								<?php endforeach; ?>
         			</tbody>
          </table>   
          <?php echo $this->pagination->create_links();?>