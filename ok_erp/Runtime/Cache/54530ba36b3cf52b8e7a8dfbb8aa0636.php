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


<script type="text/javascript">
	$(document).ready(function(){
		
		$(".amount_apart").keyup(function(){
			var amount_sum=$("#amount_sum").val();
			var amount=0.0000;
			$(".amount_apart").each(function(){
				amount=amount+Number($(this).val());
			});
			var balance_amount=amount_sum-amount;
		$("#js_amount").html(amount.toFixed(2));
		$("#balance_amount").html(balance_amount.toFixed(2));

		});
		var amount_init=0.0000;
		var amount_sum=$("#amount_sum").val();
		$(".amount_apart").each(function(){
			amount_init=amount_init+Number($(this).val());
		});
		var balance_amount=amount_sum-amount_init;
		$("#js_amount").html(amount_init.toFixed(2));
		$("#balance_amount").html(balance_amount.toFixed(2));
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


<font>请填写到款认领详细信息</font>
<table id="table-4">

<thead><th>销售方式</th><th>公司</th><th>银行</th><th>到款日期</th><th>汇款人名字</th><th>汇款人国家</th><th>币别</th><th>到账金额</th></thead>
<tbody>
<tr><td><?php echo ($data_mf["foreign_trade"]); ?></td><td><?php echo ($data_mf["company_name"]); ?></td><td><?php echo ($data_mf["bank_name"]); ?></td><td><?php echo ($data_mf["pay_dd"]); ?></td><td><?php echo ($data_mf["bank_cust"]); ?></td><td><?php echo ($data_mf["country"]); ?></td><td><?php echo ($data_mf["CUR_ID"]); ?></td><td><?php echo ($data_mf["amount"]); ?></td></tr>
</tbody>
</table>
<br /><br />

<form action="claimResult" method="post">
收汇确认业务员或跟单:<b><?php echo ($sal_name); ?></b> &nbsp;&nbsp;提供开票业务员或跟单:<input type="text" name="getlz_name" id="cst" />
<input type="hidden" name="id" value="<?php echo ($data_mf["id"]); ?>" />
<input type="hidden" name="sal_name" value="<?php echo ($sal_name); ?>" />
<input type="hidden" id="amount_sum" name="amount_sum" value="<?php echo ($data_mf["amount"]); ?>" />
<input type="hidden" name="CUR_ID" value="<?php echo ($data_mf["CUR_ID"]); ?>" />
<input type="hidden" name="claim" value="1" />
<table id="table-5">
<thead>
<th>到账金额</th><th>手续费</th><th>订单号</th><th>剩余可收金额</th><th width="40px">报关</th><th>签约客户名称</th><th>开票客户名称</th><th>联系客户名称</th><th>罚款扣款情况</th><th>备注</th>
</thead>
<tbody>
<?php if(is_array($data_acc)): $i = 0; $__LIST__ = $data_acc;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
<td><input type="text" class="amount_apart" name="amount_apart[]" id="cst" onkeyup="(this.v=function(){this.value=this.value.replace(/[^-0-9.]/,'');}).call(this)" onblur="this.v();" /></td>
<td><input type="text" name="brokerage[]" id="cst" onkeyup="(this.v=function(){this.value=this.value.replace(/[^0-9.]/,'');}).call(this)" onblur="this.v();" /></td>
<td><input type="hidden" name="so_no[]" value="<?php echo ($vo["so_no"]); ?>" /><?php echo ($vo["so_no"]); ?></td>
<td><?php if(($vo['CHINA_PI']) == "0"): ?><input type="hidden" name="so_pi[]" value="<?php echo ($vo["HONGKONG_PI"]); ?>"><?php else: ?><input type="hidden" name="so_pi[]" value="<?php echo ($vo["CHINA_PI"]); ?>"><?php endif; echo ($vo["balance_amount"]); ?></td>
<td width="40px"><input type="radio" name="is_declare<?php echo ($i); ?>" checked="checked" value="是" /> 是<input type="radio" name="is_declare<?php echo ($i); ?>" value="否" /> 否</td>
<td><input type="hidden" name="contract_cust[]" value="<?php echo ($vo["cust_name"]); ?>" id="cust" /><?php echo ($vo["cust_name"]); ?></td>
<td><input type="text" name="lz_cust[]" /></td><td><input type="text" name="connect_cust[]" id="cust" /></td><td><input type="text" name="debit[]" /></td>
<td><input type="text" name="rem[]" value="<?php echo ($vo["rem"]); ?>" /></td>
</tr><?php endforeach; endif; else: echo "" ;endif; ?>
</tbody>
</table>
输入总金额:<font id="js_amount"></font>剩余金额:<font id="balance_amount"></font>
<br />
<input type="submit" value="认领" />&nbsp;<a href="<?php echo ($url_back); ?>">返回</a>
</form>

<br />	

<br />	
填写的到账金额可自行拆分,总和必须等于总到账金额
<br />	
预收款的话备注填写预计出口日期
<br />	
原则上要求签约客户名称和开票客户名称必须要一致。				
<br />																							
<b>如果银行汇款人和与公司开票客户名称不一致，请提供委托付款函</b>														
<br />	<br />	
签约客户名称----与”公司”签合同的客户名称
<br />	
开票客户名称---提供给夏露萍开发票的客户名称
<br />	
实际客户名称---业务员直接联系的客户名称

<br />	
罚款扣款情况和金额---填写详细,并提供邮件、证明材料给财务陆丽燕
<br />	
收汇情况的备注---注明该笔款项的具体明细，例如**订单的*%的定金，或尾款等情况
	
<br />										



<h4 class="barlink">&copy;</h4>

</body>
</html>