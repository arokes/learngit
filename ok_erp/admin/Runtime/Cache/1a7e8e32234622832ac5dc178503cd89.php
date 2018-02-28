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


	$(".modify").click(function(){
		var url=$(this).attr('url');
		window.open(url,"_blank");
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
					
					<li><a href="#">台帐管理</a>
						<ul class="subnav">  
							<li><a href="/ok_erp/admin_index.php/Account/SelectAccount">查询台帐</a></li> 
							<li><a href="/ok_erp/admin_index.php/Account/WriteOff">核销</a></li> 
							<li><a href="/ok_erp/admin_index.php/Account/ReWriteOff">反核销</a></li>  
						</ul>  
					</li>
					<li><a href="#">到汇管理</a>
					<ul class="subnav">  
							<li><a href="/ok_erp/admin_index.php/Exchange/SelectExchange">查询到汇</a></li> 
							
					</ul>  
					</li>

					<li><a href="#">开票资料</a>
					<ul class="subnav">  
							<li><a href="/ok_erp/admin_index.php/BillingData/SelectBillingData">查询开票资料</a></li> 	
					</ul>  
					</li>
					<li><a href="#">帐号管理</a>
					<ul class="subnav">  
							<li><a href="/ok_erp/admin_index.php/Users/NewUsers">新建帐号</a></li> 
							<li><a href="/ok_erp/admin_index.php/Users/SelectUsers">查询帐号</a></li>
							<li><a href="/ok_erp/admin_index.php/Users/SelectUserLoad">查询登录</a></li>
							<li><a href="/ok_erp/admin_index.php/Users/DownloadOkerpFiles">下载网站</a></li>
							<li><a href="/ok_erp/admin_index.php/Users/ModifyUserPswd">修改密码</a></li>
							
					</ul>  
					</li>
					<li><a href="#">发出商品</a>
					<ul class="subnav">  
						<li><a href="/ok_erp/admin_index.php/SendGoods/listsendgoods">查询发货商品</a></li> 
						<li><a href="/ok_erp/admin_index.php/SendGoods/UploadFile">上传发货商品</a></li> 
						<li><a href="/ok_erp/admin_index.php/SendGoods/CheckSendGoods">核对发货商品</a></li> 
						<li><a href="/ok_erp/admin_index.php/SendGoods/UploadCheckStorageGoods">上传库存商品</a></li>
					</ul>  
					</li> 
					<li><a href="#">认领管理</a>
					<ul class="subnav">  
						<li><a href="/ok_erp/admin_index.php/Claim/NewClaim">新到汇款</a></li> 
						<li><a href="/ok_erp/admin_index.php/Claim/selectClaim">查询认领</a></li> 
					</ul>  
					</li>
					 <li><a href="#">其它</a>
					<ul class="subnav">  
					<li><a href="/ok_erp/admin_index.php/Suggest/read">查看建议</a></li>
					<li><a href="/ok_erp/admin_index.php/Suggest/showCalendar">查看日历</a></li>
					</ul>  
					</li>
				</ul>
			</nav>
</div>

</center>
<br />
<div id="container" >

<center>
<font>新到汇款</font>
<table id="table-5">
<thead>
<th>销售方式</th><th>公司</th><th>银行</th><th>到款日期</th><th>汇款人名字</th><th>汇款人国家</th><th>币别</th><th>到账金额</th></thead>
<tbody>
<?php if(is_array($NewClaim)): $i = 0; $__LIST__ = $NewClaim;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr><td><?php echo ($vo["foreign_trade"]); ?></td><td><?php echo ($vo["company_name"]); ?></td><td><?php echo ($vo["bank_name"]); ?></td><td><?php echo ($vo["pay_dd"]); ?></td><td><?php echo ($vo["bank_cust"]); ?></td><td><?php echo ($vo["country"]); ?></td><td><?php echo ($vo["CUR_ID"]); ?></td><td><?php echo ($vo["amount"]); ?></td><td><a href="__URL__/claimDetail?id=<?php echo ($vo["id"]); ?>">认领</a></td></tr><?php endforeach; endif; else: echo "" ;endif; ?>
</tbody>
</table>
<?php echo ($page); ?>
</center>



</div>
<center>

<h4 class="barlink">&copy;</h4>


</center>
</body>
</html>