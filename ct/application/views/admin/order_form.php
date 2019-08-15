<style type="text/css">
    input[type=text] , select {
        width:100px;
    }
</style>
<div ng-app="itemApp">
      <div ng-controller="itemListController as itemList">

<?php
		$controller = $this->uri->segment(2);

		$form_attr = array( 
											'id'=>'addform',
											'class'=>'form-horizontal'
											//'ng-submit'=>'itemList.formSubmit()'
								);
		
		if( $mode === 'EDIT' ) :   
			echo form_open_multipart("/admin/$controller/edit_save",$form_attr);
			echo form_fieldset("訂單編輯表單");
			
			echo form_hidden("hd_id",$editing_row['id']);

			?>
    	<div class="control-group">
				<label class="control-label">訂單系統編號</label>
				<div class="controls">
					<?php echo form_input(array('value'=>$editing_row['id'], 'readonly'=>'readonly')); ?>
				</div>
		</div>
		<?php 

			else:
				echo form_open_multipart("/admin/$controller/add_save",$form_attr);
				echo form_fieldset("訂單編輯表單");
			endif;
			
			
			?>
        


    	<div class="control-group">
		<label class="control-label" for='ddl_customer'>客戶</label>
		<div class="controls">
            <select name="ddl_customer" ng-options="c.id as c.sname for c in customers" ng-model="fk_customer" ng-change="itemList.changeCustomer()">
                <option value="">~ 請選擇 ~</option>
            </select>
		</div>
		</div>
        <div class="control-group">
        <label class="control-label" for='ddl_product'>產品</label>
        <div class="controls">
            <select name="ddl_product" ng-options="p.id as p.name for p in products" ng-model="fk_product" >
                <option value="">~ 請選擇 ~</option>
            </select>
        </div>
        </div>
        <div class="control-group">
            <label class="control-label" for='txt_name'>訂購數量</label>
            <div class="controls">          
                <input type='text' id='txt_name' name='txt_name' ng-model="qty"/>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for='txt_name'>預計交貨日</label>
            <div class="controls">          
                <input type='date' id='txt_name' name='txt_name' ng-model="etd"/>
            </div>
        </div>
        <div class="control-group invisible">
   
              <label class="control-label " for='txt_prtpr'>印刷版費請款</label>
              <input type="text" name="txt_prtpr" value="1" ng-model="prtpr"/> 組
              <input type="text" name="txt_bladepr_price" value="0" ng-model="prtpr_price"/> 元
              
              

        </div>
        <div class="control-group invisible">
              <label class="control-label" for='txt_namtxt_bladepre'>刀模費請款</label>
              <input type="text" name="txt_bladepr" value="1" ng-model="bladepr"/> 組
              <input type="text" name="txt_bladepr_price" value="0" ng-model="bladepr_price"/> 元

        </div>

    <div class="form-actions">
        <?php echo $mode==='EDIT' ? '<input type="button" class="btn-class" value="確定編輯" ng-click="itemList.formSubmit()" />' : '<input type="button" class="btn-success" value="確定新增" ng-click="itemList.formSubmit()" />' ?>
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
  function dt2dstr(d) {
    return d.getFullYear() + '/' + (d.getMonth()+1) + '/' + d.getDate();
  }
	//------angular
	var itemApp = angular.module('itemApp', []);


itemApp.controller('itemListController', function($scope, $http) {
    var itemList = this;

    $scope.items = <?php echo $mode == 'EDIT' ? json_encode($detail_rows) : '[]'?>;
    
    $scope.customers = <?php echo json_encode($cdata) ?>;
    $scope.products = [];
    $scope.pname = '<?php echo $mode == 'EDIT' ? $editing_row['name'] : ''?>';
    $scope.fk_customer = '<?php echo $mode == 'EDIT' ? $editing_row['fk_customer'] : '' ?>';
    $scope.prtpr = 1;
    $scope.bladepr = 1;
    itemList.addItem = function() {
      $scope.items.push(new Item('item' + ($scope.items.length + 1), '300*200', '10', '','','','','','','','','','','','','',''));
      $scope.itemText = '';
    };

    itemList.removeItem = function(idx) {
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
    
    itemList.changeCustomer = function() {
       var oParam = {
            cid: $scope.fk_customer
        };
        
        $http({
                method:'POST',
                url:'/ct/admin/product/ajax_get_all_by_custid',
                data: $.param(oParam),
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).
            then(function(res) {
                //success
                $scope.products = res.data;
            }, function(res) {
                //error
            });
    };

    itemList.checkItems = function() {
        var resMsg = [], item;


        if($scope.fk_customer == '' || $scope.fk_customer == null) {
            resMsg.push('  - 請選擇[客戶]');
        }
        if($scope.fk_product == '' || $scope.fk_product == null) {
            resMsg.push('  - 請選擇[產品]');
        }

        if(!$scope.qty || !isFinite($scope.qty)) {
            resMsg.push('  - [訂購數量] 請輸入數字');
        }
        if(!$scope.etd) {
            resMsg.push('  - 請輸入[預計交貨日]');
        }

        if(!$scope.prtpr || !isFinite($scope.prtpr)) {
            resMsg.push('  - [印刷版費請款] 請至少輸入數字1。');
        }
        if($scope.prtpr_price && !isFinite($scope.prtpr_price) || $scope.prtpr_price == 0) {
            resMsg.push('  - [印刷版費請款] 售價有輸入的話，請輸入數字。');
        }
        if(!$scope.bladepr || !isFinite($scope.bladepr)) {
            resMsg.push('  - [刀模費請款] 請至少輸入數字1。');   
        }
        if($scope.bladepr_price && !isFinite($scope.bladepr_price) || $scope.bladepr_price == 0) {
            resMsg.push('  - [刀模費請款] 售價有輸入的話，請輸入數字。');
        }

        return resMsg;
    }

    itemList.formSubmit = function() {
        var resMsg = itemList.checkItems();
        if(resMsg.length > 0) {
            alert(resMsg.join('\n'));
            return false;
        }

        var oParam = {
            <?php 
                if($mode == 'EDIT') {echo 'id:"'.$editing_row['id'] .'",';} 
            ?>
            fk_product: $scope.fk_product,
            fk_customer: $scope.fk_customer,
            qty: $scope.qty,
            etd: dt2dstr($scope.etd),
            prtpr: $scope.prtpr,
            prtpr_price: $scope.prtpr_price,
            bladepr: $scope.bladepr,
            bladepr_price: $scope.bladepr_price
        };
        
        $http({
                method:'POST',
                url:'/ct/admin/order/ajax_<?php echo strtolower($mode)?>_save',
                data: $.param(oParam),
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).
            then(function(res) {
                //success
                location.href = '/ct/admin/<?php echo $controller; ?>/index';
                //console.log(res);
            }, function(res) {
                //error
                console.log(res);
            });
    };

    itemList.archive = function() {
      var olditems = $scope.items;
      $scope.items = [];
      angular.forEach(olditems, function(item) {
        if (!item.done) $scope.items.push(item);
      });
    };
  });

function Item (name, tos, knum, back, tcs, cfs, qty, t, prt, sfc, heat, cf, pst, ga, glu, price, other) {
    this.name = name;
    this.tos = tos;
    this.knum = knum;
    this.back = back;
    this.tcs = tos;
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

    
    this.done = false;
}


</script>
