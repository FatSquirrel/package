<?php 
	//用來串在許多地方，免得常要重複輸入
	$controller = $this->uri->segment(2);
	
?>

<style type="text/css">
  

  .hide {
    display:none;
  }

  .show {
    display:block;
  }

</style>
<div ng-app="App">
  <div ng-controller="AppController as Appc" style="width:900px;">
    客戶：&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<select ng-options="c.id as (c.sname + ' - 請款日：' + c.payremark) for c in customers" ng-model="fk_customer">
                <option value="">~ 請選擇 ~</option>
            </select>
    <br />
    請款期間：<input type="date" ng-model="begindate" /> ~ <input type="date" ng-model="enddate" /> <br />
    請款備註：<input type="text" style="width:500px;" ng-model="remark" /><br />
    稅率：&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" ng-model="tax" style="width:50px;" maxlength="2" /><span style="color:#aaa;font-size:12px;">% * 請輸入%數。如輸入5：其內部將 x 0.05。或輸入0將不處理稅率/額</span>
    <br /><button ng-click="Appc.confirmEditing()" class="btn btn-primary">確定產生請款單</button> <button ng-click="Appc.confirmPreview()" class="btn">預覽</button> <br />
    <br />

    <table border="1" style="width:700px;">
      <caption style="font-weight:bold;font-size:22px;">列印記錄</caption>
      <tr style="background:#eee;">
        <td></td><td>列印日期</td><td>客戶</td><td>請款期間</td><td>稅率%</td>
      </tr>
      <?php foreach($data_list AS $row ):?>
      <tr>
        <td>
          <button ng-click="Appc.printThis('<?php echo $row['id']?>')" class="btn btn-success">開啟PDF</button>
          <button ng-click="Appc.exportExcel('<?php echo $row['id']?>')" class="btn btn-success">開啟EXCEL</button>
          <button ng-click="Appc.removeThis('<?php echo $row['id']?>')" class="btn btn-danger">刪除</button>

        </td>
        <td><?php echo $row['date'] ?></td>
        <td><?php echo $row['cn'] ?></td>
        <td><?php echo $row['begindate'] . '~' . $row['enddate'] ?></td>
        <td><?php echo $row['tax'] ?>
      </tr>
      <?php endforeach; ?>
    </table>
    <span style="font-size:12px;color:#aaa;">*保留依各請款結束日期起算六個月內之資料</span>
  </div>
</div>
<script src="/js/angular.min.js"></script>
<script>
  function dt2dstr(d) {
    return d.getFullYear() + '/' + (d.getMonth()+1) + '/' + d.getDate();
  }
  angular.module('App', []).
  controller('AppController',['$scope','$http',function($scope,$http){
    //ddd
    var Appc = this;
    $scope.customers = <?php echo json_encode($customers); ?>;
    $scope.tax = '0';
    Appc.printThis = function(id) {
      window.open('/ct/admin/bill/generate?id=' + id);
    };
    Appc.exportExcel = function(id) {
      window.open('/ct/admin/bill/excel?id=' + id);
    };
    Appc.removeThis = function(id) {
      if(!confirm('確定刪除嗎？')) {
        return;
      }
        var oParam = {
            id: id
        };
        
        $http({
                method:'POST',
                url:'/ct/admin/bill/remove',
                data: $.param(oParam),
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).
            then(function(res) {
                //success
                if(res.data == 'OK'){
                  alert('刪除成功');
                  location.href = '/ct/admin/bill'; 
                } else {
                  alert(res.data);
                }
            }, function(res) {
                //error
            });
    };

    Appc.removeHeader = function(item){
      if(confirm('確定刪除待出貨物？')) {
          var index = $scope.headers.indexOf(item);
          $scope.headers.splice(index, 1);     
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
    Appc.checkItems = function() {
        var resMsg = [], item;

        if($scope.begindate > $scope.enddate) {
            resMsg.push('  - [請款期間] 的結束日期早於開始日期。');
        }


        return resMsg;
    }
    Appc.confirmEditing = function(){
        var bstr = '',
            estr = '',
            tax = '';

        tax = $scope.tax || '0';
        if(!isFinite(tax)) {
          alert('稅率請輸入數字');
          return false;
        }


        if($scope.begindate == null || $scope.enddate == null || $scope.fk_customer == null) {
          alert('所有欄位都要輸入。');
          return false;
        }

        var resMsg = Appc.checkItems();
        if(resMsg.length > 0) {
            alert(resMsg.join('\n'));
            return false;
        }


        if($scope.begindate instanceof Date) {
          bstr = dt2dstr($scope.begindate);
        }
        if($scope.enddate instanceof Date) {
          estr = dt2dstr($scope.enddate);
        }
        var oParam = {
            customerid: $scope.fk_customer,
            begindate: bstr,
            enddate: estr,
            remark: $scope.remark || '',
            tax: tax || '0'
        };
        
        $http({
                method:'POST',
                url:'/ct/admin/bill/ajax_save',
                data: $.param(oParam),
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).
            then(function(res) {
                //success
                if(res.data == 'OK'){
                  alert('請款單建立成功，請點擊下面項目的開啟PDF按鈕開啟PDF。');
                  location.href = '/ct/admin/bill'; 
                } else {
                  alert(res.data);
                }
            }, function(res) {
                //error
            });
    };

    Appc.confirmPreview = function(){
        var bstr = '',
            estr = '';
        var tax = $scope.tax || '0';

        if($scope.begindate == null || $scope.enddate == null || $scope.fk_customer == null) {
          alert('所有欄位都要輸入。');
          return false;
        }

        var resMsg = Appc.checkItems();
        if(resMsg.length > 0) {
            alert(resMsg.join('\n'));
            return false;
        }


        if($scope.begindate instanceof Date) {
          bstr = dt2dstr($scope.begindate);
        }
        if($scope.enddate instanceof Date) {
          estr = dt2dstr($scope.enddate);
        }

        window.open('/ct/admin/bill/preview?cid=' + $scope.fk_customer + '&tax=' + tax + '&bstr=' + bstr + '&estr=' + estr + '&remark=' + ($scope.remark || ''));

        var oParam = {
            customerid: $scope.fk_customer,
            begindate: bstr,
            enddate: estr,
            remark: $scope.remark || ''
        };
        
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