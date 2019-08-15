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
<button ng-click="Appc.confirmEditing()">出貨</button>
    <table style="width:100%;">
      <thead>
      <th style="width:30px;" class="text">刪除</th><th style="width:30px;" class="text">選擇</th><th style="width:100px;" class="text">預計出貨日</th><th style="width:100px;" class="text">客戶</th><th style="width:130px;" class="text">品名</th><th style="width:100px;" class="num">單價</th><th style="width:100px;" class="num">數量</th><th style="width:100px;" class="num">金額</th>
    </thead>
    <tbody ng-repeat="h in headers | filter: Appc.filterNotDelivH ">
      <tr><td><a href="#" ng-click="Appc.removeHeader(h)">X</a></td><td><input type="checkbox" ng-model="tickFoo" ng-click="Appc.tickHeaderSel(h)" /></td><td>{{h.etd}}</td><td>{{h.cn}}</td><td>{{h.pn}}</td><td class="num">{{h.price}}</td><td class="num">{{h.qty}}</td><td class="num">{{h.subtotal}}</td></tr>
      <tr><td colspan="8">
       <table style="border-bottom:1px dashed #ddd;margin-bottom:15px;width:100%;">
          <tbody ng-repeat="d in h.details | filter: Appc.filterNotDeliv">
          <tr>
              <td style="width:30px;"></td><td style="width:30px;"><input type="checkbox" ng-model='d.selected' /></td><td style="width:100px;"></td><td style="width:100px;"></td><td style="width:130px;">{{d.pdn}}</td><td style="width:100px;" class="num">{{d.price}}</td><td style="width:100px;" class="num">{{d.qty}}</td><td style="width:100px;" class="num">{{d.subtotal}}</td></tr>
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

    Appc.removeHeader = function(item){
      if(confirm('確定刪除待出貨物？')) {
        var oParam = {
            oid: item.id
        };
        
        $http({
                method:'POST',
                url:'/ct/admin/delivery/ajax_remove_deliv',
                data: $.param(oParam),
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).
            then(function(res) {
                //success
                alert(res.data);
                if(res.data == 'OK'){
                  var index = $scope.headers.indexOf(item);          
                  $scope.headers.splice(index, 1);     
                }
            }, function(res) {
                //error
            });

        }

    };

    Appc.tickHeaderSel = function(item){
      item.selected = !item.selected;
      angular.forEach(item.details, function(v, k, o){
        v.selected = item.selected;
      });
    };

    Appc.filterNotDelivH = function(item) {
      var ds = item.details,
          isNotEmpty = false;
      for(var i = 0, len = ds.length; i < len; i += 1){
        if(ds[i].isdeliv != 'X'){ isNotEmpty = true; break;}
      }
      return isNotEmpty;
    };

    Appc.filterNotDeliv = function(item) {
      //console.log(item.pdn, item.isdeliv);
      return item.isdeliv == '' || item.isdeliv == null;
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
            //ds[i].isdeliv = 'X';
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
        alert('出貨請勿跨多張採購單!');
        return false;
      }
      
      if(sel.length == 0) { alert('未選擇出貨項目!'); return false;}
      
        var oParam = {
            items: angular.toJson(sel)
        };
        
        $http({
                method:'POST',
                url:'/ct/admin/delivery/ajax_save',
                data: $.param(oParam),
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).
            then(function(res) {
                //success
                if(res.data == 'OK'){
                  alert('已出貨!');
                  //Appc.closeEditing();
                }
                location.href = '/ct/admin/delivery'; //因為把上面的ds[i].isdeliv = 'X';註解了，所以需要這一行來刷新
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