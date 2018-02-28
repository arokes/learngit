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

<center>
<font><?php echo (substr($date,0,4)); ?>年<?php echo (substr($date,5,2)); ?>月</font>
<table id="calendar">
<thead>
<th>星期日</th><th>星期一</th><th>星期二</th><th>星期三</th><th>星期四</th><th>星期五</th><th>星期六</th>
</thead>
<tbody>
<?php for($i=0;$i<count($list_nongli);){ echo "<tr>"; for($k = 0;$k < 7;$k++){ if($i==0){ for($j = 0;$j < $list_nongli[0]['week'];$j++){ echo "<td></td>"; $k++; } } echo "<td>".substr($list_nongli[$i]['date'],-2)."</td>"; $i++; } echo "</tr>"; } ?>
</tbody>
</table>
</center>



</div>
<center>

</center>
</body>
</html>