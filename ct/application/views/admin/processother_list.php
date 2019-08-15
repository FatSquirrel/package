<?php 
	//用來串在許多地方，免得常要重複輸入
	$controller = $this->uri->segment(2);
	
?>

<style type="text/css">
  .list-div { 
    width:100%;
    height:230px;
    overflow-y:auto;
    margin:10px;
    padding:10px;
    border:1px solid #8492D4;
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
    width:700px;
    height:800px;
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

  .hide {
    display:none;
  }

  .show {
    display:block;
  }

</style>
<div ng-app="App">
  
<div ng-controller="AppController as Appc" style="width:500px;">
    <span style="font-size:20px;font-weight:bold;text-decoration:underline;">待處理</span>
    <div class="list-div">
      <ul class="nav nav-pills nav-stacked">
        <li ng-repeat="item in otheritems | filter: Appc.filterNotDone" ng-click="item.selected == 'X' ? item.selected = '' : item.selected = 'X'" ng-class="{'active': item.selected == 'X'}">
          <a href="#">{{item.cname + '_' + item.pname + '_' + item.pdname + '_' + item.qty}}</a></li>
      </ul>
      
    </div>
    <button style="float:right;font-size:18px;font-weight:bold;width:150px;" ng-click="Appc.confirmEditing()">完&nbsp;&nbsp;成</button> <br /> 
    <span style="font-size:20px;font-weight:bold;text-decoration:underline;">已處理</span> 
    <span style="font-size:12px;color:#aaa;">*只保留兩週內之資料</span>
    <div class="list-div">
      <ul class="nav nav-pills nav-stacked">
        <li ng-repeat="item in otheritems | filter: Appc.filterDone" ><a href="#">{{item.cname + '_' + item.pname + '_' + item.pdname + '_' + item.qty}}</a></li>
      </ul>
    </div>
    <div style="clear:left;"></div>
  </div>
</div>
<script src="/js/angular.min.js"></script>
<script>
  angular.module('App', []).
  controller('AppController',['$scope','$http',function($scope,$http){
    //ddd
    var Appc = this;
    $scope.otheritems = <?php echo json_encode($arrother);?>;


    Appc.confirmEditing = function(){

      var items = $scope.otheritems,
          item,
          items2 = [],
          oParam;
      for(var i = 0, len = items.length; i < len; i += 1) {
        item = items[i];
        if(item['selected'] === 'X') {
          item = {id: item.id, isotherdone: 'X'}; //只留下需要的欄位送到server
          items2.push(item);
        }
      }
      oParam = {
            items: angular.toJson(items2)
        };
        
        $http({
                method:'POST',
                url:'/ct/admin/processother/ajax_processother_save',
                data: $.param(oParam),
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).
            then(function(res) {
                //success
                var items = $scope.otheritems,
                    item,
                    items2 = [];
                if(res.data == 'OK'){
                  alert('訂單 "其它" 項目處理完成!');
                }

                for(var i = 0, len = items.length; i < len; i += 1) {
                  item = items[i];
                  if(item['selected'] === 'X') {
                    item['isotherdone'] = 'X';
                  }
                  items2.push(item);
                }
                $scope.otheritems = items2;
                //location.href = '/ct/admin/processorder/index';
            }, function(res) {
                //error
            });

      
    };

    Appc.filterNotDone = function(item) {
      if(item.isotherdone == '') {
        return true;
      } else {
        return false;
      }
    };

    Appc.filterDone = function(item) {
      if(item.isotherdone == 'X') {
        return true;
      } else {
        return false;
      }
    };

}]);

</script>