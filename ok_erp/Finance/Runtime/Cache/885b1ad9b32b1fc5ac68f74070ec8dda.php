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
<font><?php echo ($company_name); ?></font><br />
<font><?php echo ($bill_name); ?></font><br />
<?php if(is_array($billdetail_mf)): $i = 0; $__LIST__ = $billdetail_mf;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>单据日期:<b><?php echo ($vo["ps_dd"]); ?></b>&nbsp;&nbsp;&nbsp;&nbsp;单据号码:<b><?php echo ($vo["ps_no"]); ?></b>&nbsp;&nbsp;&nbsp;&nbsp;客户/厂商:<b><?php echo (iconv("GBK","utf-8",$vo["cus_name"])); ?></b><br />
立账方式:<b><?php echo ($zhang_name); ?></b>&nbsp;&nbsp;&nbsp;&nbsp;计税方式:<b><?php echo ($tax_name); ?></b>&nbsp;&nbsp;&nbsp;&nbsp;币别:<b><?php echo ($vo["cur_id"]); ?></b>&nbsp;&nbsp;&nbsp;&nbsp;汇率<b><?php echo (round($vo["exc_rto"],4)); ?></b><br />
生效日期:<b><?php echo ($vo["eff_dd"]); ?></b>&nbsp;&nbsp;&nbsp;&nbsp;录入日期:<b><?php echo ($vo["record_dd"]); ?></b>&nbsp;&nbsp;&nbsp;&nbsp;制单人:<b><?php echo (iconv("GBK","utf-8",$vo["usr_name"])); ?></b><?php endforeach; endif; else: echo "" ;endif; ?>
<table id="table-5">

<thead>
<th width="100px">来源单号</th><th>品号</th><th>客户编号</th><th width="250px">品名</th><th>数量</th><th>收/开票数量</th><th>退回数量</th><th>金额</th><th>未税金额</th><th>税额</th><th>记录</th>
</thead>
<tbody>
<?php if(is_array($billdetail_tf)): $i = 0; $__LIST__ = $billdetail_tf;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr><td width="100px"><?php echo (iconv("GBK","utf-8",$vo["bat_no"])); ?></td><td><?php echo (iconv("GBK","utf-8",$vo["prd_no"])); ?></td><td><?php echo (iconv("GBK","utf-8",$vo["prd_mark"])); ?></td><td width="250px"><?php echo (iconv("GBK","utf-8",$vo["prd_name"])); ?></td><td><?php echo (round($vo["qty"],0)); ?></td><td><?php echo (round($vo["qty_fp"],0)); ?></td><td><?php echo (round($vo["qty_rtn"],0)); ?></td><td><?php echo (round($vo["amt"],2)); ?></td><td><?php echo (round($vo["amtn_net"],2)); ?></td><td><?php echo (round($vo["tax"],2)); ?></td><td><a target="_blank"  href="../CheckTemporaryStock/FindBillingList?ps_id=<?php echo ($vo["ps_id"]); ?>&ps_no=<?php echo ($vo["ps_no"]); ?>&prd_no=<?php echo (urlencode(iconv('GBK','utf-8',$vo["prd_no"]))); ?>&company_name=<?php echo ($company_name); ?>">查看 收/开票</a></td></tr><?php endforeach; endif; else: echo "" ;endif; ?>
</tbody>
</table>
<br />
</center>



</div>
<center>

<h4 class="barlink">&copy;</h4>

</center>
</body>
</html>