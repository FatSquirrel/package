<!doctype html >
<html>
  <head>    
    <meta charset="utf-8" />    
    <!--[if IE]>
        <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->    
    <title>後台
    </title>    
    <link rel="stylesheet" href="/css/bootstrap.2.3.2.css" />    
    <link rel="stylesheet" href="/css/admin_styles.css" />
    <style>        article, aside, figure, footer, header, hgroup,          menu, nav, section { display: block; }              
    </style>

    <script src="/js/libs/jquery.js"></script>
    <script src="/js/script.js"></script>
    
	<script src="/js/libs/jquery.validate.js" ></script>
  </head>
  <body>        
    <div class="navbar">          
      <div class="navbar-inner">            
        <div class="container-fluid">              
          <a class="brand" href="#">紙器公司</a>              
          <ul class="nav">                
       
          </ul>              
			<p class="navbar-text pull-right">
				登入者 <span style='font-weight:bold;'><?php echo $this->session->userdata('who') ?></span> 
				|
				<a href="/ct/admin/login/logout">登出</a>
			</p>       
        </div>          
      </div>        
    </div>        
    <div class="container-fluid">            
      <div class="row-fluid">                
        <div class="span2">                    
          <!-- 左選單 -->
			<?php $this->load->view('admin/part_left_menu') ?>
          <!-- /左選單 -->
        </div>
        <div class="span10">
                  <!-- 主內容 -->
					<?php echo $content; ?>
          		  <!-- /主內容 -->              
          </div>                     
        </div>            
      </div>        
  </body>
</html>