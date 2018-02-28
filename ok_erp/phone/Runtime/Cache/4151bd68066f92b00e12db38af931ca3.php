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

<script type="text/javascript">
	$(document).ready(function(){
		$("#selectstock").click(function(){
			$("#stock").html("正在查询中，请稍后...");
			url=$(this).attr('url');
			batno_txt=$("#batno").val();
			parameter_txt=$("#parameter").val();
			mark_txt=$("#mark").val();
			company_txt=$("#company").val();
			$.post(url,{batno:batno_txt,parameter:parameter_txt,mark:mark_txt,company:company_txt},function(result){
				$("#stock").html(result);
			});
		});
	});
</script>


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

</head>

<body>

<div><h2>手机用户界面</h2></div>
<div style="position: relative; width: 100%">
<div class="user"><b><?php echo (session('user_name')); ?></b>已登录&nbsp;<?php echo ($user_group); ?>&nbsp;<a href="__URL__/../Index/Logout">注销</a></div>
<center>

<div id="banner">
	<nav>
				<ul class="topnav">
					<li><a href="/ok_erp/phone_index.php/Stock/SelectStock">查询库存</a></li>  
					<!---
					<li><a href="#">汇款管理</a>
						<ul class="subnav">  
							<li><a href="/ok_erp/finance_index.php/Exchange/NewExchange">新增汇款</a></li>  
							<li><a href="/ok_erp/finance_index.php/Exchange/ModifyExchange">修改汇款</a></li>
							<li><a href="/ok_erp/finance_index.php/Claim/NewClaim">认领汇款</a></li> 
						</ul>  
					</li>
					<li><a>查询管理</a>
						<ul class="subnav">  
							<li><a href="/ok_erp/finance_index.php/Claim/selectClaim">查询认领</a></li>
							<li><a href="/ok_erp/finance_index.php/Account/SelectAccount">查询台帐</a></li>
							<li><a href="/ok_erp/finance_index.php/BillingData/BillingDataSelect">查询开票资料</a></li>
						</ul>  
					</li>

						
	--->
			
				</ul>
	</nav>
</div>

</center>
<br />
<div id="container" >

<center>
<form>
请输入批号:<input type="text" name="batno" id="batno" /><br />
请输入参数:<input type="text" name="parameter" id="parameter" /><br />
请输入特征:<input type="text" name="mark" id="mark" /><br />
<input type="button" value="查询" id="selectstock" url="SelectStockResult" />
</form>
<div id="stock"></div>
</center>



</div>
<center>

<h4 class="barlink">&copy;</h4>

</center>
</body>
</html>