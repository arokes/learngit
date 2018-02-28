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
<script type="text/javascript">
$(document).ready(function(){
	$(".tolink").click(function(){
		url=$(this).attr("url")
		window.location.replace(url);
	});

	var str_screen=$("#screen").val().split(' ');
	if(str_screen.length!==0){
		var contain='';
			for(var i=0;i<str_screen.length;i++){
				contain=contain+"[name*="+str_screen[i].toUpperCase()+"]";
			}
			//alert(contain);
			$("tr[class]").hide();
			$("tr[class]"+contain).show();
	}

	$("#screen").keyup(function(){
		str_screen = $("#screen").val().split(' ');
		if(!str_screen){
			$("tr[class]").show();
		}else{
			var contain='';
			for(var i=0;i<str_screen.length;i++){
				contain=contain+"[name*="+str_screen[i].toUpperCase()+"]";
			}
			//alert(contain);
			$("tr[class]").hide();
			$("tr[class]"+contain).show();
		}
	});
});
</script>

</head>

<body>
<div><h2>收汇认领管理</h2></div>
<div style="position: relative; width: 100%">
<div class="user"><b><?php echo (session('user_name')); ?></b>已登录&nbsp;<?php echo ($user_group); ?>&nbsp;<a href="__URL__/../Index/Logout">注销</a></div>
<center>

<div id="banner">
<nav>
				<ul class="topnav">
					
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
							<li><a href="/ok_erp/finance_index.php/CheckWarehouseAccount/FindMekaMM">核对仓库做账</a></li>
							<li><a href="/ok_erp/finance_index.php/SendGoods/SelectStock">查询库存</a></li>
							<li><a href="/ok_erp/finance_index.php/CheckTemporaryStock/SetParam">工厂公司对比</a></li>
						</ul>  
					</li>
					
					<li><a href="/ok_erp/finance_index.php/BillingData/UndownloadBillingData">开票资料</a></li>
					<li><a href="/ok_erp/finance_index.php/BillingData/FormatBillingDetailList">开票清单</a></li>
					<li><a>基础资料</a>
						<ul class="subnav">  
							<li><a href="/ok_erp/finance_index.php/Basic/Company">公司资料</a></li>
							<li><a href="/ok_erp/finance_index.php/Basic/Bank">银行资料</a></li>
						</ul>  
					</li>
					
					<li><a href="/ok_erp/finance_index.php/Suggest/Suggest">Suggest</a></li>  
	
			
				</ul>
			</nav>
</div>

</center>
<br />
<div id="container" >

<center>
<font>查询到汇认领明细</font>
<table id="table-5">
<thead><th>编号</th><th>销售方式</th><th>公司</th><th>银行</th><th>付款日期</th><th>汇款客户</th><th>汇款国家</th><th>币别</th><th>到账金额</th><th>业务员</th><th>开票员</th><thead>
<tbody>
<tr><td><?php echo ($data["0"]["id"]); ?></td><td><?php echo ($data["0"]["foreign_trade"]); ?></td><td><?php echo ($data["0"]["company_name"]); ?></td><td><?php echo ($data["0"]["bank_name"]); ?></td><td><?php echo ($data["0"]["pay_dd"]); ?></td><td><?php echo ($data["0"]["bank_cust"]); ?></td><td><?php echo ($data["0"]["country"]); ?></td><td><?php echo ($data["0"]["CUR_ID"]); ?></td><td><?php echo ($data["0"]["amount"]); ?></td><td><?php echo ($data["0"]["sal_name"]); ?></td><td><?php echo ($data["0"]["getlz_name"]); ?></td></tr></tbody>
</table>
<br />
<table id="table-5">
<thead>
<th>到账金额</th><th>手续费</th><th>订单号</th><th>订单PI总金额</th><th>是否报关</th><th>合同客户</th><th>开票客户</th><th>联系客户</th><th>罚款/扣款</th><th>备注</th><th></th></thead>
<tbody>
<?php if(is_array($data)): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
<td><?php echo ($vo["amount_apart"]); ?></td><td><?php echo ($vo["brokerage"]); ?></td><td><?php echo ($vo["so_no"]); ?></a></td><td><?php echo ($vo["so_pi"]); ?></td><td><?php echo ($vo["is_declare"]); ?></td><td><?php echo ($vo["contract_cust"]); ?></td><td><?php echo ($vo["lz_cust"]); ?></td><td><?php echo ($vo["connect_cust"]); ?></td><td><?php echo ($vo["debit"]); ?></td><td><?php echo ($vo["rem"]); ?></td><td><a target="_blank" href="../Account/SelectAccountDetail?so_id=<?php echo ($vo["so_id"]); ?>">查看台账</a></td></tr><?php endforeach; endif; else: echo "" ;endif; ?>
</tbody>
</table>
<a href="changeClaim?id=<?php echo ($data["0"]["id"]); ?>">修改</a>
<a href=<?php echo ($url_bak); ?>>返回</a>
<a href="deleteClaim?id=<?php echo ($data["0"]["id"]); ?>">删除</a>
</center>
<br />
<br />



</div>
<center>

<h4 class="barlink">&copy;</h4>

</center>
</body>
</html>