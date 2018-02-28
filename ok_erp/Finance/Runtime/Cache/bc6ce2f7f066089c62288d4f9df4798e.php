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

	$(".modify").click(function(){
		var url=$(this).attr("url");
		window.open(url,"_blank");
	});

	$(".deletebasic").click(function(){
		if(confirm("要删除吗？")){
			var url=$(this).attr("url");
			$.ajax({
				type:"GET",
				url:url,
				success:function(msg){
					alert(msg);
					window.location.reload();
				}
			});
		}
	});

	$("#screen").keyup(function(){
		str_screen = $("#screen").val().split(' ');
		if(!str_screen){
			$("tr[class]").show();
		}else{
			var contain='';
			for(var i=0;i<str_screen.length;i++){
				contain=contain+"[name*="+str_screen[i].toUpperCase()+"]";
			}
			$("tr[class]").hide();
			$("tr[class]"+contain).show();
		}
	});

	
	var str_screen=$("#screen").val().split(' ');
	if(str_screen.length!==0){
		var contain='';
			for(var i=0;i<str_screen.length;i++){
				contain=contain+"[name*="+str_screen[i].toUpperCase()+"]";
			}
			$("tr[class]").hide();
			$("tr[class]"+contain).show();
	}

	

	
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
							<li><a href="/ok_erp/finance_index.php/Exchange/DeletedExchange">已删汇款</a></li> 
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
<div id="myModal" class="reveal-modal">
	<form action="__URL__/Manwrite_offAccount" method="POST" >
		<input type="hidden" name="so_id" value=<?php echo ($account["so_id"]); ?> />
		<?php if(empty($data_tf)): ?>没有可以选择的订单!<?php else: ?>
		<table id="table-4" width="1000px">
		<thead>
		<th></th><th>订单号</th><th>币别</th><th>到账金额</th><th>手续费</th><th>订单PI金额</th><th>开票客户</th><th>备注</th><th>业务员</th>
		</thead>
		<tbody>
		<?php if(is_array($data_tf)): $i = 0; $__LIST__ = $data_tf;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo1): $mod = ($i % 2 );++$i;?><tr><td><input type="checkbox" value="id=<?php echo ($vo1["id"]); ?> and itm=<?php echo ($vo1["itm"]); ?>" name="tf_id[]" /></td><td><?php echo ($vo1["so_no"]); ?></td><td><?php echo ($vo1["CUR_ID"]); ?></td><td><?php echo ($vo1["amount_apart"]); ?></td><td><?php echo ($vo1["brokerage"]); ?></td><td><?php echo ($vo1["so_pi"]); ?></td><td><?php echo ($vo1["lz_cust"]); ?></td><td><?php echo ($vo1["rem"]); ?></td><td><?php echo ($vo1["sal_name"]); ?></td></tr><?php endforeach; endif; else: echo "" ;endif; ?>
		</tbody>
		</table>
		不选择收款,则单独核销台帐<br /><?php endif; ?>
		<a class="close-reveal-modal">&#215;</a>
		
		<input type="submit" value="强制核销" /><a class="close-reveal-modal">&#215;</a>
	</form>
</div>
<font>客户台帐</font>
<table id="table-5">
	<thead>
		<th>编号</th><th>客户名称</th><th>国家</th><th>订单号</th><th>币别</th><th>香港PI金额</th><th>国内PI金额</th><th>预交日期</th>
		<th>应收款</th><th>账期</th><th>预计打款日期</th><th>业务员</th><th width="200px">备注</th><th>核销</th>
	</thead>
	<tbody>
		<tr><td><?php echo ($account["so_id"]); ?></td><td><?php echo ($account["cust_name"]); ?></td><td><?php echo ($account["country"]); ?></td><td><?php echo ($account["so_no"]); ?></td><td><?php echo ($account["CUR_ID"]); ?></td>
		<td><?php echo ($account["HONGKONG_PI"]); ?></td>
		<td><?php echo ($account["CHINA_PI"]); ?></td><td><?php echo ($account["expect_sale"]); ?></td><td><?php echo ($account["receivable"]); ?></td><td><?php echo ($account["account_period"]); ?></td><td><?php echo ($account["recevable_dd"]); ?></td>
		<td><?php echo ($account["sal_name"]); ?></td><td width="200px"><?php echo ($account["rem"]); ?></td><td><?php echo ($account["write_off"]); ?></td>
		</tr>
	</tbody>
</table>
<?php if(empty($tf_exchange)): ?><a href="#" data-reveal-id="myModal" data-animation="none">
	手动核销</a>&nbsp;<a href="<?php echo ($url); ?>">返回</a><br />
<?php else: ?>
	<font>收汇明细</font>
	<table id="table-5">
	<thead>
	<th>币别</th><th>金额</th><th>罚款/扣款</th><th>备注</th><th>到账公司</th><th>到账银行</th><th>汇款客户</th><th>汇款国家</th><th>到账日期</th><th>汇款总金额</th><th></th>
	</thead>
	<tbody>
	<?php if(is_array($tf_exchange)): $i = 0; $__LIST__ = $tf_exchange;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr><td><?php echo ($vo["CUR_ID"]); ?></td><td><?php echo ($vo["amount_apart"]); ?></td><td><?php echo ($vo["debit"]); ?></td><td><?php echo ($vo["rem"]); ?></td><td><?php echo ($vo["company_snm"]); ?></td><td><?php echo ($vo["bank_snm"]); ?></td><td><?php echo ($vo["bank_cust"]); ?></td><td><?php echo ($vo["country"]); ?></td><td><?php echo ($vo["pay_dd"]); ?></td><td><?php echo ($vo["amount"]); ?></td><td><a target="_blank" href="../Claim/selectClaimDetail?id=<?php echo ($vo["id"]); ?>">查看汇款</a></td></tr><?php endforeach; endif; else: echo "" ;endif; ?>
	</tbody>
	</table>
	<?php if(($account["write_off"]) == "F"): ?><a href="__URL__/Write_offAccount?so_id=<?php echo ($account["so_id"]); ?>">核销</a> &nbsp;<a href="#" class="big-link" data-reveal-id="myModal">强制核销</a><?php else: ?>已核销<?php endif; ?>&nbsp;<a href="<?php echo ($url); ?>">返回</a><br /><?php endif; ?>	
</center>



</div>
<center>

<h4 class="barlink">&copy;</h4>

</center>
</body>
</html>