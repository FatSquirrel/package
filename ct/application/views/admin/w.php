<?php 
	//用來串在許多地方，免得常要重複輸入
	$controller = $this->uri->segment(2);
	
?>

                  <table class="table table-striped" style="height:80%;">			
                    <thead>
                      <tr style="">				
                        <th style="width:100px;"></th>				
                        <th style="width:100px;">產品名稱</th>							
                        <th style="width:100px;">更新日期</th>				
                      </tr>				             
         			</thead>
         			<tbody>
         						<?php foreach($data_list AS $row ):?>
	                      <tr >				
	                        <td><?php echo anchor("/admin/$controller/edit/{$row['id']}?s=6","<i class='icon-edit icon-white'></i> 編輯",array('class'=>'btn btn-primary') );	?>
	                        </td>				
	                        <td><?php echo $row['name']; ?></td>	
                          <td><?php echo $row['updatedate'] == '1970-01-01' ? '':$row['updatedate']  ?>
	                        </td>						
	                      </tr>			
								<?php endforeach; ?>
         			</tbody>
          </table>   
          <?php echo $this->pagination->create_links();?>