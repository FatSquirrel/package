
<?php

		$controller = $this->uri->segment(2);
		$form_attr = array( 
											'id'=>'addform',
											'class'=>'form-horizontal'
								);
		
		echo form_fieldset("管理員表單");
		if( $mode === 'EDIT' ) :   
			echo form_open("/ct/admin/$controller/edit_save",$form_attr);
			
			echo form_hidden("hd_id",$id);
		?>
    	<div class="control-group">
				<label class="control-label">管理員編號</label>
				<div class="controls">
					<?php echo form_input(array('id'=>'txt_id','value'=>$id, 'readonly'=>'readonly')); ?>
				</div>
				
		</div>
		<?php 
			else:
				echo form_open("/ct/admin/$controller/add_save",$form_attr);
			
			endif;
			
			?>
    	<div class="control-group">
		<label class="control-label" for='txt_username'>帳號</label>
		<div class="controls">			
			<input type='text' id='txt_username' name='txt_username'  value='<?php echo isset($editing_row['username']) ? $editing_row['username']:set_value('txt_username'); ?>'/>
			<?php echo form_error('txt_username', '<div class="error">', '</div>'); ?>
		</div>
		</div>
    	<div class="control-group">
		<label class="control-label" for='txt_password'>密碼</label>
		<div class="controls">			
			<input type='text' id='txt_password' name='txt_password'  value='<?php echo isset($editing_row['password']) ? $editing_row['password']:set_value('txt_password'); ?>'/>
			<?php echo form_error('txt_password', '<div class="error">', '</div>'); ?>
		</div>
		</div>
    	<div class="control-group">
		<label class="control-label" for='txt_name'>名稱</label>
		<div class="controls">			
		<input type='text' id='txt_nicknaem' name='txt_nickname'  value='<?php echo isset($editing_row['nickname']) ? $editing_row['nickname']:set_value('txt_nickname'); ?>'/>
			<?php echo form_error('txt_name', '<div class="error">', '</div>'); ?>
		</div>
		</div>

    	
		<div class="form-actions">
			<?php echo $mode==='EDIT' ? form_submit(array('class'=>'btn-primary'),'確定編輯') : form_submit(array('class'=>'btn-success'),'確定新增')?>
			<?php echo anchor("/admin/$controller/index","取消",array('class'=>'btn '))?>
		</div>
<?php
	 echo form_fieldset_close();
	 echo form_close();  
?>
<script>
	$(document).ready(function(e) {
			$txt_username = $('#txt_username');
			$txt_username.val($.trim($txt_username.val()));
			$("#addform").validate({
				debug:false,
				rules:{
	
					"txt_username": {
						"required":true,
						"remote": {
						        url: "/ct/admin/<?php echo $controller?>/ajax_username_unique_check",
						        type: "post",
						        data: {
						          id: function() {
						            return $("#txt_id").val();
						          }
						        }
						}
					},
					"txt_password":"required",
					"txt_nickname":"required"
				},
				messages:{
					"txt_username": {
						"required":"*請輸入帳號",
						"remote":"*這個帳號已經存在。"
					},
					"txt_password":"*請輸入密碼",
					"txt_nickname":"*請輸入名稱"
				}
			});
	}); //end document ready
</script>
