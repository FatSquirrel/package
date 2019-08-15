<?php 
	//用來串在許多地方，免得常要重複輸入
	$controller = $this->uri->segment(2);
	
?>

<style type="text/css">
  .list-div { 
    width:400px;
    float:left;
    margin:10px;
    padding:10px;
    border:1px solid #eee;
    border-radius:5px;
  }

  .list-item-notdone {
    width:100%;
    height:30px;
    background:#aaa;
    margin:3px;
    border-radius:3px;
  }

  .div-edit-modal {
    width:500px;
    
    background:#eee;
    position:absolute;
    top:10px;left:600px;
    min-height:500px;   
    display:;
  }

  .div-edit-litsec {
    border-bottom:1px solid #000;
  }

  .div-edit-header {
    background:#00B0F0;
    width:100%;
    font-weight:bold;
    border-bottom:3px solid #000;
  }
  .num {
    text-align:right;
  }
  .text {
    text-align:left;
  }

  .hide {
    display:none;
  }

  .show {
    display:block;
  }

</style>
<div ng-app="App">
  <div ng-controller="AppController as Appc" style="width:900px;">
<button ng-click="Appc.confirmEditing()" class="btn">退貨</button>
 客戶：
    <select ng-model="filteringCustomer" ng-change="Appc.changeCustomer()" ng-options="c.id as c.sname for c in customers">
      <option value="">~請選擇~</option>
    </select>&nbsp;
    <span style="font-size:12px;color:#aaa;">*保留出貨日期起算二個月內之資料</span>
    <table style="width:100%;">
      <thead>
      <th style="width:30px;" class="text">選擇</th><th style="width:140px;" class="text">出貨日期</th><th style="width:100px;" class="text">客戶</th><th style="width:130px;" class="text">品名</th><th style="width:100px;" class="num">單價</th><th style="width:100px;" class="num">數量</th><th style="width:100px;" class="num">金額</th>
    </thead>
    <tbody ng-repeat="h in headers | filter: Appc.filterNotDelivH ">
      <tr><td><input type="checkbox" ng-model="tickFoo" ng-click="Appc.tickHeaderSel(h)" /></td><td><input type="date" ng-model="h.delivdate" style="width:140px;" class="text"/></td><td class="text" style="width:100px">{{h.cn}}</td><td class="text">{{h.pn}}</td><td class="num">{{h.price}}</td><td class="num">{{h.qty}}</td><td class="num">{{h.subtotal}}</td></tr>
      <tr><td colspan="7">
       <table style="border-bottom:1px dashed #ddd;margin-bottom:15px;width:100%;">
          <tbody ng-repeat="d in h.details | filter: Appc.filterNotDeliv">
          <tr>
              <td style="width:30px;" class="text"><input type="checkbox" ng-model='d.selected' /></td><td style="width:140px;"></td><td style="width:111px;"></td><td style="width:130px;" class="text">{{d.pdn}}</td><td style="width:100px;" class="num">{{d.price}}</td><td style="width:100px;" class="num">{{d.qty}}</td><td style="width:100px;" class="num">{{d.subtotal}}</td></tr>
          </tr>
          </tbody>
          <tfoot>
            <tr>
              <td colspan="7" style="height:15px;"></td>
            </tr>
          </tfoot>
       </table>
      </td></tr>
    </tbody>

    </table>
  </div>
</div>
<script src="/js/angular.min.js"></script>
<script>
  angular.module('App', []).
  controller('AppController',['$scope','$http',function($scope,$http){
    //ddd
    var Appc = this;
    $scope.filteringCustomer = {id:''};
    $scope.customers = <?php echo json_encode($cdata) ?>;
    // $scope.headers = [
    //   {'id':123, 'etd':'2015-01-01', 'cn':'圈圈公司', 'pn': '水果盒', 'price':'10', 'qty':500, 'subtotal': 5000, 
    //    details:[{pdn:'Test組件1', price:3, qty:500, subtotal:1500},
    //             {pdn:'test組件2', price:7, qty:500, subtotal:3500}
    //            ]
    //   },
    //   {'id':456, 'etd':'2015-08-19', 'cn':'XX公司', 'pn': '水果盒', 'price':'10', 'qty':100, 'subtotal': 1000,
    //     details:[{pdn:'test組件1', price:10, qty:500, subtotal:5000}]
    //   }
    // ];
    $scope.headers = <?php echo json_encode($arrod) ?>;
    for(var h in $scope.headers){
      var item = $scope.headers[h];
      var parts = item.delivdate.split('-');
      $scope.headers[h].delivdate = new Date(parts[0], parts[1] - 1, parts[2]);
    }
    Appc.tickHeaderSel = function(item){
      item.selected = !item.selected;
      angular.forEach(item.details, function(v, k, o){
        v.selected = item.selected;
      });
    };

    Appc.filterCustomer = function(item) {
      if(item.fk_customer == $scope.filteringCustomer){
        return true;
      } else {  
        return false;
      }
    };

    Appc.filterNotDelivH = function(item) {
      var ds = item.details,
          isNotEmpty = false,
          isMatchCustomer = false;
      for(var i = 0, len = ds.length; i < len; i += 1){
        if(ds[i].isdeliv != 'X'){ isNotEmpty = true; break;}
      }

      if(item.cid == $scope.filteringCustomer){
        isMatchCustomer = true;
      } else {  
        isMatchCustomer = false;
      }
    

      return isNotEmpty && isMatchCustomer;
    };


    Appc.filterNotDeliv = function(item) {
      //console.log(item.pdn, item.isdeliv);
      return item.isdeliv == '' || item.isdeliv == null;
    };

    Appc.changeCustomer = function() {
       if($scope.headers.length > 0){
         $scope.headers[0].foo = Math.random(); //為了觸發filter，隨意變動值
       }
    };

    Appc.confirmEditing = function(){
      var sel = [];
      var dsc = {};

      angular.forEach($scope.headers, function(v, k, o){
        var ds = v.details;
        if(dsc[v.id] == null) {
          dsc[v.id] = 0;
        }

        for(var i = 0, len = ds.length; i < len; i += 1){
          if(ds[i].selected == true) {
            dsc[v.id]++;
            sel.push({'did':ds[i]['did'], 'oid': v.id});

          }
        }
      })//end forEach

      var seld = 0;
      for(var k in dsc){//(var i = 0, len = dsc.length; i < len; i += 1){
        if(dsc[k] > 0){
          seld++;
        }
      }

      if(seld > 1){
        alert('退貨請勿跨多張採購單!');
        return false;
      }
      

      if(sel.length == 0) { alert('未選擇退貨項目!'); return false;}

        var oParam = {
            items: angular.toJson(sel)
        };

        $http({
                method:'POST',
                url:'/ct/admin/delivery_back/ajax_save',
                data: $.param(oParam),
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).
            then(function(res) {
                //success
                if(res.data == 'OK'){
                  alert('已設定為退貨');
                  //Appc.closeEditing();
                }
                location.href = '/ct/admin/delivery_back';
            }, function(res) {
                //error
            });

      
    };

    Appc.filterLeft = function(item) {

      if(item.ispodone == ''){
        return true;
      } else {
        return false;
      }
    };


}]);

</script>