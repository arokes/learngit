<?php if (!defined('THINK_PATH')) exit();?><html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><meta name="viewport" content="initial-scale=1, user-scalable=0, minimal-ui"><title><?php echo ($title); ?></title><link rel="stylesheet" href="../Public/css/ok_erp_style.css" type="text/css" /><!-- Internet Explorer HTML5 enabling script: --><!--[if IE]><script src="http://swiftphp.org/Swift/includes/js/html5.js"></script><style type="text/css">				.clear {
					zoom: 1;
					display: block;
				}
			</style><![endif]--><script type="text/javascript" src="../Public/js/jquery.js"></script><script type="text/javascript" src="../Public/laydate/laydate.js"></script><script type="text/javascript" src="../Public/js/ok_erp_js.js"></script><script type="text/javascript" src="../Public/js/reveal.js"></script><script type="text/javascript" >$(document).ready(function(){
						$("ul.subnav").parent().append("<span></span>"); //Only shows drop down trigger when js is enabled
						$("ul.topnav li a").hover(function() { //When trigger is clicked...
							//Following events are applied to the subnav itself (moving subnav up and down)
							$(this).parent().find("ul.subnav").slideDown('fast').show(); //Drop down the subnav on click
							$(this).parent().hover(function() {}, function(){
								$(this).parent().find("ul.subnav").slideUp('fast'); //When the mouse hovers out, move it back up
							});
						});
					});
</script><script type="text/javascript">$(document).ready(function(){
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
</script></head><body><div><h2>收汇认领管理</h2></div><div style="position: relative; width: 100%"><div class="user"><b><?php echo (session('user_name')); ?></b>已登录&nbsp;<?php echo ($user_group); ?>&nbsp;<a href="__URL__/../Index/Logout">注销</a></div><center><div id="banner"><nav><ul class="topnav"><li><a href="#">汇款管理</a><ul class="subnav"><li><a href="/ok_erp/finance_index.php/Exchange/NewExchange">新增汇款</a></li><li><a href="/ok_erp/finance_index.php/Exchange/ModifyExchange">修改汇款</a></li><li><a href="/ok_erp/finance_index.php/Claim/NewClaim">认领汇款</a></li></ul></li><li><a>查询管理</a><ul class="subnav"><li><a href="/ok_erp/finance_index.php/Claim/selectClaim">查询认领</a></li><li><a href="/ok_erp/finance_index.php/Account/SelectAccount">查询台帐</a></li><li><a href="/ok_erp/finance_index.php/BillingData/BillingDataSelect">查询开票资料</a></li><li><a href="/ok_erp/finance_index.php/CheckWarehouseAccount/FindMekaMM">核对仓库做账</a></li><li><a href="/ok_erp/finance_index.php/SendGoods/SelectStock">查询库存</a></li><li><a href="/ok_erp/finance_index.php/CheckTemporaryStock/SetParam">工厂公司对比</a></li></ul></li><li><a href="/ok_erp/finance_index.php/BillingData/UndownloadBillingData">开票资料</a></li><li><a href="/ok_erp/finance_index.php/BillingData/FormatBillingDetailList">开票清单</a></li><li><a>基础资料</a><ul class="subnav"><li><a href="/ok_erp/finance_index.php/Basic/Company">公司资料</a></li><li><a href="/ok_erp/finance_index.php/Basic/Bank">银行资料</a></li></ul></li><li><a href="/ok_erp/finance_index.php/Suggest/Suggest">Suggest</a></li></ul></nav></div></center><br /><div id="container" ><center><table id="table-4"><thead><th>销售方式</th><th>公司</th><th>银行</th><th>到款日期</th><th>汇款人名字</th><th>汇款人国家</th><th>币别</th><th>到账金额</th></thead><tbody><?php if(is_array($data)): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr><td><?php echo ($vo["foreign_trade"]); ?></td><td><?php echo ($vo["company_name"]); ?></td><td><?php echo ($vo["bank_name"]); ?></td><td><?php echo ($vo["pay_dd"]); ?></td><td><?php echo ($vo["bank_cust"]); ?></td><td><?php echo ($vo["country"]); ?></td><td><?php echo ($vo["CUR_ID"]); ?></td><td><?php echo ($vo["amount"]); ?></td></tr><?php endforeach; endif; else: echo "" ;endif; ?></tbody></table><br /><br /><br /><form action="getAccountData" method="post"><input type="hidden" name="id" value="<?php echo ($vo["id"]); ?>" />请选择您的名字:<select name="sal_name" id="btext"><option >-------</option><?php if(is_array($data_acc)): foreach($data_acc as $key=>$vo_acc): ?><option value="<?php echo ($vo_acc["sal_name"]); ?>"><?php echo ($vo_acc["sal_name"]); ?></option><?php endforeach; endif; ?></select><br /><br /><input type="submit" value="查询可用订单" />&nbsp;<a href="<?php echo ($url_back); ?>">返回</a><br /><br /><b>如果没有您的名字，请更新台账</b></form></center



</div><center><h4 class="barlink">&copy;</h4></center></body></html>