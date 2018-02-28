<?php if (!defined('THINK_PATH')) exit();?><html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
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
<script language="javascript" src="../Public/js/jquery.PrintArea.min.js"></script> 
<script language="javascript" src="../Public/js/jquery-barcode.js"></script> 
<script language="javascript" src="../Public/js/jquery.cookie.js"></script> 




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
$(document).ready(function() { 
	$("#back_id").click(function(){
  		if(confirm("确认返回去修改吗？")){
  			var data=$(this).attr('data');
			$.ajax({
				type:'POST',
				url:'SendBack',
				cache : false,
				traditional : true,
				data:{shipment_id : data},
				success:function(msg){
					window.location.reload();
				},
				error:function(){
					alert("修改失败");
				}
			});
		}
	});

	$("#send_id").click(function(){
  		if(confirm("确认发货完成吗？")){
  			var data=$(this).attr('data');
			$.ajax({
				type:'POST',
				url:'SendOut',
				cache : false,
				traditional : true,
				data:{shipment_id : data},
				success:function(msg){
					window.location.reload();
				},
				error:function(){
					alert("修改失败");
				}
			});
		}
	});

}); 

function doPrint() { 
	bdhtml=window.document.body.innerHTML; 
	sprnstr="<!--startprint-->"; 
	eprnstr="<!--endprint-->"; 
	prnhtml=bdhtml.substr(bdhtml.indexOf(sprnstr)+17); 
	prnhtml=prnhtml.substring(0,prnhtml.indexOf(eprnstr)); 
	window.document.body.innerHTML=prnhtml; 
	window.print();
	window.location.reload(); 
	
}
</script>

<script type="text/javascript" >  
	$(document).ready(function(){	
		$("#select_storage").click(function(){
			$("#storage").html("正在查询中,请稍后...");
			bat_txt=$("#bat_no").val();
			par_txt=$('#parameter').val();
			var url=$(this).attr('url');
			$.post(url,{bat_no:bat_txt,parameter:par_txt},function(result){
				$("#storage").html(result);
			});
		});

		$(".create_barcode").click(function(){
			var txt=$(this).attr('text');
			var company_name=$(this).attr('company_name');
			var prd_name="<table><td width=\"300px\">"+$(this).attr('prd_name')+"</td></table>";
			
			$("#company").html(company_name);
			$("#barcode_container").empty().barcode(txt, "code128",{barWidth:1, barHeight:50,showHRI:true});
			$("#prd_name").html(prd_name);
		});		
	});
</script> 
<script type="text/javascript" src="../Public/laydate/laydate.js"></script>
<script type="text/javascript" src="../Public/js/ok_erp_js.js"></script> 
</head>
<body>

<div><h2>仓库管理系统</h2></div>
<div style="position: relative; width: 100%">
<div class="user"><b><?php echo (session('user_name')); ?></b>已登录&nbsp;<?php echo ($user_group); ?>&nbsp;<a href="__URL__/../Index/Logout">注销</a></div>
<center>
<div id="banner">
<nav>
	<ul class="topnav">
		<li><a href="/ok_erp/warehouse_index.php/BarCode/PurchaseOrder">打印条码</a></li>
		<li><a href="/ok_erp/warehouse_index.php/SendGoods/WaitSendGoods">待发货物</a></li>
		<li><a href="/ok_erp/warehouse_index.php/SendGoods/SelectSend">查询发货单</a></li>
		<!--
		<li><a href="/ok_erp/warehouse_index.php/Logistics/IntervalStorage">区间数量</a></li>
					
					<li><a >查询管理</a>
						<ul class="subnav">
							<li><a  href="/ok_erp/index.php/Account/SelectAccount">查询台帐</a></li>
							<li><a  href="/ok_erp/index.php/Claim/selectClaim">查询认领</a></li>
							<li><a href="/ok_erp/index.php/SendGoods/SelectSend">查询发货单</a></li>
						</ul>
					</li>
					<li><a  href="/ok_erp/index.php/SendGoods/NewSend">新增发货单</a></li>
					<li><a  href="/ok_erp/index.php/SendGoods/ModifySendDetail">发货单明细</a></li>
					<li><a  href="/ok_erp/index.php/Suggest/add">建议</a></li>
					
					<li><a href="#">汇款管理</a>
						<ul class="subnav">  
							<li><a href="/ok_erp/admin_index.php/Exchange/NewExchange">新增汇款</a></li>  
							<li><a href="/ok_erp/admin_index.php/Exchange/ModifyExchange">修改汇款</a></li>
							<li><a href="/ok_erp/admin_index.php/Claim/NewClaim">认领汇款</a></li> 
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


<!--startprint-->
<h3><?php echo ($shipment_data["warehouse_no"]); ?>发货单明细</h3>
<table id="table-3">

<thead>
<th>进仓编号</th><th>发货单位</th><th>合同编号</th><th> 外箱整唛</th>
</thead>
<tbody><tr><td><?php echo ($shipment_data["warehouse_no"]); ?></td><td><?php echo ($shipment_data["company_name"]); ?></td><td><?php echo ($shipment_data["bat_no"]); ?></td>
<td rowspan="5"><img src="../../Tpl/Public/upload/<?php echo ($shipment_data["box_mark_savename"]); ?>" ></td>
</tr>
<th>发货时间</th><th>出货地点</th><th>跟单员</th>
<tr><td><?php echo ($shipment_data["send_time"]); ?></td><td><?php echo ($shipment_data["send_add"]); ?></td><td><?php echo ($shipment_data["sale_name"]); ?></td></tr>
<th>到货地址</th><th>进仓单</th><th>备注</th>
<tr><td><?php echo ($shipment_data["arrive_add"]); ?></td><td><a href="DownloadFile?filename=<?php echo ($shipment_data["warehouse_file_savename"]); ?>&showname=<?php echo ($shipment_data["warehouse_no"]); ?>"><?php echo ($shipment_data["warehouse_no"]); ?>.doc</a></td><td><?php echo ($shipment_data["rem"]); ?></td>
</tbody>
</table>

<?php if(empty($ship_detail_data)): else: ?>
<table id="table-3">
<thead>
<th>合同号</th><th>品号</th><th width="200px">品名</th><th>唛头&号数</th><th>发货数量</th><th>装箱规格</th><th>箱数</th><th>重量(KG)</th><th>体积(m³)</th><th id="date">生产厂商</th><th>备注</th>
</thead>
<tbody>
<?php if(is_array($ship_detail_data)): $i = 0; $__LIST__ = $ship_detail_data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo_detail): $mod = ($i % 2 );++$i;?><tr><td><?php echo ($vo_detail["bat_no"]); ?></td>
<td><?php echo ($vo_detail["prd_no"]); ?></td>
<td width="200px"><?php echo ($vo_detail["prd_name"]); ?></td>
<td><?php echo ($vo_detail["mark_no"]); ?></td>

<td><?php echo ($vo_detail["send_qty"]); ?></td>
<td><?php echo ($vo_detail["packing_spc"]); ?></td>
<td><?php echo ($vo_detail["packing_qty"]); ?></td>
<td><?php echo ($vo_detail["package_weight"]); ?></td>
<td><?php echo ($vo_detail["package_volume"]); ?></td>
<td><?php echo ($vo_detail["factory"]); ?></td>
<td><?php echo ($vo_detail["rem"]); ?></td></tr><?php endforeach; endif; else: echo "" ;endif; ?>
<tr><td>合计</td><td></td><td></td><td></td><td><?php echo ($shipment_data["totle_qty"]); ?></td><td></td><td><?php echo ($shipment_data["totle_packing_qty"]); ?></td><td><?php echo ($shipment_data["totle_weight"]); ?></td><td><?php echo ($shipment_data["totle_volume"]); ?></td></tr>
</tbody>
</table>
<!--endprint-->
<?php if(($shipment_data["up_id"]) == "T"): ?><button onClick="doPrint()">打印发货单</button>
<?php if(($shipment_data["cls_id"]) == "F"): ?>&nbsp;
<button id="back_id" data="<?php echo ($shipment_data["shipment_id"]); ?>">返审核</button>&nbsp;
<button id="send_id" data="<?php echo ($shipment_data["shipment_id"]); ?>" >发货完成</button><?php endif; endif; endif; ?>
<br />



<h4 class="barlink">&copy;</h4>

</body>
</html>