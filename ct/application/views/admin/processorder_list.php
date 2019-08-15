<?php 
	//用來串在許多地方，免得常要重複輸入
	$controller = $this->uri->segment(2);
	
?>

<style type="text/css">
  input[type=text] {
    width:89px;
  }
  select {
    width:100px;
  }
  .list-div { 
    width:88%;
    height:430px;
    overflow-y:auto;
    margin:10px;
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
    cursor:pointer;
    background:#DDEAF6;
  }

  .list-div table tr:first-child {

    color:#999;
    font-weight:bold;
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
    width:715px;
    
    background:#eff;
    position:absolute;
    top:10px;left:400px;
  
    border:1px solid #000;

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

  .tb-bd {
    width:100%;
    border-collapse:collapse;
  }

  .tb-bd td { width:100px; }

</style>

<div ng-app="App">

<div id="outer" ng-controller="AppController as Appc" >

<form id="qForm">
查詢日期：<input type="date" id="bd" ng-model="bd" /> ~ <input type="date" id="ed" ng-model="ed" />
<a ng-click="Appc.query()" class="btn" ><i class="icon-search"></i>查詢</a> 
<a href="/ct/admin/<?php echo $controller ?>" class="btn" ><i class="icon-remove"></i>清除條件</a> 



 
</form>
<div class="div-edit-modal" ng-class="{'hide': editing == null, 'show': editing != null}">
<div style="float:left;350px;">
    <div class="div-edit-header">外發工單</div>
    <table class="tb-bd" border="1">
      <tr>
        <td style="width:100px;">面紙：</td>
        <td style="width:100px;"><input type="text" ng-model="editing.t" /></td>
        <td style="width:100px;">
          <select ng-model="editing.tvendor" ng-options="v.id as v.sname for v in vendors">
            <option value="">無</option>
          </select>
        </td>
      </tr>
      <tr>
        <td>印刷：</td>
        <td><input type="text" ng-model="editing.prt" /></td>
        <td>
          <select ng-model="editing.prtvendor" ng-options="v.id as v.sname for v in vendors">
            <option value="">無</option>
          </select>
        </td>
      </tr>
      <tr>
        <td>上光：</td>
        <td><input type="text" ng-model="editing.sfc" /></td>
        <td>
          <select ng-model="editing.sfcvendor" ng-options="v.id as v.sname for v in vendors">
            <option value="">無</option>
          </select>
        </td>
      </tr>
      <tr>
        <td>燙金：</td>
        <td><input type="text" ng-model="editing.heat" /></td>
        <td>
          <select ng-model="editing.heatvendor" ng-options="v.id as v.sname for v in vendors">
            <option value="">無</option>
          </select>
        </td>
      </tr>
      <tr>
        <td>紙材：</td>
        <td><input type="text" ng-model="editing.cf" /></td>
        <td>
          <select ng-model="editing.cfvendor" ng-options="v.id as v.sname for v in vendors">
            <option value="">無</option>
          </select>
        </td>
      </tr>
      <tr>
        <td>貼合：</td>
        <td><input type="text" ng-model="editing.pst" /></td>
        <td>
          <select ng-model="editing.pstvendor" ng-options="v.id as v.sname for v in vendors">
            <option value="">無</option>
          </select>
        </td>
      </tr>
      <tr>
        <td>軋盒：</td>
        <td><input type="text" ng-model="editing.ga" /></td>
        <td>
          <select ng-model="editing.gavendor" ng-options="v.id as v.sname for v in vendors">
            <option value="">無</option>
          </select>
        </td>
      </tr>
      <tr>
        <td>糊盒：</td>
        <td><input type="text" ng-model="editing.glu" /></td>
        <td>
          <select ng-model="editing.gluvendor" ng-options="v.id as v.sname for v in vendors">
            <option value="">無</option>
          </select>
        </td>
      </tr>
    </table>
</div>
<div style="float:left;width:400px;">
    <div class="div-edit-header">採購作業
      <div style="float:right;margin-right:10px;cursor:pointer" ng-click="Appc.closeEditing()">X</div>
    </div>
    <div class="div-edit-litsec">
      <table border="1" class="tb-bd">
        <tr>
          <td>訂購數量</td><td>{{editing.qty}}</td>
          <td>製作數量</td><td><input type="text" ng-model="editing.qty2" ng-change="Appc.changeQty2()" /></td>
        </tr>
        <tr>
          <td>面紙應訂數量</td><td>{{editing.toq}}</td>
          <td>面紙實訂數量</td><td><input type="text" ng-model="editing.toq2" /></td>
        </tr>
        <tr>
          <td>面紙應訂尺寸</td><td>{{editing.tos}}</td>
          <td>面紙實訂尺寸</td><td><input type="text" ng-model="editing.tos2" /></td>
        </tr>
        <tr>  
          <td>紙材應訂數量</td><td>{{editing.cfqty}}</td>
          <td>紙材實訂數量</td><td><input type="text" ng-model="editing.cfqty2" /></td>
        </tr>
        <tr>
          <td>紙材應訂尺寸</td><td>{{editing.cfs}}</td>
          <td>紙材實訂尺寸</td><td><input type="text" ng-model="editing.cfs2" /></td>
        </tr>
      </table>
    </div>
    <div class="div-edit-header">備註</div>
    <div>
      <textarea ng-model="editing.remark" style="width:387px;height:94px;"></textarea>
      
    </div>
    
</div>

    <div class="div-edit-litsec" style="border-bottom:0px;text-align:right;">
      <button class="btn" ng-click="Appc.closeEditing()">返回</button>
      <button class="btn btn-success" ng-click="Appc.confirmEditing()">確定</button>
    </div>
  </div>
<div style="float:left;width:400px;">
   <span style="font-size:20px;font-weight:bold;text-decoration:underline;">待處理工單</span>
    <div class="list-div">
      <table>
        <tr>
          <td>工單號碼</td>
          <td>產品名稱</td>
          <td>品項</td>
          <td>數量</td>
          <td>客戶</td>
        </tr>
        <tr ng-repeat="item in oditems | filter: Appc.filterNotDone" ng-click="Appc.chooseItem(item)" ng-class="{'active': editing.id == item.id}">
          <td>{{item.orderno}}</td>
          <td>{{item.pname}}</td>
          <td>{{item.pdname}}</td>
          <td>{{item.qty}}</td>
          <td>{{item.cname}}</td>
        </tr>
      </table>
    </div>
</div>
<div style="float:left;width:400px;">
    <span style="font-size:20px;font-weight:bold;text-decoration:underline;">已處理工單</span> 
    <span style="font-size:12px;color:#aaa;">*只保留兩週內之資料</span>
    <button ng-click="Appc.exportxls()">EXCEL</button>
    <div class="list-div">
    <table>
        <tr>
          <td>工單號碼</td>
          <td>產品名稱</td>
          <td>品項</td>
          <td>數量</td>
          <td>客戶</td>
        </tr>
        <tr ng-repeat="item in oditems | filter: Appc.filterDone" ng-mouseup="Appc.processChoose(item.id, $event);" ng-class="{'active': editing.id == item.id}">
          <td>{{item.orderno}}</td>
          <td>{{item.pname}}</td>
          <td>{{item.pdname}}</td>
          <td>{{item.qty}}</td>
          <td>{{item.cname}}</td>
        </tr>
      </table>

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
    $scope.oditems = <?php echo json_encode($arrod);?>;
    $scope.editing = null;
    $scope.vendors = <?php echo json_encode($vdata) ?>;

    Appc.processChoose = function(id, evt) {
      if(evt.button == 0) { //若按下左鍵，就匯出pdf
        window.open('/ct/admin/processpp/generate?id=' + id);
      }
      if(evt.button == 2) { //若按下右鍵，就轉回未處理
 
       var rollbackitem = $scope.oditems[$scope.oditems.findIndex( item => item.id == id)];
       var oParam = { id: rollbackitem.id };
       
       $http({
                method:'POST',
                url:'/ct/admin/<?php echo $controller ?>/ajax_setnotdone',
                data: $.param(oParam),
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).
            then(function(res) {
                //success
                if(res.data === 'OK') {
                  rollbackitem.isorderdone = '';
                } else {
                  alert('發生錯誤;');
                }
            }, function(res) {
                //error
            });
      }

      

    };
    Appc.exportxls = function() {
      var ids = $scope.oditems.filter(item => item.isorderdone == 'X').map( item => item.id).join();
      var bd = $scope.bd && dt2dstr($scope.bd, '/');
      var ed = $scope.ed && dt2dstr($scope.ed, '/');
      var claus = '';
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
                $scope.oditems = $scope.oditems.filter( item => item.isorderdone == '');

                //把搜尋結果(已處理的)塞進去.
                res.data.forEach(d => $scope.oditems.push(d));
                
                
                //location.href = '/ct/admin/processorder/index';
            }, function(res) {
                //error
            });
      }
    };
    //傳進 製作數量 及 張/束 數量
    Appc.calcQty2 = function(qty, qty_paper, knum, back, ga) {
      qty = qty || 0;
      qty_paper = qty_paper || 1;
      knum = knum || 1;
      back = back || 1;
      ga = ga || 1;

      //var qtyFinal = ((qty / knum) / ga) * back;
      var qtyFinal = qty / knum / ga * back;
      var toqFormula = '[(' + qty + ' / ' + knum + ') / ' + ga + '] * ' + back;
      var qent = qtyFinal / 500;
      var part1 = Math.floor(qent); //令
      var _part2 = Math.ceil((qent - part1) * 500 / qty_paper); //束
      var part2;
      var q = 0;
      
      //以下處理束進位令
      if(qty_paper == 100) {
        q = 5;
      } else if(qty_paper == 125) {
        q = 4;
      } else if(qty_paper == 250){
        q = 2;
      } else { // == 500
        q = 1;
      }

      qent = _part2 / q; //ex: 4束 / 4;
      part1 += Math.floor(qent);
      part2 = _part2 % q;

      //傳回0: 令, 1: 束, 2: 面紙應訂數量
      return [part1, part2, qtyFinal, toqFormula];
    };

    Appc.calcCfqty = function(qty, ga, cfreq) {
      qty = qty || 0;
      ga = ga || 1;
      var qtyFinal = qty / ga * cfreq;
      var cfqtyFormula = qty + ' / ' + ga;
      return [qtyFinal, cfqtyFormula];
    };

    Appc.changeQty2 = function() {
      
      var tmpE = $scope.editing;
      var qtyParts = Appc.calcQty2(tmpE.qty2, tmpE.qty_paper, tmpE.knum, tmpE.back, tmpE.ga);

      tmpE.toq_n = qtyParts[2];
      tmpE.toq = qtyParts[0] + '令' + qtyParts[1] + '束'; //製作數量
      tmpE.toq2 = tmpE.toq;
      tmpE.tos2 = tmpE.tos;
      tmpE.toqFormula = qtyParts[3]; 

      var cfqtyParts = Appc.calcCfqty(tmpE.qty2, tmpE.ga, tmpE.cfreq);


      tmpE.cfqty = cfqtyParts[0];
      tmpE.cfqtyFormula = cfqtyParts[1];
      tmpE.cfqty2 = tmpE.cfqty;
      tmpE.cfs2 = tmpE.cfs;

      $scope.editing = tmpE;
    };

    Appc.chooseItem = function(item){
      // console.log('choose', item);
      var tmpE = item;
      var qtyParts = Appc.calcQty2(tmpE.qty, tmpE.qty_paper, tmpE.knum, tmpE.back, tmpE.ga);
      //設定應訂類的預設值
      tmpE.qty2 = item.qty; //製作數量
       //計算面紙應訂數量
      tmpE.toq_n = qtyParts[2];
      tmpE.toq = qtyParts[0] + '令' + qtyParts[1] + '束'; //製作數量
      tmpE.toq2 = tmpE.toq;
      tmpE.tos2 = item.tos;
      tmpE.toqFormula = qtyParts[3];
      //計算紙材應訂數量
      var cfqtyParts = Appc.calcCfqty(tmpE.qty, tmpE.ga, tmpE.cfreq);
      tmpE.cfqty = cfqtyParts[0];
      tmpE.cfqtyFormula = cfqtyParts[1];
      tmpE.cfqty2 = tmpE.cfqty;
      tmpE.cfs2 = item.cfs;

      $scope.editing = tmpE;

    };

    Appc.closeEditing = function(){
      $scope.editing = null;
    };

    Appc.checkItems = function() {
        var resMsg = [], item;

        if(!$scope.editing.qty2 || !isFinite($scope.editing.qty2)) {
            resMsg.push('  - [製作數量] 請輸入數字');
        }

        if($scope.editing.cfqty2 && !isFinite($scope.editing.cfqty2)) {
            resMsg.push('  - [紙材實訂數量] 有輸入的話，請輸入數字。');
        }

        if($scope.editing.ga && !isFinite($scope.editing.ga) ) {
            resMsg.push('  - [軋盒] 有輸入的話，請輸入數字。');
        }

        return resMsg;
    }
    Appc.confirmEditing = function(){
      var resMsg = Appc.checkItems();
      if(resMsg.length > 0) {
          alert(resMsg.join('\n'));
          return false;
      }


      $scope.editing.isorderdone = 'X';
        var oParam = {
            item: angular.toJson($scope.editing)
        };
        $http({
                method:'POST',
                url:'/ct/admin/processorder/ajax_processorder_save',
                data: $.param(oParam),
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).
            then(function(res) {
                //success
                if(res.data == 'OK'){
                  //alert('訂單項目處理完成!');
                  Appc.closeEditing();
                }
                //location.href = '/ct/admin/processorder/index';
            }, function(res) {
                //error
            });

      
    };

    Appc.filterNotDone = function(item) {
      if(item.isorderdone == '') {
        return true;
      } else {
        return false;
      }
    };

    Appc.filterDone = function(item) {
      if(item.isorderdone == 'X') {
        return true;
      } else {
        return false;
      }
    };


}]);





</script>