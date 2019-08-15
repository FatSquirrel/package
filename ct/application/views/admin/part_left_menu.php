<div class="well" style="height:560px;">                        
            <ul class="nav nav-list">                            
<!--               <li class="nav-header" style="display:none;">會員管理
              </li>                            
              <li style="display:none;;">
              <a href="" style="text-decoration:line-through" >
                <i class="icon-user"></i>會員列表</a>
              </li> 
              <li class="nav-header">              <a href="/ct/admin/bill/pdf" target="_blank">
                <i class="icon-user"></i>測試</a>
              </li> 
              <li class="nav-header">              <a href="/ct/admin/main">
                <i class="icon-user"></i>首頁</a>
              </li>  -->

              <li class="nav-header" style="font-size:14px;">～處理作業～</li> 
                <li class="<?php echo $this->uri->segment(2) == 'order' ? 'active':''?>"><a href="/ct/admin/order">建立訂單</a><li>
                <li class="<?php echo $this->uri->segment(2) == 'processorder' ? 'active':''?>"><a href="/ct/admin/processorder">處理訂單</a></li> 
                <li class="<?php echo $this->uri->segment(2) == 'processpo_t' ? 'active':''?>"><a href="/ct/admin/processpo_t">面紙採購作業</a></li> 
                <li class="<?php echo $this->uri->segment(2) == 'processpo_cf' ? 'active':''?>"><a href="/ct/admin/processpo_cf">楞紙採購作業</a></li>
                <!-- <li style="text-decoration:line-through" class="<?php echo $this->uri->segment(2) == 'processother' ? 'active':''?>"><a href="/ct/admin/processother">待辦清單</a></li> 
              <li style="text-decoration:line-through" class="nav-header" style="font-size:14px;">～商品管理～</li> 
                <li style="text-decoration:line-through" class="<?php echo $this->uri->segment(2) == 'delivery' ? 'active':''?>"><a href="/ct/admin/delivery">出貨管理</a></li>   
                <li style="text-decoration:line-through" class="<?php echo $this->uri->segment(2) == 'delivery_back' ? 'active':''?>"><a href="/ct/admin/delivery_back">退貨管理</a></li>

              <li style="text-decoration:line-through" class="nav-header" style="font-size:14px;">～請款作業～</li>              
                <li style="text-decoration:line-through" class="<?php echo $this->uri->segment(2) == 'bill' ? 'active':''?>"><a href="/ct/admin/bill">請款作業</a></li>  -->

              <li class="nav-header" style="font-size:14px;">～設定作業～</li>              
                <li class="<?php echo $this->uri->segment(2) == 'product' ? 'active':''?>"><a href="/ct/admin/product">產品管理</a></li> 
                <li class="<?php echo $this->uri->segment(2) == 'vendor' ? 'active':''?>"><a href="/ct/admin/vendor">廠商設定</a></li> 
                <li class="<?php echo $this->uri->segment(2) == 'customer' ? 'active':''?>"><a href="/ct/admin/customer">客戶設定</a></li> 
              <li class="nav-header" style="font-size:14px;">～其它～</li>              
                <li class="<?php echo $this->uri->segment(2) == 'admin' ? 'active':''?>"><a href="/ct/admin/admin">管理員設定</a></li> 
                


    

            </ul>                    
          </div>                