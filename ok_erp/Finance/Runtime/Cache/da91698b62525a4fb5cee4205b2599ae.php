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
	$(".newwincheck").click(function(){
		document.form1.action=$(this).attr("url");
		//window.open('','NEW','height=600, width=1000, top=0, left=25%, toolbar=no, menubar=no, scrollbars=no, resizable=no,location=no, status=no');
		document.form1.target='_blank';
		document.form1.submit();

	});

	$(".newwindpo").click(function(){
		url=$(this).attr("url");
		window.open(url,'newwindow','height=600, width=1000, top=0, left=25%, toolbar=no, menubar=no, scrollbars=no, resizable=no,location=no, status=no');

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
<font>截止日期为<?php echo ($other_param["date_max"]); ?></font><br />
参数筛选:<input id="screen" name="screen" type="text" /> <br />
<font><?php echo ($other_param["factory_name"]); ?></font>
<form name="form1" method="post">
<table id="table-5">
<thead>
<th id="os_no">批号</th><th id="os_no">品号</th><th width="300px">品名</th><th>订单数量</th><th>缴库数量</th><th>出库数量</th><th>美科结存</th><th>开票数量</th><th>开票金额</th><th>对方暂估</th><th>发出商品</th><th>选择</th>
</thead>
<tbody>
<?php if(is_array($factory_list)): $i = 0; $__LIST__ = $factory_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr class="screen" name="<?php echo (iconv('GBK','UTF-8',$vo["prd_name"])); ?>"><td id="os_no"><a class="newwindpo" url="FindNakedDetail?bat_no=<?php echo (urlencode($vo["bat_no"])); ?>&prd_no=<?php echo ($vo["prd_no"]); ?>"><?php echo (iconv("GBK","UTF-8",$vo["bat_no"])); ?></a></td><td id="os_no"><a target="_blank" href="../SendGoods/SelectFactoryStockDetail?factory_db=<?php echo ($other_param['factory_db']); ?>&bat_no=<?php echo (urlencode($vo["bat_no"])); ?>&prd_no=<?php echo ($vo["prd_no"]); ?>"><?php echo ($vo["prd_no"]); ?></td><td width="300px"><?php echo (iconv("GBK","UTF-8",$vo["prd_name"])); ?></td><td><a class="newwindpo" url="FindSoDetail?bat_no=<?php echo (urlencode($vo["bat_no"])); ?>&prd_no=<?php echo ($vo["prd_no"]); ?>"><?php echo ($vo["so_qty"]); ?></a></td><td><?php echo ($vo["mm_qty"]); ?></td><td><?php echo ($vo["sa_qty"]); ?></td><td><?php echo ($vo['mm_qty']-$vo['sa_qty']); ?></td><td><?php echo ($vo["lz_qty"]); ?></td><td><?php echo ($vo["lz_amtn_net"]); ?></td><td><?php echo ($vo['mm_qty']-$vo['lz_qty']); ?></td><td><?php echo ($vo['sa_qty']-$vo['lz_qty']); ?></td>
<td><input type="radio" name="factory_param" id="factory_param" value="<?php echo ($other_param["factory_db"]); ?>;<?php echo ($vo["bat_no"]); ?>;<?php echo ($vo["prd_no"]); ?>"></td></tr><?php endforeach; endif; else: echo "" ;endif; ?>
</tbody>
</table>
<font><?php echo ($other_param["company_name"]); ?></font>
<table id="table-5">
<thead>
<th id="os_no">批号</th><th id="os_no">品号</th><th width="300px">品名</th><th>特征</th><th>入库数量</th><th>收票数量</th><th>收票金额</th><th>出库数量</th><th>开票数量</th><th>暂估数量</th><th>选择</th>
</thead>
<tbody>
<?php if(is_array($company_list)): $i = 0; $__LIST__ = $company_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr class="screen" name="<?php echo (iconv('GBK','UTF-8',$vo["prd_name"])); ?>"><td id="os_no"><a class="newwindpo" url="FindPoDetail?bat_no=<?php echo (urlencode($vo["bat_no"])); ?>&prd_no=<?php echo (urlencode($vo["prd_no"])); ?>"><?php echo (iconv("GBK","UTF-8",$vo["bat_no"])); ?></a></td><td id="os_no"><a target="_blank" href="../SendGoods/SelectStockDetailResult?bat_no=<?php echo (urlencode($vo["bat_no"])); ?>&prd_no=<?php echo (urlencode($vo["prd_no"])); ?>"><?php echo (iconv("GBK","UTF-8",$vo["prd_no"])); ?></a></td><td width="300px"><?php echo (iconv("GBK","UTF-8",$vo["prd_name"])); ?></td><td><a target="_blank" href="../SendGoods/SelectStockDetailResult?bat_no=<?php echo (urlencode($vo["bat_no"])); ?>&prd_no=<?php echo (urlencode($vo["prd_no"])); ?>&prd_mark=<?php echo (urlencode($vo["prd_mark"])); ?>"><?php echo (iconv("GBK","UTF-8",$vo["prd_mark"])); ?></td><td><?php echo ($vo["pc_qty"]); ?></td><td><?php echo ($vo["lz1_qty"]); ?></td><td><?php echo ($vo["lz1_amtn_net"]); ?></td><td><?php echo ($vo["sa_qty"]); ?></td><td><?php echo ($vo["lz_qty"]); ?></td><td><?php echo ($vo['pc_qty']-$vo['lz1_qty']); ?></td>
<td><input type="radio" name="company_param" id="company_param" value="<?php echo ($other_param["company_db"]); ?>;<?php echo ($vo["bat_no"]); ?>;<?php echo (iconv('GBK','UTF-8',$vo["prd_no"])); ?>;<?php echo (iconv('GBK','UTF-8',$vo["prd_mark"])); ?>"></td></tr><?php endforeach; endif; else: echo "" ;endif; ?>
</tbody>
</table>
<button type="button" class="newwincheck" url="FindTemporaryStockDetailResult" >对比</button>&nbsp;<button type="button" onclick="javascript:history.back(-1)">返回</button>
</form>
</center>



</div>
<center>

<h4 class="barlink">&copy;</h4>

</center>
</body>
</html>