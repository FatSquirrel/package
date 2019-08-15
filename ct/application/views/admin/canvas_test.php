  <link rel="stylesheet" type="text/css" href="/css/imgareaselect-default.css" />


<img id="photo" src="/uploads/tmppic/2014-05-05/027fdc72-7f2f-4eed-829e-711a22854af7.jpg" />

  <script type="text/javascript" src="/js/jquery.imgareaselect.pack.js"></script>


<script type="text/javascript">
$(document).ready(function () {

$('#photo').imgAreaSelect({
    handles: true,
    aspectRatio: '1:1',
    onSelectEnd: function (img, selection) {
        if (!selection.width || !selection.height) {
            return;
        }
        console.log(selection);
        
    }
});
});
</script>
