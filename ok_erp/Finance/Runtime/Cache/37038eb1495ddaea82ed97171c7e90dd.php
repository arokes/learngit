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

	var str_screen=$("#screen").val().split(' ');
	if(str_screen.length!==0){
		var contain='';
			for(var i=0;i<str_screen.length;i++){
				contain=contain+"[name*="+str_screen[i].toUpperCase()+"]";
			}
			//alert(contain);
			$("tr[class]").hide();
			$("tr[class]"+contain).show();
	}

	$("#screen").keyup(function(){
		str_screen = $("#screen").val().split(' ');
		if(!str_screen){
			$("tr[class]").show();
		}else{
			var contain='';
			for(var i=0;i<str_screen.length;i++){
				contain=contain+"[name*="+str_screen[i].toUpperCase()+"]";
			}
			//alert(contain);
			$("tr[class]").hide();
			$("tr[class]"+contain).show();
		}
	});
});
</script>

</head>


</center>
<br />
<div id="container" >

<center>
<font><?php echo ($other['factory_name']); ?>&nbsp;<?php echo ($other['factory_lz_no']); ?>&nbsp;&nbsp;&nbsp;对比&nbsp;&nbsp;&nbsp;<?php echo ($other['company_name']); ?>&nbsp;<?php echo ($other['company_lz1_no']); ?></font><br />
<br />
<table id="table-2">
<thead>
<th>批号</th><th>品号</th><th>品名</th><th>编号</th><th>开票数量</th><th>开票金额</th><th>收票数量</th><th>收票金额</th>
</thead>
<?php $fc_sum_qty=0;$fc_sum_qmtn_net;$com_sum_qty;$com_sum_amtn_net; ?>
<?php if(is_array($list_result)): $i = 0; $__LIST__ = $list_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tbody>
<tr><td ><?php echo (iconv("GBK","UTF-8",$vo["bat_no"])); ?></td><td><?php echo (iconv("GBK","UTF-8",$vo["prd_no"])); ?></td><td><?php echo (iconv("GBK","UTF-8",$vo["prd_name"])); ?></td><td><?php echo (iconv("GBK","UTF-8",$vo["prd_mark"])); ?></td>
	<td><?php $fc_sum_qty=$fc_sum_qty+$vo['fc_qty']; echo ($vo["fc_qty"]); ?></td>
	<td><?php $fc_sum_amtn_net=$fc_sum_amtn_net+$vo['fc_amtn_net']; echo ($vo["fc_amtn_net"]); ?></td>
	<td><?php $com_sum_qty=$com_sum_qty+$vo['com_qty']; echo ($vo["com_qty"]); ?></td>
	<td><?php $com_sum_amtn_net=$com_sum_amtn_net+$vo['com_amtn_net']; echo ($vo["com_amtn_net"]); ?></td></tr>
</tbody><?php endforeach; endif; else: echo "" ;endif; ?>
<tr><td>合计</td><td></td><td></td><td></td><td><?php echo ($fc_sum_qty); ?></td><td><?php echo ($fc_sum_amtn_net); ?></td><td><?php echo ($com_sum_qty); ?></td><td><?php echo ($com_sum_amtn_net); ?></td></tr>
</table>
<br /><br />
</center>



</div>
<center>

<h4 class="barlink">&copy;</h4>

</center>
</body>
</html>