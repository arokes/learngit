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

<div><h2>出入库查询</h2></div>
<div style="position: relative; width: 100%">
<div class="user"><b><?php echo (session('user_name')); ?></b>已登录&nbsp;<?php echo ($user_group); ?>&nbsp;<a href="__URL__/../Index/Logout">注销</a></div>
<center>
<div id="banner">
<nav>
	<ul class="topnav">
		<li><a href="/ok_erp/warehouse_index.php/Logistics/InStorage">近期数量</a></li>
		<li><a  href="/ok_erp/warehouse_index.php/Logistics/IntervalStorage">区间数量</a></li>
					<!--
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


<h2>查询近期贸易公司出入库数据</h2>
<form action="" method="post">
出库入库:<select name="state" id="state"><option value="PC">入库</option><option value="SA">出库</option></select>
查询时间:<select name="date_min" id="date_min">
<?php if(is_array($date)): foreach($date as $key=>$vo): ?><option value="<?php echo ($vo); ?>"><?php echo ($key); ?></option><?php endforeach; endif; ?>
</select>
<input type="button" value="查询" url="SelectInStorage" id="select_storage">
</form>
<div id="storage"></div>



<h4 class="barlink">&copy;</h4>

</body>
</html>