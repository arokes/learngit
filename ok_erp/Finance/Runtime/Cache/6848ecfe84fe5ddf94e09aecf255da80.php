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

</center>
<br />
<div id="container" >


<font>美科成品缴库数据明细</font>
<br />
<b>美科批号</b>:<?php echo ($mekamm_list[0]['bat_no']); ?> &nbsp;<b>美科品号</b>:<?php echo ($mekamm_list[0]['prd_no']); ?>&nbsp;
<b>美科品名</b>:<?php echo (iconv("GBK","utf-8",$mekamm_list[0]['name'])); ?>&nbsp;<b>美科总缴库数</b>:<?php echo ($qty['mk_qty']); ?><br />
<p>浙江美科成品缴库</p>
<table id="table-3" >
<thead>
<th>缴库单号</th><th>缴库日期</th><th>审核日期</th><th>缴库数量</th>
</thead>
<tbody>
<?php if(is_array($mekamm_list)): $i = 0; $__LIST__ = $mekamm_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr><td><?php echo ($vo["mm_no"]); ?></td><td><?php echo ($vo["mm_dd"]); ?></td><td><?php echo ($vo["eff_dd"]); ?></td><td><?php echo ($vo["qty"]); ?></td></tr><?php endforeach; endif; else: echo "" ;endif; ?>
</tbody> 
</table>



</div>
<center>

</center>
</body>
</html>