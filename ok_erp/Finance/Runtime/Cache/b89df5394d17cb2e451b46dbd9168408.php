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
<h3>新增汇款</h3>
</center>
<form method="post" action="__URL__/NewExchangeResult" id="contact-form">
<label>销售方式:</label>
<select name="foreign_trade">
<option value="外销" >------外销------</option>
<option value="内销" >------内销------</option>
</select>
<label>公司:</label>
<select name="company_id">
<?php if(is_array($company)): $i = 0; $__LIST__ = $company;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$voc): $mod = ($i % 2 );++$i;?><option value="<?php echo ($voc["company_id"]); ?>" ><?php echo ($voc["company_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
</select>
<label>银行:</label>
<select name="bank_id">
<?php if(is_array($bank)): $i = 0; $__LIST__ = $bank;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vob): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vob["bank_id"]); ?>" ><?php echo ($vob["bank_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
</select>
<label>到款日期:</label>
<input type="text" onkeyup="(this.v=function(){this.value=this.value.replace(/[^0-9--]+/,'');}).call(this)" onblur="this.v();" name="pay_dd" onClick="laydate()" />
<label>汇款人:</label>
<input type="text" name="bank_cust" />
<label>汇款国家:</label>
<input type="text" name="country"  />
<label>币别:</label>
<select name="CUR_ID"  ><option value="USD">------USD------</option><option value="RMB">------RMB------</option><option value="EUR">------EUR------</option></select>
<label>到账金额:</label>
<input type="text" name="amount" onkeyup="(this.v=function(){this.value=this.value.replace(/[^0-9.]/,'');}).call(this)" onblur="this.v();"  /><br />
<label>备注:</label>
<input type="text" name="rem" value="第一笔" />
<input type="submit" value="新增" id="submit-button" />
<p></p>
</form>




</div>
<center>

<h4 class="barlink">&copy;</h4>

</center>
</body>
</html>