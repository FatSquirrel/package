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

  .hide {
    display:none;
  }

  .show {
    display:block;
  }

</style>
<div ng-app="App">
  
<div ng-controller="AppController as Appc" style="width:900px;">
<div class="div-edit-modal" ng-class="{'hide': editing == null, 'show': editing != null}">
    <div class="div-edit-header">工作傳單</div>
    <div class="div-edit-litsec">
      產品名稱 {{editing.pname + '-' + editing.pdname}}
    </div>
    <div class="div-edit-litsec">
      <table style="width:100%;">
          <tr><td style="width:75px;">面紙廠商：</td><td style="width:150px;">{{editing.tv_name}}</td><td style="width:75px;">面紙數量：</td><td style="width:150px;">{{editing.toq2}}</td></tr>
          <tr><td>面紙尺寸：</td><td>{{editing.tos2}}</td><td>裁切尺寸：</td><td>{{editing.tcs}}</td></tr>
          <tr><td>印刷廠商：</td><td>{{editing.prtv_name}}</td><td>印刷數量：</td><td>{{editing.toq2 + ' = (足' + editing.toq_n + '+耗損)'}}</td></tr>
      </table>
    </div>

    <div class="div-edit-litsec">
      <table>
          <tr><td style="width:75px;">表面廠商：</td><td style="width:150px;">{{editing.sfcv_name}}</td><td style="width:75px;">表面處理：</td><td style="width:150px;">{{editing.sfc}}</td></tr>
          <tr><td>燙金廠商：</td><td>{{editing.heatv_name}}</td><td>燙金處理：</td><td>{{editing.heat}}</td></tr>
      </table>
    </div>
    <div class="div-edit-litsec">
      <table>
          <tr><td style="width:75px;">褙紙廠商：</td><td style="width:150px;">{{editing.pstv_name}}</td><td style="width:75px;">褙紙數量：</td><td style="width:150px;">{{editing.cfqty2}}</td></tr>
          <tr><td>紙材廠商：</td><td>{{editing.cfv_name}}</td><td>紙材數量：</td><td>{{editing.cfqty2}}</td></tr>
          <tr><td>紙材尺寸：</td><td>{{editing.cfs2}}</td><td></td><td></td></tr>
          <tr><td>軋盒廠商：</td><td>{{editing.gav_name}}</td><td>模數：</td><td>{{editing.ga}}</td></tr>
          <tr><td>成品數量：</td><td>{{editing.qty2}}</td><td></td><td></td></tr>
      </table>
    </div>
    <div class="div-edit-litsec">
      <table>
          <td style="width:75px;">糊盒廠商：</td><td style="width:150px;">{{editing.gluv_name}}</td><td style="width:75px;">糊盒數量：</td><td style="width:150px;">{{editing.qty2}}</td>
      </table>
    </div>
    <div class="div-edit-litsec">
      【備註】<br />
      <textarea style="width:466px;" ng-model="editing.ppremark">
      </textarea>
    </div>
    <div class="div-edit-litsec">
      <button ng-click="Appc.closeEditing()">返回</button>
      <button ng-click="Appc.confirmEditing()">確定</button>
    </div>
  </div>


    <div class="list-div">
      <ul ng-repeat="item in oditems | filter: Appc.filterNotDone" class="nav nav-pills nav-stacked">
        <li ng-click="Appc.chooseItem(item)" ng-class="{'active': editing.id == item.id}"><a href="#">{{item.cusname + '-' + item.pname + '-' + item.pdname}}</a></li>
      </ul>
    </div>

    <div class="list-div">
      <ul ng-repeat="item in oditems | filter: Appc.filterDone" class="nav nav-pills nav-stacked">
        <li><a href="#">{{item.cname + '-' + item.pname + '-' + item.pdname}}</a></li>
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
    $scope.oditems = <?php echo json_encode($arrod);?>;
    $scope.editing = null;    


    Appc.chooseItem = function(item){
      // // console.log('choose', item);
      // var tmpE = item;
      // var qtyParts = Appc.calcQty2(tmpE.qty, tmpE.qty_paper, tmpE.knum, tmpE.back, tmpE.ga);
      // //設定應訂類的預設值
      // tmpE.qty2 = item.qty; //製作數量
      //  //計算面紙應訂數量
      // tmpE.toq_n = qtyParts[2];
      // tmpE.toq = qtyParts[0] + '令' + qtyParts[1] + '束'; //製作數量
      // tmpE.toq2 = tmpE.toq;
      // tmpE.tos2 = item.tos;
      // tmpE.toqFormula = qtyParts[3];
      // //計算楞紙應訂數量
      // var cfqtyParts = Appc.calcCfqty(tmpE.qty, tmpE.ga);
      // tmpE.cfqty = cfqtyParts[0];
      // tmpE.cfqtyFormula = cfqtyParts[1];
      // tmpE.cfqty2 = tmpE.cfqty;
      // tmpE.cfs2 = item.cfs;

      $scope.editing = item;

    };

    Appc.closeEditing = function(){
      $scope.editing = null;
    };

    Appc.confirmEditing = function(){
      $scope.editing.isppdone = 'X';
        var oParam = {
            item: angular.toJson($scope.editing)
        };
        
        $http({
                method:'POST',
                url:'/ct/admin/processpp/ajax_processpp_save',
                data: $.param(oParam),
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).
            then(function(res) {
                //success
                if(res.data == 'OK'){
                  alert('工作傳單建立完成!');
                  Appc.closeEditing();
                }
                //location.href = '/ct/admin/processorder/index';
            }, function(res) {
                //error
            });

      
    };

    Appc.filterNotDone = function(item) {
      if(item.isppdone == '') {
        return true;
      } else {
        return false;
      }
    };

    Appc.filterDone = function(item) {
      if(item.isppdone == 'X') {
        return true;
      } else {
        return false;
      }
    };

}]);

</script>