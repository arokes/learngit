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
	$(".resetpswd").click(function(){
		if(confirm("要重置密码吗？")){
			var userid=$(this).attr("userid");
			$.ajax({
				type:'POST',
				url: '__URL__/ResetUserPswd',
				data:{user_id: userid},
				success:function(msg){
					alert(msg);
				}
			});
		}
	});
	$(".disableuser").click(function(){
		var userid=$(this).attr("userid");
		$.ajax({
			type:'POST',
			url:'__URL__/DisableUser',
			data:{user_id:userid},
			success:function(msg){
				alert(msg);
				window.location.reload();
			}
		});
	});

	$(".enableuser").click(function(){
		var userid=$(this).attr("userid");
		$.ajax({
			type:'POST',
			url:'__URL__/EnableUser',
			data:{user_id:userid},
			success:function(msg){
				alert(msg);
				window.location.reload();
			}
		});
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


	$(".modify").click(function(){
		var url=$(this).attr('url');
		window.open(url,"_blank");
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
					
					<li><a href="#">台帐管理</a>
						<ul class="subnav">  
							<li><a href="/ok_erp/admin_index.php/Account/SelectAccount">查询台帐</a></li> 
							<li><a href="/ok_erp/admin_index.php/Account/WriteOff">核销</a></li> 
							<li><a href="/ok_erp/admin_index.php/Account/ReWriteOff">反核销</a></li>  
						</ul>  
					</li>
					<li><a href="#">到汇管理</a>
					<ul class="subnav">  
							<li><a href="/ok_erp/admin_index.php/Exchange/SelectExchange">查询到汇</a></li> 
							
					</ul>  
					</li>

					<li><a href="#">开票资料</a>
					<ul class="subnav">  
							<li><a href="/ok_erp/admin_index.php/BillingData/SelectBillingData">查询开票资料</a></li> 	
					</ul>  
					</li>
					<li><a href="#">帐号管理</a>
					<ul class="subnav">  
							<li><a href="/ok_erp/admin_index.php/Users/NewUsers">新建帐号</a></li> 
							<li><a href="/ok_erp/admin_index.php/Users/SelectUsers">查询帐号</a></li>
							<li><a href="/ok_erp/admin_index.php/Users/SelectUserLoad">查询登录</a></li>
							<li><a href="/ok_erp/admin_index.php/Users/DownloadOkerpFiles">下载网站</a></li>
							<li><a href="/ok_erp/admin_index.php/Users/ModifyUserPswd">修改密码</a></li>
							
					</ul>  
					</li>
					<li><a href="#">发出商品</a>
					<ul class="subnav">  
						<li><a href="/ok_erp/admin_index.php/SendGoods/listsendgoods">查询发货商品</a></li> 
						<li><a href="/ok_erp/admin_index.php/SendGoods/UploadFile">上传发货商品</a></li> 
						<li><a href="/ok_erp/admin_index.php/SendGoods/CheckSendGoods">核对发货商品</a></li> 
						<li><a href="/ok_erp/admin_index.php/SendGoods/UploadCheckStorageGoods">上传库存商品</a></li>
					</ul>  
					</li> 
					<li><a href="#">认领管理</a>
					<ul class="subnav">  
						<li><a href="/ok_erp/admin_index.php/Claim/NewClaim">新到汇款</a></li> 
						<li><a href="/ok_erp/admin_index.php/Claim/selectClaim">查询认领</a></li> 
					</ul>  
					</li>
					 <li><a href="#">其它</a>
					<ul class="subnav">  
					<li><a href="/ok_erp/admin_index.php/Suggest/read">查看建议</a></li>
					<li><a href="/ok_erp/admin_index.php/Suggest/showCalendar">查看日历</a></li>
					</ul>  
					</li>
				</ul>
			</nav>
</div>

</center>
<br />
<div id="container" >


<h4>查询用户列表</h4>
<center>
<table id="table-4">
<thead>
<th>编号</th><th>用户名</th><th>姓名</th><th>用户组</th><th>部门</th><th>用户等级</th><th>状态</th><th width="200px">操作</th>
</thead>
<tbody>
<?php if(is_array($user_data)): $i = 0; $__LIST__ = $user_data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr><td><?php echo ($vo["user_id"]); ?></td><td><?php echo ($vo["user_name"]); ?></td><td><?php echo ($vo["name"]); ?></td><td><?php echo ($vo["user_group_name"]); ?></td><td><?php echo ($vo["user_dep_no"]); ?></td><td><?php echo ($vo["user_level"]); ?></td><td><?php echo ($vo["disable"]); ?></td><td width="200px"><button class="resetpswd" userid="<?php echo ($vo["user_id"]); ?>">重置密码</button>&nbsp;&nbsp;<button class="modify" url="__URL__/ModifyUser?user_id=<?php echo ($vo["user_id"]); ?>">修改</button>&nbsp;&nbsp;<?php if(($vo["disable"]) == "0"): ?><button class="enableuser" userid="<?php echo ($vo["user_id"]); ?>">启用</button><?php else: ?><button class="disableuser" userid="<?php echo ($vo["user_id"]); ?>">禁用</button><?php endif; ?></td></tr><?php endforeach; endif; else: echo "" ;endif; ?>

</tbody>
</table>
<?php echo ($show); ?><a href="__URL__/SelectUsers">返回</a>
</center>



</div>
<center>

<h4 class="barlink">&copy;</h4>


</center>
</body>
</html>