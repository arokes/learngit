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

<center>
<input type="hidden" name="id" value="<?php echo ($billingcompany_data["id"]); ?>">
<table id="table-4">
<thead>
<font>请核对公司信息</font>
<th>购方公司名称</th><th>购方银行帐号</th><th>复核人</th>
</thead>
<tbody>
<tr><td><?php echo ($billingcompany_data["Gfmc"]); ?></td><td><?php echo ($billingcompany_data["Gfyhzh"]); ?></td><td><?php echo ($billingcompany_data["Fhr"]); ?></td></tr>
</tbody>
<thead>
<th>购方公司税号</th><th>购方地址电话</th><th>收款人</th><th>备注</th>
</thead>
<tbody>
<tr><td><?php echo ($billingcompany_data["Gfsh"]); ?></td><td><?php echo ($billingcompany_data["Gfdzdh"]); ?></td><td><?php echo ($billingcompany_data["Skr"]); ?></td><td><?php echo ($billingcompany_data["Bz"]); ?></td></tr>
</tbody>
</table>
<br />
<p>请使用xls,xlsx文件格式上传开票清单</p>
<p>↓</p>
<table id="table-4">
<thead>
<th>名称</th><th>型号</th><th>单位</th><th>数量</th><th>单价(含税)</th><th>金额(含税)</th><th>税率</th><th>商品编码</th>
<tbody>
<tr><td>LED灯</td><td>*600*600 48W 白光</td><td>套</td><td>10</td><td>62</td><td>620</td><td>0.17</td><td>109042401</td></tr>
</tbody>
</table>
</center>



</div>
<center>

</center>
</body>
</html>