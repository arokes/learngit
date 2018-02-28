<?php if (!defined('THINK_PATH')) exit();?><html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
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

<div><h2>绍兴美科科技库存查询</h2></div>
<div style="position: relative; width: 100%">
<div class="user"><b><?php echo (session('user_name')); ?></b>已登录&nbsp;<?php echo ($user_group); ?>&nbsp;<a href="__URL__/../Index/Logout">注销</a></div>
<center>
<div id="banner">
<nav>
	<ul class="topnav">
		<li><a href="/ok_erp/warehouse_index.php/SxmkStorage/SxmkStorage">查询库存</a></li>	
		<?php if(($_SESSION['user_level']) >= "2"): ?><li><a  href="/ok_erp/warehouse_index.php/SxmkAccountStatement/SxmkAccountStatement">对账单</a></li><?php endif; ?>
	</ul>
</nav>
</div>

<br />


<table id="table-5">
<thead>
<th>客户名称</th><th>日期</th><th>单号</th><th>单据种类</th><th>品名</th><th>单位</th><th>单价</th><th>数量</th><th>本位币</th><th>收款金额</th><th>未冲结余</th>
</thead>
<tbody>
<?php if(is_array($list_account)): $i = 0; $__LIST__ = $list_account;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr><td><?php echo (iconv('GBK','utf-8',$vo["name"])); ?></td><td><?php echo ($vo["ps_dd"]); ?></td><td><?php echo (iconv('GBK','utf-8',$vo["ps_no"])); ?></td><td><?php echo (iconv('GBK','utf-8',$vo["ps_id"])); ?></td><td><?php echo (iconv('GBK','utf-8',$vo["prd_name"])); ?></td><td><?php echo (iconv('GBK','utf-8',$vo["ut"])); ?></td><td><?php echo ($vo["up"]); ?></td><td><?php echo ($vo["qty"]); ?></td><td><?php echo ($vo["amt"]); ?></td><td><?php echo ($vo["amtn_bc"]); ?></td><td><?php echo ($vo["init"]); ?></td>
</tr><?php endforeach; endif; else: echo "" ;endif; ?>
</tbody>
</table>



<h4 class="barlink">&copy;</h4>

</body>
</html>