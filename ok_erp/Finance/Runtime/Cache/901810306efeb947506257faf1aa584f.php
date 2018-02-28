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
	$(".button").click(function(){
		if(confirm("确认下载完成了吗？")){
		var data=$(this).attr('bill_id');
		$.ajax({
			type:'POST',
			url: 'DownloadConfirmBillingData',
			cache : false,
			traditional : true,
			data:{id: data},
			
			success:function(msg){
				alert(msg);
				window.location.reload();
			},
			error:function(){
				alert("修改失败");
			}
		});
	}		
			
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
							<li><a href="/ok_erp/finance_index.php/SendGoods/SelectStock">查询公司库存</a></li>
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
<?php if(empty($billingdata_data)): ?><h2>没有新上传的开票资料了</h2>
<?php else: ?>
<h3>新上传开票资料</h3>
<table id="table-4">
<thead>
<th width="220px">合同协议号</th><th>业务员</th><th>报关公司</th><th>预录入单</th><th>内销合同</th><th>清关资料</th><th>发货清单</th><th>上传时间</th><th>修改时间</th><th>备注</th><th>确认下载</th>
</thead>
<tbody>
<?php if(is_array($billingdata_data)): $i = 0; $__LIST__ = $billingdata_data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr><td width="220px"><?php echo ($vo["contract_no"]); ?></td><td><?php echo ($vo["sale_name"]); ?></td><td><?php echo ($vo["company_name"]); ?></td><td><a href="__URL__/DownloadBillingData?filename=<?php echo ($vo["prerecorded_file"]); ?>&showname=<?php echo ($vo["contract_no"]); ?>预录入单">预录入单</a></td><td><a href="__URL__/DownloadBillingData?filename=<?php echo ($vo["contract_file"]); ?>&showname=<?php echo ($vo["contract_no"]); ?>内销合同">内销合同</a></td><td><a href="__URL__/DownloadBillingData?filename=<?php echo ($vo["clearance_file"]); ?>&showname=<?php echo ($vo["contract_no"]); ?>清关资料">清关资料</a></td>
<td><a href="__URL__/DownloadBillingData?filename=<?php echo ($vo["sendgoods_file"]); ?>&showname=<?php echo ($vo["contract_no"]); ?>发货清单">发货清单</a></td><td><?php echo ($vo["upload_date"]); ?></td><td><?php echo ($vo["change_date"]); ?></td><td><?php echo ($vo["rem"]); ?></td><td><button bill_id="<?php echo ($vo["ID"]); ?>" class="button">下载完成</button></td><td><a href="__URL__/DownloadAllBillingData?id=<?php echo ($vo["ID"]); ?>">下载全部</a></td>
</tr><?php endforeach; endif; else: echo "" ;endif; ?>
</tbody>
</table><?php endif; ?>
</center>



</div>
<center>

<h4 class="barlink">&copy;</h4>

</center>
</body>
</html>