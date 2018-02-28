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
<script language="javascript" src="../Public/js/jquery.PrintArea.min.js"></script> 
<script language="javascript" src="../Public/js/jquery-barcode.js"></script> 
<script language="javascript" src="../Public/js/jquery.cookie.js"></script> 





<script type="text/javascript" >  
	$(document).ready(function(){	
		$("#select_storage").click(function(){
			$("#storage").html("正在查询中,请稍后...");
			bat_txt=$("#bat_no").val();
			par_txt=$('#parameter').val();
			var url=$(this).attr('url');
			$.post(url,{bat_no:bat_txt,parameter:par_txt},function(result){
				$("#storage").html(result);
			});
		});

		$(".create_barcode").click(function(){
			var txt=$(this).attr('text');
			var company_name=$(this).attr('company_name');
			var prd_name="<table><td width=\"300px\">"+$(this).attr('prd_name')+"</td></table>";
			
			$("#company").html(company_name);
			$("#barcode_container").empty().barcode(txt, "code128",{barWidth:1, barHeight:50,showHRI:true});
			$("#prd_name").html(prd_name);
		});		
	});
</script> 
<script type="text/javascript" src="../Public/laydate/laydate.js"></script>
<script type="text/javascript" src="../Public/js/ok_erp_js.js"></script> 
</head>
<body>

<br />


<table id="table-4">
<thead>
<th>公司</th><th>批号</th><th>采购订单号</th><th>品号</th><th>品名</th><th>厂商名称</th><th></th>
</thead>
<tbody>
<?php if(is_array($PO_data)): $i = 0; $__LIST__ = $PO_data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if(empty($vo)): else: ?>
<?php if(is_array($vo)): $i = 0; $__LIST__ = $vo;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo1): $mod = ($i % 2 );++$i;?><tr><td><?php echo (iconv("GBK","utf-8",$vo1["company_name"])); ?></td><td><?php echo (iconv("GBK","utf-8",$vo1["bat_no"])); ?></td><td><?php echo (iconv("GBK","utf-8",$vo1["os_no"])); ?></td><td><?php echo (iconv("GBK","utf-8",$vo1["prd_no"])); ?></td><td><?php echo (iconv("GBK","utf-8",$vo1["prd_name"])); ?></td><td><?php echo (iconv("GBK","utf-8",$vo1["name"])); ?></td><td><input type="button" value="生成条码" text="<?php echo (iconv("GBK","utf-8",$vo1["bat_no"])); ?>@<?php echo ($vo1["prd_no"]); ?>" prd_name="<?php echo (iconv("GBK","utf-8",$vo1["prd_name"])); ?>"  company_name="<?php echo (iconv("GBK","utf-8",$vo1["company_name"])); echo ($vo1["cus_no"]); ?>" class="create_barcode" url="CreateBarCode"></td></tr><?php endforeach; endif; else: echo "" ;endif; endif; endforeach; endif; else: echo "" ;endif; ?>
</tbody>




</body>
</html>