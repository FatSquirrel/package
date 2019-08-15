<?php 
	//用來串在許多地方，免得常要重複輸入
	$controller = $this->uri->segment(2);
	
?>

<style type="text/css">
  .donegroup {

    border-bottom: 1px dotted #aaa;
  }
  .donegroup:hover {
    
    cursor:pointer;
    background:#DDEAF6;
   
  }
  
  .list-div .field1 {
    width:70px;
  }
  .list-div .field2 {
    width:150px;
  }
  .list-div .field3 {
    width:50px;
  }
  .list-div .field4 {
    width:50px;
  }
  .list-div .field5 {
    width:50px;
  }
  .list-div .field6 {
    width:50px;
  }

  .list-div { 
    width:88%;
    height:430px;
    overflow-y:auto;
    margin:1px;
    padding:10px;
    border:1px solid #8492D4;
    border-radius:5px;
  }

  .list-div table {
    width:100%;
    /* border-collapse:collapse;
    border:1px solid #000; */
  }

  .list-div table tr:hover {
    /*
    cursor:pointer;
    background:#DDEAF6;
    */
  }

  .list-div table td {

  }


  
  .list-div table td {
    padding:3px;

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

  .hide {
    display:none;
  }

  .show {
    display:block;
  }

  .left-item {
    cursor:pointer;
  }

  .list-div .selecting {
    background:#DDEAF6;
  }

</style>
<div ng-app="App">
  
<div id="outer" ng-controller="AppController as Appc">


<div class="div-edit-modal" ng-class="{'hide': selecting == false, 'show': selecting == true}">
    <div class="div-edit-header">
      公司採購單<br />
採購日期:
    </div>
    <div class="div-edit-litsec">
    <table border="1" style="border-collapse:collapse;width:100%">
      <thead><tr><td>序</td><td>紙材</td><td>尺寸</td><td>數量</td><td>指送</td></tr></thead>
      <tbody ng-repeat="item in oditems | filter:Appc.filterSelected">
        <tr><td>{{item.sn}}</td><td>{{item.cf}}</td><td>{{item.cfs2}}</td><td>{{item.cfqty2}}</td><td>{{item.cf_nextvendor_cn}}</td></tr>
      </tbody>
    </table>
   </div>
    <div class="div-edit-litsec">
      【備註】<br />
      <textarea style="width:466px;" ng-model="remark">
      </textarea>
    </div>
    <div class="div-edit-litsec">
      <button ng-click="Appc.closeEditing()">返回</button>
      <button ng-click="Appc.confirmEditing()">確定</button>
    </div>
  </div>
  查詢日期：<input type="date" id="bd" ng-model="bd" /> ~ <input type="date" id="ed" ng-model="ed" />
    <a ng-click="Appc.query()" class="btn" ><i class="icon-search"></i>查詢</a> 
    <a href="/ct/admin/<?php echo $controller ?>" class="btn" ><i class="icon-remove"></i>清除條件</a> 
    <div style="clear:both;"></div>
  


<div style="float:left;width:500px;">
   <span style="font-size:20px;font-weight:bold;text-decoration:underline;">待採購</span>
   <span style="font-size:17px;">廠商：</span>
    <select style="width:120px;" ng-model="filteringVendor" ng-change="Appc.changeVendor()" ng-options="v.id as v.sname for v in vendors">
      <option value="">~請選擇~</option>
    </select>
    <button style="background:#00B0F0;color:#fff;border:1px solid #4472C4;" ng-click="Appc.chooseItem()">確定採購</button>
    <div class="list-div">
      <table>
          <td>工單號碼</td>
          <td>產品名稱</td>
          <td>紙材</td>
          <td>尺吋</td>
          <td>數量</td>
          <td>客戶</td>
        <tr class="left-item" ng-repeat="item in oditems | filter: Appc.filterLeft" ng-click="Appc.selectingItem(item)" ng-class="{'selecting': item.selected == 'X'}">
          <td>{{item.orderno}}</td>
          <td>{{item.pname}}</td>
          <td>{{item.cf}}</td>
          <td>{{item.cfs2}}</td>
          <td>{{item.cfqty2}}</td>
          <td>{{item.cname}}</td>
        </tr>
      </table>
    </div>
</div>
<div style="float:left;width:600px;">
    <span style="font-size:20px;font-weight:bold;text-decoration:underline;">已採購</span> 
    <span style="font-size:12px;color:#aaa;">*只保留兩週內之資料</span>
    <button ng-click="Appc.exportxls()" style="margin:0 0 10px 0;">EXCEL</button>
    <div class="list-div">

        <table>
          <td class="field1">工單號碼</td>
          <td class="field2">產品名稱</td>
          <td class="field3">紙材</td>
          <td class="field4">尺吋</td>
          <td class="field5">數量</td>
          <td class="field6">客戶</td>
        </table>
      <div class="donegroup" ng-repeat="(key, group) in donerows" ng-mouseup="Appc.processChoose(key, group, $event);">
    
        <table>
          <tr ng-repeat="item in group">
            <td class="field1">{{item.orderno}}</td>
            <td class="field2">{{item.pname}}</td>
            <td class="field3">{{item.cf}}</td>
            <td class="field4">{{item.cfs2}}</td>
            <td class="field5">{{item.cfqty2}}</td>
            <td class="field6">{{item.cname}}</td>
          </tr>
        </table>
      </div>
    </div>
</div>
  <div style="clear:left;"></div>
  </div>
</div>
<script src="/js/angular.min.js"></script>
<script>
  //關掉右鍵選單
  document.querySelector('html').addEventListener('contextmenu', function(e) {
    e.preventDefault();
  });

  angular.module('App', []).
  controller('AppController',['$scope','$http',function($scope,$http){
    //ddd
    var Appc = this;
    $scope.oditems = <?php echo json_encode($notdone);?>;
    $scope.donerows = <?php echo json_encode($done);?>; 
    

    

    $scope.editing = null;    
    $scope.vendors = <?php echo json_encode($vdata) ?>;
    $scope.selecting = false;
    $scope.filteringVendor = {id:''};


    Appc.processChoose = function(key, group, evt) {
      if(evt.button == 0) { //若按下左鍵，就匯出pdf
        window.open('/ct/admin/<?php echo $controller ?>/generate?id=' + key);
      }
      if(evt.button == 2) { //若按下右鍵，就轉回未處理

      //  var rollbackitem = $scope.oditems[$scope.oditems.findIndex( item => item.id == id)];
        var oParam = { id: key };
       
       $http({
                method:'POST',
                url:'/ct/admin/<?php echo $controller ?>/ajax_setnotpurchase',
                data: $.param(oParam),
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).
            then(function(res) {
                //success
                if(res.data === 'OK') {
                  group.forEach(item => $scope.oditems.push(item));
                  delete $scope.donerows[key];
                } else {
                  alert('發生錯誤;');
                }
            }, function(res) {
                //error
            });
      }
    };


    Appc.exportxls = function() {
      var ids = [];
      var bd = $scope.bd && dt2dstr($scope.bd, '/');
      var ed = $scope.ed && dt2dstr($scope.ed, '/');
      var claus = '';

      for(var k in $scope.donerows) {
        ids.push(k);
      }

      if(bd) {
        claus = '&bd=' + bd + '&ed=' + ed;
      }
      
      window.open('/ct/admin/<?php echo $controller; ?>/excel?ids=' + ids + claus);
    };

 

    Appc.query = function() {
      var msg = [];
      var bd = $scope.bd;
      var ed = $scope.ed;
      
      if($scope.bd == undefined && $scope.ed != undefined) {
        $scope.ed = undefined;
        ed = undefined;
      }

      if(ed == undefined) {
        $scope.ed = ed = bd;
      }


      bd = bd ? dt2dstr(bd, '') : '';
      ed = ed ? dt2dstr(ed, '') : '';
      

      if(bd != '' && ed != '') {
        var oParam = {
            bd: bd,
            ed: ed
        };
        $http({
                method:'POST',
                url:'/ct/admin/<?php echo $controller ?>/ajax_search',
                data: $.param(oParam),
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).
            then(function(res) {
                //success
                //把未處理的撈出來
                //$scope.oditems = $scope.oditems.filter( item => item.isorderdone == '');

                //把搜尋結果(已處理的)塞進去.
                $scope.donerows = res.data;
                
                
                //location.href = '/ct/admin/processorder/index';
            }, function(res) {
                //error
            });
      }
    };
    Appc.selectingItem = function(item) {
      //已經開啟視窗後就不要再給選了。
      if($scope.selecting == false) {
        if(item.selected == 'X') {
          item.selected = '';
        } else {
          item.selected = 'X';
        }
      }

    };

    Appc.changeVendor = function() {
       if($scope.oditems.length > 0){
         $scope.oditems[0].foo = Math.random(); //為了觸發filter，隨意變動值
       }
    };

    Appc.chooseItem = function() {
     var items = $scope.oditems,
         item = null,
         selectedCount = 1;
     for(var i = 0, len = items.length; i < len; i += 1) {
      item = items[i];
      if(item.selected != '' && item.selected != undefined) {
        
        $scope.selecting = true;
        item.sn = selectedCount++;
        //break;
      }
     }
    };

    Appc.closeEditing = function(){
      angular.forEach($scope.oditems, function(value, key) {
        value.selected = '';
        value.sn = '';
      });
      $scope.selecting = false;
      $scope.remark = '';

    };

    Appc.confirmEditing = function(){
      var selItems = [];
      angular.forEach($scope.oditems, function(v, k, o) {
        if(v.selected === 'X') {
          v.ispo_cf_done = 'X';
          selItems.push(v);
        }
      });

      if(selItems.length == 0) {
        alert('請至少選擇一筆採購資料');
        return false;
      }
      var oParam = {
          items: angular.toJson(selItems),
          remark: $scope.remark || ''
      };

      $http({
              method:'POST',
              url:'/ct/admin/processpo_cf/ajax_processpo_save',
              data: $.param(oParam),
              headers: {'Content-Type': 'application/x-www-form-urlencoded'}
          }).
          then(function(res) {
              //success
              if(res.data == 'OK'){
                alert('楞紙採購單建立完成!');
                Appc.closeEditing();
              }
              location.href = '/ct/admin/processpo_cf/index';
          }, function(res) {
              //error
      });
    };

    Appc.filterLeft = function(item) {
      if(item.ispo_cf_done == '' && item.cfvendor == $scope.filteringVendor){
        return true;
      } else {  
        return false;
      }
    };
    
    Appc.filterSelected = function(item) {
      return item.selected == 'X';
    };



}]);

</script>