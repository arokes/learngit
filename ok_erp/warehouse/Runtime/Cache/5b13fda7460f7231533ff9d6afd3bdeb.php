<?php if (!defined('THINK_PATH')) exit();?><html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

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
<script type="text/javascript" src="../Public/js/reveal.js"></script>

<script type="text/javascript" >  
	$(document).ready(function(){	
		$("#select_storage").click(function(){
			$("#storage").html("正在查询中,请稍后...");
			date_min_txt=$("#date_min").val();
			date_max_txt=$('#date_max').val();
			company_txt=$('#company').val();
			state_txt=$('#state').val();
			var url=$(this).attr('url');
			$.post(url,{date_min:date_min_txt,date_max:date_max_txt,company:company_txt,state:state_txt},function(result){
				$("#storage").html(result);
			});
		});
	});
</script> 
<script type="text/javascript" src="../Public/laydate/laydate.js"></script>
<script type="text/javascript" src="../Public/js/ok_erp_js.js"></script> 
</head>
<body>

<br />



<h3><?php echo ($company_name); ?></h3>
<?php if(empty($intervalstorage_data)): ?><h3>没有数据</h3><?php else: ?>
<table id="table-4">
<thead>
<th>批号</th><th>品号</th><th width="400px">品名</th><th>特征</th><th>日期</th><th>数量</th>
</thead>
<tbody>
<?php if(is_array($intervalstorage_data)): $i = 0; $__LIST__ = $intervalstorage_data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr><td><?php echo ($vo["bat_no"]); ?></td><td><?php echo ($vo["prd_no"]); ?></td><td width="400px"><?php echo (iconv('GBK','utf-8',$vo["prd_name"])); ?></td><td><?php echo ($vo["prd_mark"]); ?></td><td><?php echo ($vo["eff_dd"]); ?></td><td><?php echo ($vo["qty"]); ?></td></tr><?php endforeach; endif; else: echo "" ;endif; ?>
</tbody>
</table><?php endif; ?>



</body>
</html>