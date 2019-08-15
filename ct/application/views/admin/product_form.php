<?php 
    $controller = $this->uri->segment(2);
?>
<style type="text/css">
    input[type=text] , select {
        width:100px;
    }
  .hide {
    display:none;
  }

  .show {
    display:block;
  }

  #txt_name {
      width:150px;
  }

</style>
<div ng-app="itemApp">
      <div ng-controller="itemListController as itemList">
<?php if($s == '6') echo anchor("/admin/$controller/w","<i class='icon-edit icon-white'></i> ←退回待修正清單",array('class'=>'btn btn-primary') );; ?>
<?php


		$form_attr = array( 
						'id'=>'addform',
						'class'=>'form-horizontal'
						//'ng-submit'=>'itemList.formSubmit()'
			);
		
		if( $mode === 'EDIT' ) :   
			echo form_open_multipart("/admin/$controller/edit_save",$form_attr);
			echo form_fieldset("產品編輯表單");
			
			echo form_hidden("hd_id",$editing_row['id']);

			?>

    	<div class="control-group" style="display:none;">
				<label class="control-label">產品系統編號</label>
				<div class="controls">
					<?php echo form_input(array('value'=>$editing_row['id'], 'readonly'=>'readonly')); ?>
				</div>
		</div>
		<?php 

			else:
				echo form_open_multipart("/admin/$controller/add_save",$form_attr);
				echo form_fieldset("產品編輯表單");
			endif;
		?>
        
    	<div class="pull-left">
		<label class="control-label" for='txt_name'>產品名稱</label>
		<div class="controls">			
		<input type='text' id='txt_name' name='txt_name' ng-model="pname" />
		</div>
		</div>
        <div class="pull-left">
    	<div class="control-group">
		<label class="control-label" for='ddl_customer'>所屬客戶</label>
		<div class="controls">
            <select name="ddl_customer" ng-options="c.id as c.sname for c in customers" ng-model="fk_customer" >
                <option value="">~ 請選擇 ~</option>
            </select>
		</div>
		</div>
        </div>
        <div class="pull-left">
        <div class="control-group">
		<label class="control-label" for='txt_price'>產品售價</label>
		<div class="controls">
        <input type='text' id='txt_price' name='txt_price' ng-model="price"/>
		</div>
		</div>
		</div>
<!--產品項目 表格-->		
<div style="clear:both;">.</div>
  <h3>品項</h3>



<!--         <button ng-click="showAll()" >"SHOWALL"</button>
        <button ng-click="showAll2()" >"SHOWALL2"</button> -->
    <input class="btn-primary" type="button" value="新增" ng-click="itemList.addItem()" />
<div ng-repeat="item in items" style="margin-bottom:15px;">
        <table border="1" style="border-collapse:collapse;border:2px solid #ddd;">
            <tr style="background:#eee;">
            <th>.</th>
            <th>品項</th>
            <th>面紙訂購尺寸</th>
            <th>K數</th>
            <th>對褙(紙張需求*2)</th>
            <th>面紙裁切尺寸</th>
            <th>紙材需求</th>
            <th>紙材尺寸</th>
            <th>張/束</th>
            </tr>
            <tr>
                <td rowspan="3"><button ng-click="itemList.removeItem($index)">刪除</button></td>
                <td><input type="text" ng-model="item.name" /></td>
                <td><input type="text" ng-model="item.tos" /></td>
                <td><input type="text" ng-model="item.knum" /></td>
                <td><input type="text" ng-model="item.back" /></td>
                <td><input type="text" ng-model="item.tcs" /></td>
                <td><input type="text" ng-model="item.cfreq" /></td>
                <td><input type="text" ng-model="item.cfs" /></td>
                <td><input type="text" ng-model="item.qty" /></td>
            </tr>
            <tr style="background:#eee;">
            <th>面紙</th>
            <th>印刷</th>
            <th>上光</th>
            <th>加工</th>
            <th>紙材</th>
            <th>褙紙</th>
            <th>軋盒</th>
            <th>糊盒</th>
            </tr>
            <tr >
                <td><input type="text" ng-model="item.t" />
                    <select ng-model="item.tvendor" ng-options="v.id as v.sname for v in vendors">
                        <option value="">~請選擇~</option>
                    </select>
                </td>
                <td><input type="text" ng-model="item.prt" />
                    <select ng-model="item.prtvendor" ng-options="v.id as v.sname for v in vendors">
                        <option value="">~請選擇~</option>
                    </select>
                </td>
                <td><input type="text" ng-model="item.sfc" />
                    <select ng-model="item.sfcvendor" ng-options="v.id as v.sname for v in vendors">
                        <option value="">~請選擇~</option>
                    </select>
                </td>
                <td><input type="text" ng-model="item.heat" />
                    <select ng-model="item.heatvendor" ng-options="v.id as v.sname for v in vendors">
                        <option value="">~請選擇~</option>
                    </select>
                </td>
                <td><input type="text" ng-model="item.cf" />
                    <select ng-model="item.cfvendor" ng-options="v.id as v.sname for v in vendors">
                        <option value="">~請選擇~</option>
                    </select>
                </td>
                <td><input type="text" ng-model="item.pst" />
                    <select ng-model="item.pstvendor" ng-options="v.id as v.sname for v in vendors">
                        <option value="">~請選擇~</option>
                    </select>
                </td>
                <td>
                    <input type="text" placeholder="軋盒模數欄" ng-model="item.ga" />
                    <select ng-model="item.gavendor" ng-options="v.id as v.sname for v in vendors">
                        <option value="">~請選擇~</option>
                    </select>
                    <input type="text" placeholder="軋盒說明欄" ng-model="item.garemark" style="width:207px;" />
                </td>
                <td><input type="text" ng-model="item.glu" />
                    <select ng-model="item.gluvendor" ng-options="v.id as v.sname for v in vendors">
                        <option value="">~請選擇~</option>
                    </select>
                </td>       
            </tr>
        </table>
        </div>
    <div class="form-actions">
        <?php echo $mode==='EDIT' ? '<input type="button" class="btn btn-success" value="確定編輯" ng-click="itemList.formSubmit()" />' : '<input type="button" class="btn-success" value="確定新增" ng-click="itemList.formSubmit()" />' ?>
        <?php echo anchor("/admin/$controller/index","取消",array('class'=>'btn '))?>
    </div>
  </div>
</div>


<?php
	 echo form_fieldset_close();
	 echo form_close();  
?>

<script src="/js/angular.min.js"></script>
<script>
	$(document).ready(function(e) {



	}); //end document ready
	//------angular
	var itemApp = angular.module('itemApp', []);


itemApp.controller('itemListController', function($scope, $http) {
    var itemList = this;

    $scope.items = <?php echo $mode == 'EDIT' ? json_encode($detail_rows) : '[]'?>;
    $scope.deleted = [];
    $scope.vendors = <?php echo json_encode($vdata) ?>;
    $scope.customers = <?php echo json_encode($cdata) ?>;
    $scope.pname = '<?php echo $mode == 'EDIT' ? $editing_row['name'] : ''?>';
    $scope.price = '<?php echo $mode == 'EDIT' ? $editing_row['price'] : '0'?>';
    $scope.fk_customer = '<?php echo $mode == 'EDIT' ? $editing_row['fk_customer'] : '' ?>';
    itemList.addItem = function() {
      $scope.items.push(new Item('item' + ($scope.items.length + 1), '', '1', '1','', '1', '',125,'','','','','','','','','0','',''));
      $scope.itemText = '';
    };

    itemList.removeItem = function(idx) {

        $scope.deleted.push($scope.items[idx]['id']);
        $scope.items.splice(idx, 1);
    };

    itemList.remaining = function() {
      var count = 0;
      angular.forEach($scope.items, function(item) {
        count += item.done ? 0 : 1;
      });
      return count;
    };

    itemList.toJson = function() { 
        console.log($scope);
        //alert(angular.toJson($scope.items));
    };


    itemList.checkItems = function() {
        var resMsg = [], item;

        if($scope.items.length == 0) {
            resMsg.push('  - 請至少輸入一項品項。');
        }

        if($scope.pname == '') {
            resMsg.push('  - 請輸入[產品名稱]。');
        }
        
        if($scope.fk_customer == '' || $scope.fk_customer == null) {
            resMsg.push('  - 請選擇[所屬客戶]');
        }
        
        if($scope.price == '' || !isFinite($scope.price)) {
            resMsg.push('  - 請輸入[售價]數字 ');
        }
        
        for(var i = 0, len = $scope.items.length; i < len; i += 1) {
            item = $scope.items[i];
            // resMsg += item.back + ',' + item.knum + ',' + item.qty;
            if(item.name == '') {
                resMsg.push('  - 第' + (i + 1) + '列 的 [品項] 請輸入名稱。');
            }
            if(!item.back || !isFinite(item.back)) {
                resMsg.push('  - 第' + (i + 1) + '列 的 [對褙] 請輸入數字，或至少輸入1。');
            }
            if(!item.cfreq || !isFinite(item.cfreq)) {
                resMsg.push('  - 第' + (i + 1) + '列 的 [楞紙需求] 請輸入數字，或至少輸入1。');
            }
            if(!item.knum || !isFinite(item.knum)) {
                resMsg.push('  - 第' + (i + 1) + '列 的 [K數] 請輸入數字，或至少輸入1。');
            }
            if(!item.qty || !isFinite(item.qty)) {
                resMsg.push('  - 第' + (i + 1) + '列 的 [張/束] 請輸入數字。');
            }
            if(!item.price || !isFinite(item.price)) {
                resMsg.push('  - 第' + (i + 1) + '列 的 [售價] 請輸入數字。');
            }
            if(item.ga != '' && !isFinite(item.ga)) {
                resMsg.push('  - 第' + (i + 1) + '列 的 [模數] 若有輸入的話，請輸入數字。');
            }
        }
        return resMsg;
    }

    itemList.formSubmit = function() {
        var resMsg = itemList.checkItems();
        if(resMsg.length > 0) {
            alert('檢查到以下錯誤：' + '\n' + resMsg.join('\n'));
            return false;
        }
        if(!confirm('即將送出，確定儲存嗎？')) { return;}
        var oParam = {
            <?php 
                if($mode == 'EDIT') {echo 'id:"'.$editing_row['id'] .'",';} 
            ?>
            items: angular.toJson($scope.items),
            pname: $scope.pname,
            fk_customer: $scope.fk_customer,
            price: $scope.price,
            deleted:  angular.toJson($scope.deleted)
        };
        
        $http({
                method:'POST',
                url:'/ct/admin/product/ajax_<?php echo strtolower($mode)?>_save',
                data: $.param(oParam),
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).
            then(function(res) {
                //success
                if(res.data == 'OK'){
                    location.href = '<?php echo $okurl; ?>';
                }
               
            }, function(res) {
                //error
            });
    }

    itemList.archive = function() {
      var olditems = $scope.items;
      $scope.items = [];
      angular.forEach(olditems, function(item) {
        if (!item.done) $scope.items.push(item);
      });
    };
  });

function Item (name, tos, knum, back, tcs, cfreq, cfs, qty, t, prt, sfc, heat, cf, pst, ga, glu, price, other, otherremark) {
    this.name = name;
    this.tos = tos;
    this.knum = knum;
    this.back = back;
    this.tcs = tos;
    this.cfreq = cfreq;
    this.cfs = cfs;
    this.qty = qty;
    this.t = t;
    this.tvendor = '';
    this.prt = prt;
    this.prtvendor = '';
    this.sfc = sfc;
    this.sfcvendor = '';
    this.heat = heat;
    this.heatvendor = '';
    this.cf = cf;
    this.cfvendor = '';
    this.pst = pst;
    this.pstvendor = '';
    this.ga = ga;
    this.gavendor = '';
    this.garemark = '';
    this.glu = glu;
    this.gluvendor = '';
    this.price = price;
    this.other = other;
    this.otherremark = otherremark;
    this.done = false;
}


</script>
