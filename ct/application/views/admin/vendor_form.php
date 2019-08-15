
<?php
		$controller = $this->uri->segment(2);

		$form_attr = array( 
											'id'=>'addform',
											'class'=>'form-horizontal'
								);
		
		if( $mode === 'EDIT' ) :   
			echo form_open_multipart("/admin/$controller/edit_save",$form_attr);
			echo form_fieldset("廠商編輯表單");
			
			echo form_hidden("hd_id",$editing_row['id']);
			?>
    	<div class="control-group" style="display:none;">
				<label class="control-label">廠商系統編號</label>
				<div class="controls">
					<?php echo form_input(array('value'=>$editing_row['id'], 'readonly'=>'readonly')); ?>
				</div>
		</div>
		<?php 

			else:
				echo form_open_multipart("/admin/$controller/add_save",$form_attr);
				echo form_fieldset("廠商編輯表單");
			endif;
			
			
			?>

    	<div class="control-group">
		<label class="control-label" for='txt_name'>廠商名稱</label>
		<div class="controls">			
		<input type='text' id='txt_name' name='txt_name'  value='<?php echo isset($editing_row['name']) ? $editing_row['name']:''; ?>'/>
		</div>
		</div>
    	<div class="control-group">
		<label class="control-label" for='txt_sname'>廠商簡稱</label>
		<div class="controls">
			<input type='text' id='txt_sname' name='txt_sname'  value='<?php echo isset($editing_row['sname']) ? $editing_row['sname']:''; ?>'/>
		</div>
		</div>
    	<div class="control-group">
		<label class="control-label" for='txt_companycode'>廠商統編</label>
		<div class="controls">			
		<input type='text' id='txt_companyno' name='txt_companyno'  value='<?php echo  isset($editing_row['companyno']) ? $editing_row['companyno']:''; ?>'/>
		</div>
		</div>
    	<div class="control-group">
		<label class="control-label" for='txt_address'>通訊地址</label>
		<div class="controls">			
		<input type='text' id='txt_address' name='txt_address'  value='<?php echo  isset($editing_row['address']) ? $editing_row['address']:''; ?>'/>
		</div>
		</div>
    	<div class="control-group">
		<label class="control-label" for='txt_tel'>電話號碼</label>
		<div class="controls">			
		<input type='text' id='txt_tel' name='txt_tel'  value='<?php echo  isset($editing_row['tel']) ? $editing_row['tel']:''; ?>'/>
		</div>
		</div>
    	<div class="control-group">
		<label class="control-label" for='txt_fax'>傳真號碼</label>
		<div class="controls">			
		<input type='text' id='txt_fax' name='txt_fax'  value='<?php echo  isset($editing_row['fax']) ? $editing_row['fax']:''; ?>'/>
		</div>
		</div>
		
<!--     	<div class="control-group">
		<label class="control-label" for='txt_payremark'>請款備註</label>
		<div class="controls">			
		<input type='text' id='txt_payremark' name='txt_payremark'  value='<?php echo  isset($editing_row['payremark']) ? $editing_row['payremark']:''; ?>'/>
		</div>
		</div> -->



	
		<div class="form-actions">
			<?php echo $mode==='EDIT' ? form_submit(array('class'=>'btn-primary'),'確定編輯') : form_submit(array('class'=>'btn-success'),'確定新增')?>
			<?php echo anchor("/admin/$controller/index","取消",array('class'=>'btn '))?>
		</div>
	</p>

<?php
	 echo form_fieldset_close();
	 echo form_close();  
?>


<script>
	$(document).ready(function(e) {
			$("#addform").validate({
				debug:false,
				rules:{
					"txt_name":"required",
					"txt_sname": "required"

				},
				messages:{
					"txt_name":"*請輸入[廠商名稱]",
					"txt_sname": "*請輸入[廠商簡稱]"
				}
			});
	}); //end document ready
</script>
