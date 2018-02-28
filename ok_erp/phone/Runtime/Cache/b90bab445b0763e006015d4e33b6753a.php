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


</head>

<body>

</center>
<br />
<div id="container" >

<?php if(is_array($stock_data)): $i = 0; $__LIST__ = $stock_data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo_1): $mod = ($i % 2 );++$i; if(empty($vo_1)): $j++; else: ?>
<b><?php echo ($key); ?></b>
<?php if(is_array($vo_1)): $i = 0; $__LIST__ = $vo_1;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><table id="table-5">
<thead>
<th></th><th></th>
</thead>
<tr><td style="width:55px">订单号</td><td ><?php echo ($vo["bat_no"]); ?></td></tr>
<tr><td style="width:55px">品号</td><td ><?php echo ($vo["prd_no"]); ?></td></tr>
<tr><td style="width:55px">品名</td><td ><?php echo (iconv("GBK","utf-8",$vo["prd_name"])); ?></td></tr>
<tr><td style="width:55px">客户编号</td><td ><?php echo ($vo["prd_mark"]); ?></td></tr>
<tr><td style="width:55px">库存数</td><td ><?php echo (round($vo["stock"],2)); ?></td></tr>
<tr><td style="width:55px">入库</td><td ><?php echo (round($vo["pc"],2)); ?></td></tr>
<tr><td style="width:55px">收票</td><td ><?php echo (round($vo["pc_qty_fp"],2)); ?></td></tr>
<tr><td style="width:55px">出库</td><td ><?php echo (round($vo["sa"],2)); ?></td></tr>
<tr><td style="width:55px">开票</td><td ><?php echo (round($vo["sa_qty_fp"],2)); ?></td></tr>

</table><?php endforeach; endif; else: echo "" ;endif; endif; endforeach; endif; else: echo "" ;endif; ?>
<?php if(in_array(($j), explode(',',"3"))): ?>查询不到数据<?php endif; ?>



</div>
<center>

</center>
</body>
</html>