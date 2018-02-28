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
		window.open('','NEW','height=600, width=1000, top=0, left=25%, toolbar=no, menubar=no, scrollbars=no, resizable=no,location=no, status=no');
		document.form1.target='NEW';
		document.form1.submit();
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
<font><?php echo ($factory_name); ?>&nbsp;&nbsp;&nbsp;对比&nbsp;&nbsp;&nbsp;<?php echo ($company_name); ?></font><br />
<table id="table-4">
<thead>
<th>公司</th><th>批号</th><th>品号</th><th>品名</th><th>编号</th>
</thead>
<tbody>
<tr><td><?php echo ($factory_name); ?></td>
<td><?php echo ($check_result[0]['fc_bat_no']); ?></td><td><?php echo (iconv("GBK","UTF-8",$check_result[0]['fc_prd_no'])); ?></td><td><?php echo (iconv("GBK","UTF-8",$check_result[0]['fc_prd_name'])); ?></td><td><?php echo ($check_result[0]['fc_prd_mark']); ?></td>
</tr>
<tr><td><?php echo ($company_name); ?></td>
<td><?php echo ($company_result[0]['bat_no']); ?></td><td><?php echo (iconv("GBK","UTF-8",$company_result[0]['prd_no'])); ?></td><td><?php echo (iconv("GBK","UTF-8",$company_result[0]['prd_name'])); ?></td><td><?php echo (iconv("GBK","UTF-8",$company_result[0]['prd_mark'])); ?></td>
</tr>
</tbody>
</table>
<br />
<table id="table-4">
<thead>
<th>发票号码</th><th>备注</th><th>开票单号</th><th>开票日期</th><th>开票数量</th><th>开票金额</th><th>收票单号</th><th>收票日期</th><th>备注</th><th>收票数量</th><th>收票金额</th>
</thead>
<?php if(is_array($check_result)): $i = 0; $__LIST__ = $check_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tbody>
<tr><td><a target="_blank" href="FindInvnoResult?inv_no=<?php echo (urlencode(iconv('GBK','UTF-8',$vo["inv_no"]))); ?>"><?php echo ($vo["inv_no"]); ?></a></td><td><?php echo (iconv("GBK","UTF-8",$vo["fc_rem"])); ?></td><td><a target="_blank" href="FindLzDetail?lz_no=<?php echo ($vo["fc_lz_no"]); ?>&factory_name=<?php echo ($factory_name); ?>"><?php echo ($vo["fc_lz_no"]); ?></a></td><td><?php echo ($vo["fc_eff_dd"]); ?></td><td><?php echo ($vo["fc_qty"]); ?></td><td><?php echo ($vo["fc_amtn_net"]); ?></td><td><a target="_blank" href="FindLpDetail?lz_no=<?php echo ($vo["com_lz_no"]); ?>&company_name=<?php echo ($company_name); ?>"><?php echo ($vo["com_lz_no"]); ?></td><td><?php echo ($vo["com_eff_dd"]); ?></td><td><?php echo (iconv("GBK","UTF-8",$vo["com_rem"])); ?></td><td><?php echo ($vo["com_qty"]); ?></td><td><?php echo ($vo["com_amtn_net"]); ?></td></tr>
</tbody><?php endforeach; endif; else: echo "" ;endif; ?>
</table>
<br /><br />
<form name="form1" method="post">
<input type="hidden" name="factory_param" value="<?php echo ($factory_name); ?>;<?php echo (iconv('GBK','UTF-8',$check_result[0]['fc_prd_no'])); ?>" />
<input type="hidden" name="company_param" value="<?php echo ($company_name); ?>;<?php echo (iconv('GBK','UTF-8',$company_result[0]['prd_no'])); ?>" />
开票号码:<input type="text" name="factory_lz_no" />
&nbsp;&nbsp;&nbsp;
收票号码:<input type="text" name="company_lz1_no" />
&nbsp;&nbsp;&nbsp;
参数:<input type="text" name="param" />
<br /><br />
<button type="button" class="newwincheck" url="CheckLzResult">对比</button>


</form>
</center>



</div>
<center>

<h4 class="barlink">&copy;</h4>

</center>
</body>
</html>