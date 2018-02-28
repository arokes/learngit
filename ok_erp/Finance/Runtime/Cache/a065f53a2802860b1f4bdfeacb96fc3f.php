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
	$(".newwindows").click(function(){
		url=$(this).attr("url");
		window.open(url,'NEW','height=600, width=1000, top=0, left=25%, toolbar=no, menubar=no, scrollbars=no, resizable=no,location=no, status=no');
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
<font>美科缴库总数据与销售公司入库总数据对比</font>
<table id="table-4">
<p>浙江美科成品缴库</p>
<thead>
<th>批号</th><th>品号</th><th>品名</th><th>总缴库数量</th>
</thead>
<tbody>
<?php if(is_array($mekamm_list)): $i = 0; $__LIST__ = $mekamm_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr><td><?php echo ($vo["bat_no"]); ?></td><td><a href="" class="newwindows" url="FindMekaMMDetail?bat_no=<?php echo (urlencode($vo["bat_no"])); ?>&prd_no=<?php echo ($vo["prd_no"]); ?>"><?php echo ($vo["prd_no"]); ?></a></td><td><?php echo (iconv("GBK","utf-8",$vo["name"])); ?></td><td><?php echo ($vo["qty"]); ?></td></tr><?php endforeach; endif; else: echo "" ;endif; ?>
</tbody>
<?php $k=0; ?>
<?php if(is_array($storage_list)): $i = 0; $__LIST__ = $storage_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$company_list): $mod = ($i % 2 );++$i;?><table id="table-4">
<?php if(empty($company_list)): $k++; else: ?>
<p><?php echo ($key); ?>入库</p>
<thead>
<th>批号</th><th>品号</th><th style="width:400px">品名</th><th>客户编号</th><th>库位</th><th>总入库数量</th>
</thead>
<tbody>
<?php $company=$key; ?>
<?php if(is_array($company_list)): $i = 0; $__LIST__ = $company_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr><td><?php echo ($vo["bat_no"]); ?></td><td><a href="" url="FindWarehouseAccountDetail?mk_bat_no=<?php echo (urlencode($mekamm_list[0]['bat_no'])); ?>&mk_prd_no=<?php echo ($mekamm_list[0]['prd_no']); ?>&mk_qty=<?php echo ($mekamm_list[0]['qty']); ?>&sale_bat_no=<?php echo (urlencode($vo["bat_no"])); ?>&sale_prd_no=<?php echo ($vo["prd_no"]); ?>&sale_prd_mark=<?php echo ($vo["prd_mark"]); ?>&sale_qty=<?php echo ($vo["qty"]); ?>&company=<?php echo ($company); ?>" class="newwindows" /><?php echo (iconv("GBK","utf-8",$vo["prd_no"])); ?></a></td><td ><?php echo (iconv("GBK","utf-8",$vo["prd_name"])); ?></td><td><?php echo ($vo["prd_mark"]); ?></td><td><?php echo ($vo["wh"]); ?></td><td><?php echo ($vo["qty"]); ?></td><td><?php echo (iconv("GBK","utf-8",$vo["name"])); ?></td></tr><?php endforeach; endif; else: echo "" ;endif; ?>
</tbody><?php endif; ?>
</table><?php endforeach; endif; else: echo "" ;endif; ?>
<?php if(($k) == "4"): ?>销售公司没有数据<?php else: ?><b>点击对应品号链接查看明细</b><?php endif; ?><br />
<button type="button" onclick="javascript:history.back(-1);">返回</button>
</center>



</div>
<center>

<h4 class="barlink">&copy;</h4>

</center>
</body>
</html>