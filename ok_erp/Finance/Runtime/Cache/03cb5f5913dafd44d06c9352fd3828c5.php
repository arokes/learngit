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
<?php $j=0; ?>
<?php if(is_array($stock_data)): $i = 0; $__LIST__ = $stock_data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo_1): $mod = ($i % 2 );++$i; if(empty($vo_1)): $j++;$stock=0; else: ?>
<?php $company_name=$key; ?>
<b><?php echo ($key); ?>---存货明细账</b>
<table id="table-5">
<thead>
<th>类别</th>
<th>批号</th>
<th>品号</th>
<th width="250px">品名</th>
<th>客户编号</th>
<th>单号</th>
<th>单据日期</th>
<th>入库数量</th>
<th>收票数量</th>
<th>出库数量</th>
<th>开票数量</th>
<th>出库金额</th>
<th>结存数量</th>
</thead>
<tbody>
<?php if(is_array($vo_1)): $i = 0; $__LIST__ = $vo_1;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr><td><?php echo ($vo["ps_id"]); ?></td>
<td><?php echo (iconv("GBK","utf-8",$vo["bat_no"])); ?></td>
<td><?php echo (iconv("GBK","utf-8",$vo["prd_no"])); ?></td>
<td width="250px"><?php echo (iconv("GBK","utf-8",$vo["prd_name"])); ?></td>
<td><?php echo (iconv("GBK","utf-8",$vo["prd_mark"])); ?></td>
<td><a target="_blank" href="SelectPsBillDetail?ps_no=<?php echo ($vo["ps_no"]); ?>&ps_id=<?php echo ($vo["ps_id"]); ?>&company_name=<?php echo ($company_name); ?>"><?php echo ($vo["ps_no"]); ?></a></td>
<td><?php echo ($vo["eff_dd"]); ?></td>
<td><?php echo (round($vo["pc_qty"],0)); ?></td>
<td><?php if(in_array(($vo['ps_id']), explode(',',"PC,PB"))): ?><a target="_blank"  href="../CheckTemporaryStock/FindBillingList?ps_id=<?php echo ($vo["ps_id"]); ?>&ps_no=<?php echo ($vo["ps_no"]); ?>&prd_no=<?php echo (urlencode(iconv('GBK','utf-8',$vo["prd_no"]))); ?>&company_name=<?php echo ($company_name); ?>"><?php echo (round($vo["pc_fp_qty"],0)); ?></a><?php else: echo (round($vo["pc_fp_qty"],0)); endif; ?></td>
<td><?php echo (round($vo["sa_qty"],0)); ?></td>
<td><?php if(in_array(($vo['ps_id']), explode(',',"SA,SB"))): ?><a target="_blank"  href="../CheckTemporaryStock/FindBillingList?ps_id=<?php echo ($vo["ps_id"]); ?>&ps_no=<?php echo ($vo["ps_no"]); ?>&prd_no=<?php echo (urlencode(iconv('GBK','utf-8',$vo["prd_no"]))); ?>&company_name=<?php echo ($company_name); ?>"><?php echo (round($vo["sa_fp_qty"],0)); ?></a><?php else: echo (round($vo["sa_fp_qty"],0)); endif; ?></td>
<td><?php echo (round($vo["sa_amt"],2)); ?></td>
<td><?php $stock=$stock+$vo['pc_qty']-$vo['sa_qty']; echo ($stock); ?></td>
</tr><?php endforeach; endif; else: echo "" ;endif; ?>
</tbody>
</table><?php endif; endforeach; endif; else: echo "" ;endif; ?>
<?php if(in_array(($j), explode(',',"3"))): ?>查询不到数据<?php endif; ?>
<a href="javascript:history.back(-1);">返回</a>
<br />
</center>



</div>
<center>

<h4 class="barlink">&copy;</h4>

</center>
</body>
</html>