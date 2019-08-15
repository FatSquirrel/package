<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="robots" content="noindex,nofollow" />
    <title>後台
    </title>
    <!--[if IE]>
      <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <style type="text/css">article,aside,figure,footer,header,hgroup,menu,nav,section { 	display: block; }.login-form { 	width: 500px; 	position: absolute; 	top: 25%; 	left: 25%; } 
    </style>
    <link rel="stylesheet" href="/css/bootstrap.2.3.2.css" />
  </head>
  <body>	

    <div class="login-form">		
      <form method="post" action="<?php echo site_url('admin/login/process_login'); ?>" id="loginform" class="form-horizontal">			
        <fieldset>				
          <legend>管理員登入
          </legend>
<?php if(!empty($errormsg)):?>
<div class="alert alert-error">
  <a class="close" data-dismiss="alert">×</a>
  <h4 class="alert-heading">歐哦!</h4>
		<?php echo $errormsg?>
</div>
<?php endif;?>				
          <div class="control-group">					
            <label class="control-label" for="un">帳號
            </label>					
            <div class="controls">						
              <input name="un" type="text" id="un" />					
            </div>				
          </div>				
          <div class="control-group">					
            <label class="control-label" for="pw">密碼
            </label>					
            <div class="controls">	
              <input type="password" id="pw" name="pw" />	
            </div>				
          </div>			
          	
<!--           <div class="control-group">         
            <label class="control-label" for="sc">驗證碼
            </label>  
            <div class="controls">  
              <input type="text" id="sc" name="sc" />
                
              <img src="/ct/admin/login/captcha?width=100&height=40&characters=5" name="imgCaptcha" id="imgCaptcha" />
              <a href="javascript:void(0)" onclick="RefreshImage('imgCaptcha')" style="font-size:9pt">更換圖片</a><br />
            </div>
          </div>     -->
          <div class="control-group">         
            <span id="lbMsg" class="error">
            </span>       
          </div>      
          <div class="form-actions">

            <input type="submit" name="btnLogin" value="登入" id="btnLogin" class="btn-primary" />          
            <input type="reset" class="btn" value="取消" />       
          </div>  
        </fieldset>		
      </form>	
    </div>	
<script src="/js/libs/jquery.js"  type="text/javascript"></script>
<script src="/js/libs/jquery.validate.js" type="text/javascript"></script>
<script src="/js/libs/bootstrap_alert.js" type="text/javascript"></script>
<script>
	$(document).ready(function(e) {
		$("#loginform").validate({
			debug:false,
			rules:{
				un:{
					required:true
				},
				pw:{
					required:true
				}	
			},
			messages:{ 
				un:{
					required:"*請輸入登入帳號。"
				},
				pw:{
					required:"*請輸入密碼。"	
				}	
			}
		
		});
	});

  //更換驗證碼圖片
  function RefreshImage(valImageId) {
    var objImage = document.images[valImageId];
    if (objImage == undefined) {
      return;
    }
    objImage.src = objImage.src.split('?')[0] + '?width=100&height=40&characters=5&s=' + new Date().getTime();
  }
</script>
  </body>
</html>