

var confirmwMsg = function (msg) {
    return confirm(msg);
}


var dt2dstr = function (d, delimiter) {
    if(delimiter === undefined) { delimiter = '/'; }
    return d.getFullYear() + delimiter + fillZero((d.getMonth()+1), 2) + delimiter + fillZero(d.getDate(), 2);
  }





var fillZero = function(n, zerocount) {
    
    return n.toLocaleString('en', {minimumIntegerDigits:zerocount,minimumFractionDigits:0,useGrouping:false})

}



var o = function(msg) { 
    console.log(msg);
}









