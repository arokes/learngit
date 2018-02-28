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
<script type="text/javascript" src="../Public/js/md5.js"></script>

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
		$("button").click(function(){
			txt=$("#btext").val();
			$.post("getAccountData",{sal_name:txt},function(result){
				$("#myModal").html(result);
	});
	});

	$("#select_stock").click(function(){
		$("#stock").html("正在查询中,请稍后...");
		url=$(this).attr('url');
		batno_txt=$("#bat_no").val();
		parameter_txt=$("#parameter").val();
		prd_no_txt=$("#prd_no").val();
		$.post(url,{bat_no:batno_txt,parameter:parameter_txt,prd_no:prd_no_txt},function(result){
			$("#stock").html(result);
		});
	});
	

	$(".delete_detail").click(function(){
  		if(confirm("确认要删除这条发货物品吗？")){
		var url=$(this).attr('url');
		var data=$(this).attr('data');
		$.ajax({
			type:'POST',
			url: url,
			cache : false,
			traditional : true,
			data:{field : data},
			success:function(msg){
				alert(msg);
				window.location.reload();
			},
			error:function(){
				alert("删除失败");
			}
		});
	}
});

		$(".delete_shipment").click(function(){
  		if(confirm("确认要删除吗？")){
			var url=$(this).attr('url');
			var data=$(this).attr('data');
			$.ajax({
				type:'POST',
				url: url,
				cache : false,
				traditional : true,
				data:{field : data},
				success:function(msg){
					alert(msg);
					window.location.href('NewSend');
				},
				error:function(){
					alert("删除失败");
				}
			});
		}
});

	$(".Send_Up").click(function(){
	  		if(confirm("确认要提交吗？提交后将由仓库审核")){
				var url=$(this).attr('url');
				var data=$(this).attr('data');
				$.ajax({
					type:'POST',
					url: url,
					cache : false,
					traditional : true,
					data:{field : data},
					success:function(msg){
						alert(msg);
						window.location.reload();
					},
					error:function(){
						alert("删除失败");
					}
				});
			}
	});
});
</script> 
<script type="text/javascript" src="../Public/laydate/laydate.js"></script>
<script type="text/javascript" src="../Public/js/ok_erp_js.js"></script> 
</head>

<body>

<div><h2>收汇认领</h2></div>
<div style="position: relative; width: 100%">
<div class="user"><b><?php echo ($_SESSION['name']); ?></b>已登录&nbsp;<?php echo ($user_group); ?>&nbsp;<a href="__URL__/../Index/Logout">注销</a></div>
<center>
<div id="banner">
<nav>
				<ul class="topnav">
					
					<li><a href="/ok_erp/index.php/Account/NewAccount">客户台帐</a></li>
					<li><a  href="/ok_erp/index.php/Claim/NewClaim">认领汇款</a></li>					
					<li><a >查询管理</a>
						<ul class="subnav">
							<li><a  href="/ok_erp/index.php/Account/SelectAccount">查询台帐</a></li>
							<li><a  href="/ok_erp/index.php/Claim/selectClaim">查询认领</a></li>
							<li><a  href="/ok_erp/index.php/SendGoods/SelectStock">查询库存</a></li>
							<?php if(($_SESSION['user_level']) >= "2"): ?><li><a  href="/ok_erp/index.php/BillingData/UploadBillingDataSelect">查询开票资料</a></li><?php endif; ?>
							
						</ul>
					</li>
					<li><a  href="/ok_erp/index.php/SendGoods/CheckSendGoods">发货商品</a></li>
					<li><a  href="/ok_erp/index.php/SendGoods/CheckStorageGoods">库存商品</a></li>
					<?php if(($_SESSION['user_level']) >= "2"): ?><li><a  href="/ok_erp/index.php/BillingData/UploadBillingData">开票资料</a></li>
					<li><a  href="/ok_erp/index.php/Basic/ChangePasswordCheck">修改密码</a></li><?php endif; ?>
					<li><a>建议</a>
						<ul class="subnav">
							<li><a  href="/ok_erp/index.php/Suggest/add">新增建议</a></li>
							<li><a  href="/ok_erp/index.php/Suggest/read">查看建议</a></li>
						</ul>
					</li>
					<!--
					<li><a href="#">汇款管理</a>
						<ul class="subnav">  
							<li><a href="/ok_erp/admin_index.php/Exchange/NewExchange">新增汇款</a></li>  
							<li><a href="/ok_erp/admin_index.php/Exchange/ModifyExchange">修改汇款</a></li>
							<li><a href="/ok_erp/admin_index.php/Claim/NewClaim">认领汇款</a></li> 
							<li><a  href="/ok_erp/index.php/Basic/SelectPrdt">查询成品品号</a></li>
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


<font>客户台帐</font>
<table id="table-5">
<thead><th>编号</th><th>客户名称</th><th>国家</th><th>订单号</th><th>币别</th><th>香港PI金额</th><th>国内PI金额</th><th>预交日期</th>
<th>应收款</th><th>账期</th><th>预计打款日期</th><th>业务员</th><th width="200px">备注</th><th>核销</th>
</thead>
<tbody>
<tr><td><?php echo ($account["so_id"]); ?></td><td><?php echo ($account["cust_name"]); ?></td><td><?php echo ($account["country"]); ?></td><td><?php echo ($account["so_no"]); ?></td><td><?php echo ($account["CUR_ID"]); ?></td><td><?php echo ($account["HONGKONG_PI"]); ?></td>
<td><?php echo ($account["CHINA_PI"]); ?></td><td><?php echo ($account["expect_sale"]); ?></td><td><?php echo ($account["receivable"]); ?></td><td><?php echo ($account["account_period"]); ?></td><td><?php echo ($account["recevable_dd"]); ?></td>
<td><?php echo ($account["sal_name"]); ?></td><td width="200px"><?php echo ($account["rem"]); ?></td><td><?php echo ($account["write_off"]); ?></td>
</tr>
</tbody>
</table>
<?php if(empty($tf_exchange)): ?><a href="__URL__/ModifyAccount?so_id=<?php echo ($account["so_id"]); ?>">修改</a>&nbsp;<a href="__URL__/DeleteAccount?so_id=<?php echo ($account["so_id"]); ?>">删除</a>&nbsp;<a href=<?php echo ($url); ?>>返回</a><br /><?php else: ?>
<font>收款认领明细</font>

<table id="table-5">
<thead>
<th>币别</th><th>金额</th><th>罚款/扣款</th><th>备注</th><th>到账公司</th><th>到账银行</th><th>汇款客户</th><th>汇款国家</th><th>到账日期</th><th>汇款总金额</th><th></th>
</thead>
<tbody>
<?php if(is_array($tf_exchange)): $i = 0; $__LIST__ = $tf_exchange;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr><td><?php echo ($vo["CUR_ID"]); ?></td><td><?php echo ($vo["amount_apart"]); ?></td><td><?php echo ($vo["debit"]); ?></td><td><?php echo ($vo["rem"]); ?></td><td><?php echo ($vo["company_snm"]); ?></td><td><?php echo ($vo["bank_snm"]); ?></td><td><?php echo ($vo["bank_cust"]); ?></td><td><?php echo ($vo["country"]); ?></td><td><?php echo ($vo["pay_dd"]); ?></td><td><?php echo ($vo["amount"]); ?></td><td><a target="_blank" href="../Claim/selectClaimDetail?id=<?php echo ($vo["id"]); ?>">查看汇款</a></td></tr><?php endforeach; endif; else: echo "" ;endif; ?>
</tbody>
</table>
<a href="__URL__/ModifyAccountAmount?so_id=<?php echo ($account["so_id"]); ?>">修改台账金额</a><br /><?php endif; ?>
<br /><br />



<h4 class="barlink">&copy;</h4>

</body>
</html>