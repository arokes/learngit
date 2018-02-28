<?php if (!defined('THINK_PATH')) exit();?><html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="initial-scale=1, user-scalable=0, minimal-ui">
<title><?php echo ($title); ?></title>
<link rel="stylesheet" href="../Public/css/ok_erp_style.css" type="text/css" />
<!-- Internet Explorer HTML5 enabling script: -->
		<!--[if IE]>
			<script src="http://swiftphp.org/Swift/includes/js/html5.js"></script>
			<style type="text/css">
				.clear {
					zoom: 1;
					display: block;
				}
			</style>
		<![endif]-->
<script type="text/javascript" src="../Public/js/jquery.js"></script>
<script type="text/javascript" src="../Public/laydate/laydate.js"></script>
<script type="text/javascript" src="../Public/js/ok_erp_js.js"></script>
<script type="text/javascript" src="../Public/js/reveal.js"></script>


<script type="text/javascript" >  
$(document).ready(function(){
						$("ul.subnav").parent().append("<span></span>"); //Only shows drop down trigger when js is enabled
						$("ul.topnav li a").hover(function() { //When trigger is clicked...
							//Following events are applied to the subnav itself (moving subnav up and down)
							$(this).parent().find("ul.subnav").slideDown('fast').show(); //Drop down the subnav on click
							$(this).parent().hover(function() {}, function(){
								$(this).parent().find("ul.subnav").slideUp('fast'); //When the mouse hovers out, move it back up
							});
						});
					});
</script>
<script type="text/javascript">
$(document).ready(function(){
	$(".tolink").click(function(){
		url=$(this).attr("url")
		window.location.replace(url);
	});

	$(".modify").click(function(){
		var url=$(this).attr("url");
		window.open(url,"_blank");
	});

	$(".deletebasic").click(function(){
		if(confirm("要删除吗？")){
			var url=$(this).attr("url");
			$.ajax({
				type:"GET",
				url:url,
				success:function(msg){
					alert(msg);
					window.location.reload();
				}
			});
		}
	});

	$("#screen").keyup(function(){
		str_screen = $("#screen").val().split(' ');
		if(!str_screen){
			$("tr[class]").show();
		}else{
			var contain='';
			for(var i=0;i<str_screen.length;i++){
				contain=contain+"[name*="+str_screen[i].toUpperCase()+"]";
			}
			$("tr[class]").hide();
			$("tr[class]"+contain).show();
		}
	});

	
	var str_screen=$("#screen").val().split(' ');
	if(str_screen.length!==0){
		var contain='';
			for(var i=0;i<str_screen.length;i++){
				contain=contain+"[name*="+str_screen[i].toUpperCase()+"]";
			}
			$("tr[class]").hide();
			$("tr[class]"+contain).show();
	}

	

	
});
</script>

</head>


</center>
<br />
<div id="container" >

<center>
<?php if(is_array($billdetail_po_result)): $i = 0; $__LIST__ = $billdetail_po_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><font><?php echo ($key); ?>--采购订单明细</font>
<table id="table-2">
<thead>
<th width="100px">厂商</th><th>批号</th><th>品号</th><th width="200px">品名</th><th>客户编号</th><th>库位</th><th>采购数量</th><th>已交量</th><th>退回量</th>
</thead>
<?php if(is_array($vo)): $i = 0; $__LIST__ = $vo;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo_1): $mod = ($i % 2 );++$i;?><tbody>
<tr><td width="100px"><?php echo (iconv("GBK","UTF-8",$vo_1["name"])); ?></td><td><?php echo (iconv("GBK","UTF-8",$vo_1["bat_no"])); ?></td><td><?php echo (iconv("GBK","UTF-8",$vo_1["prd_no"])); ?></td><td width="200px"><?php echo (iconv("GBK","UTF-8",$vo_1["prd_name"])); ?></td><td><?php echo (iconv("GBK","UTF-8",$vo_1["prd_mark"])); ?></td><td><?php echo ($vo_1["wh"]); ?></td><td><?php echo (round($vo_1["qty"],0)); ?></td><td><?php echo (round($vo_1["qty_ps"],0)); ?></td><td><?php echo (round($vo_1["qty_pre"],0)); ?></td></tr>
</tbody><?php endforeach; endif; else: echo "" ;endif; ?>
</table><?php endforeach; endif; else: echo "" ;endif; ?>

</center>



</div>
<center>

<h4 class="barlink">&copy;</h4>

</center>
</body>
</html>