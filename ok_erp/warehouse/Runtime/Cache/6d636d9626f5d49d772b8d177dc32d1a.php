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
<script type="text/javascript" src="../Public/js/reveal.js"></script>
<script language="javascript" src="../Public/js/jquery.PrintArea.min.js"></script> 
<script language="javascript" src="../Public/js/jquery-barcode.js"></script> 
<script language="javascript" src="../Public/js/jquery.cookie.js"></script> 




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

<div><h2>仓库管理系统</h2></div>
<div style="position: relative; width: 100%">
<div class="user"><b><?php echo (session('user_name')); ?></b>已登录&nbsp;<?php echo ($user_group); ?>&nbsp;<a href="__URL__/../Index/Logout">注销</a></div>
<center>
<div id="banner">
<nav>
	<ul class="topnav">
		<li><a href="/ok_erp/warehouse_index.php/BarCode/PurchaseOrder">打印条码</a></li>
		<li><a href="/ok_erp/warehouse_index.php/SendGoods/WaitSendGoods">待发货物</a></li>
		<li><a href="/ok_erp/warehouse_index.php/SendGoods/SelectSend">查询发货单</a></li>
		<!--
		<li><a href="/ok_erp/warehouse_index.php/Logistics/IntervalStorage">区间数量</a></li>
					
					<li><a >查询管理</a>
						<ul class="subnav">
							<li><a  href="/ok_erp/index.php/Account/SelectAccount">查询台帐</a></li>
							<li><a  href="/ok_erp/index.php/Claim/selectClaim">查询认领</a></li>
							<li><a href="/ok_erp/index.php/SendGoods/SelectSend">查询发货单</a></li>
						</ul>
					</li>
					<li><a  href="/ok_erp/index.php/SendGoods/NewSend">新增发货单</a></li>
					<li><a  href="/ok_erp/index.php/SendGoods/ModifySendDetail">发货单明细</a></li>
					<li><a  href="/ok_erp/index.php/Suggest/add">建议</a></li>
					
					<li><a href="#">汇款管理</a>
						<ul class="subnav">  
							<li><a href="/ok_erp/admin_index.php/Exchange/NewExchange">新增汇款</a></li>  
							<li><a href="/ok_erp/admin_index.php/Exchange/ModifyExchange">修改汇款</a></li>
							<li><a href="/ok_erp/admin_index.php/Claim/NewClaim">认领汇款</a></li> 
						</ul>  
					</li>
					<li><a>查询管理</a>
						<ul class="subnav">  
							<li><a href="/ok_erp/admin_index.php/Claim/selectClaim">查询认领</a></li>
						</ul>  
					</li>
					<li><a>基础资料</a>
						<ul class="subnav">  
							<li><a href="/ok_erp/admin_index.php/Basic/Company">公司资料</a></li>
							<li><a href="/ok_erp/admin_index.php/Basic/Bank">银行资料</a></li>
						</ul>  
					</li>
					
							<li><a href="/ok_erp/admin_index.php/Suggest/Suggest">Suggest</a></li> 
						--> 
	
			
	</ul>
</nav>
</div>

<br />


<h3>查询发货单</h3>
<form action="SelectSendResult" method="post">
进仓单号：<input type="text" name="warehouse_no" /><br /><br />
业务员：&nbsp;&nbsp;<input type="text" name="sale_name" /><br />
<input type="submit" value="查询" />



<h4 class="barlink">&copy;</h4>

</body>
</html>