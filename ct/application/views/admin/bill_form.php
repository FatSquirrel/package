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
   <?php foreach($arrod as $k => $v):  ?>
      <?php echo $v['id'].'<br />' ?>
   <?php endforeach; ?>
  </div>
</div>
<script src="/js/angular.min.js"></script>
<script>
  angular.module('App', []).
  controller('AppController',['$scope','$http',function($scope,$http){
    //ddd
    var Appc = this;

    $scope.headers = <?php echo json_encode($arrod) ?>;

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

    Appc.confirmEditing = function(){
     return false;
        var oParam = {
            items: angular.toJson(sel)
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