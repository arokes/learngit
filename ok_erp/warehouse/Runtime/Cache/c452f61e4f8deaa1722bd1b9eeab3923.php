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
			prd_name_txt=$('#prd_name').val();
			var url=$(this).attr('url');
			$.post(url,{prd_name:prd_name_txt},function(result){
				$("#storage").html(result);
			});
		});

		$("#select_accountstatement").click(function(){
			$("#account").html("正在查询中,请稍后...");
			date_min_txt=$('#data_min').val();
			cus_no_txt=$('#cusno').val();
			var url=$(this).attr('url');
			$.post(url,{cusno:cus_no_txt,date_min:date_min_txt},function(result){
				$("#account").html(result);
			});
		});
	});
</script> 
<script type="text/javascript" src="../Public/laydate/laydate.js"></script>
<script type="text/javascript" src="../Public/js/ok_erp_js.js"></script> 
</head>
<body>

<br />



<table id="table-4" >
<thead>
<th>品号</th><th>品名</th><th>库存量</th><th>在途量</th>
</thead>
<tbody>
<?php if(is_array($storage_data)): $i = 0; $__LIST__ = $storage_data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr><td><?php echo (iconv('GBK','utf-8',$vo["prd_no"])); ?></td><td><?php echo (iconv('GBK','utf-8',$vo["name"])); ?></td><td><?php echo ($vo["qty"]); ?></td><td><?php echo ($vo["qty_on_way"]); ?></td></tr><?php endforeach; endif; else: echo "" ;endif; ?>
</tbody>

</table>



</body>
</html>