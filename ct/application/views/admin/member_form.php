
<?php

		$controller = $this->uri->segment(2);
		$form_attr = array( 
											'id'=>'addform',
											'class'=>'form-horizontal'
								);
		
		echo form_fieldset("會員表單");
		if( $mode === 'EDIT' ) :   
			echo form_open("/ct/admin/$controller/edit_save",$form_attr);
			
			echo form_hidden("hd_id",$id);
		?>
    	<div class="control-group">
				<label class="control-label">會員編號</label>
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
		<label class="control-label" for='txt_email'>email</label>
		<div class="controls">			
			<input type='text' id='txt_email' name='txt_email'  value='<?php echo isset($editing_row['email']) ? $editing_row['email']:set_value('txt_email'); ?>'/>
			<?php echo form_error('txt_email', '<div class="error">', '</div>'); ?>
		</div>
		</div>
    	<div class="control-group">
		<label class="control-label" for='txt_password'>密碼</label>
		<div class="controls">			
			<input type='password' id='txt_password' name='txt_password'  value='<?php echo isset($editing_row['password']) ? $editing_row['password']:set_value('txt_password'); ?>'/>
			<?php echo form_error('txt_password', '<div class="error">', '</div>'); ?>
		</div>
		</div>
    	<div class="control-group">
		<label class="control-label" for='txt_name'>姓名</label>
		<div class="controls">			
		<input type='text' id='txt_name' name='txt_name'  value='<?php echo isset($editing_row['name']) ? $editing_row['name']:set_value('txt_name'); ?>'/>
			<?php echo form_error('txt_name', '<div class="error">', '</div>'); ?>
		</div>
		</div>
    	<div class="control-group">
		<label class="control-label" for='rb_gender'>性別</label>
		<div class="controls">
		<?php 
			echo form_label('男 '.form_radio('rb_gender','M', isset( $editing_row['gender'] ) ? $editing_row['gender']==='M' : set_value('rb_gender')==='M' ) );
			echo form_label('女 '.form_radio('rb_gender','F',isset( $editing_row['gender'] ) ? $editing_row['gender']==='F'  : set_value('rb_gender')==='F' ) );
		
			echo form_error('rb_gender', '<div class="error">', '</div>'); 
		?>		
		</div>
		</div>
    	<div class="control-group">
		<label class="control-label" for='txt_address'>地址</label>
		<div class="controls">
		<input type='text' id='txt_address' name='txt_address'  value='<?php echo  isset($editing_row['address']) ? $editing_row['address']:set_value('txt_address');?>'/>
		<?php echo form_error('txt_address', '<div class="error">', '</div>'); ?>
		</div>
		</div>
    	<div class="control-group">
		<label class="control-label" for='ddl_status'>會員狀態</label>
		<div class="controls">
		<?php 
		$options = $status_options;
		if($mode === 'EDIT') {
			if(isset($editing_row['status']) && $editing_row['status'] != '0') {
				echo form_dropdown('ddl_status',$options,$editing_row['status']);
			}
			else {
				echo '<span style="color:#f00;">此位會員尚未開通帳號</span>';
			}
		}
		else {
			echo form_dropdown('ddl_status',$options);
		}
		?>
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
			
			$("#addform").validate({
				debug:false,
				rules:{
					"txt_email": {
						"required":true,
						"email":true,
						"remote": {
						        url: "/ct/admin/<?php echo $controller?>/ajax_email_unique_check",
						        type: "post",
						        data: {
						          id: function() {
						            return $("#txt_id").val();
						          }
						        }
						}
					},
					"txt_password":"required",
					"txt_name":"required",
					"rb_gender":"required",
					"txt_address":"required"
				},
				messages:{
					"txt_email": {
						"required":"*請輸入eMail",
						"email":"*您的eMail格式不正確",
						"remote":"*您的eMail已經在使用了。"
					},
					"txt_password":"*請輸入密碼",
					"txt_name":"*請輸入性名",
					"rb_gender":"*請選擇性別",
					"txt_address":"*請輸入地址"
				}
			});
	}); //end document ready
</script>
