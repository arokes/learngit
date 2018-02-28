<?php

class SendGoodsAction extends Action {
	
	public function _initialize(){
		$pos=strpos(session('user_group'),'sale');
		if($pos!==false){
			$this->user_group='销售部';
		}else{
			header("Content-type: text/html; charset=utf-8");
			redirect("http://".$_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT'],2,'<h1><center>未登录.页面跳转中...</center></h1>');
		}
	}

	public function NewSend(){
		$this->title="新增发货表单";
		$company=M('company');
		$company_data=$company->where("send_out='T'")->select();
		if($company_data){
			$this->company_data=$company_data;
			$this->display();
		}else{
			$this->error('数据库连接错误');
		}
	}

	function NewSendResult($warehouse_no=null){
		import('ORG.Net.UploadFile');
		$upload = new UploadFile();
		$upload->maxSize=3145728;
		$upload->savePath='Tpl/Public/upload/';
		if(!$upload->upload()){
			$this->error($upload->getErrorMsg());
		}else{
			$info = $upload->getUploadFileInfo();
		}
		$shipment=M('shipment');
		$shipment->create();
		$shipment->warehouse_file_savename=$info[0]['savename'];
		$shipment->box_mark_savename=$info[1]['savename'];
		
		$result=$shipment->add();
		if($result){
			$warehouse_no=I('warehouse_no','');
			$shipment_data=$shipment->where("warehouse_no='".$warehouse_no."'")->find();
			$this->success('新增发货单完成,请添加发货明细',"AddSendDetail?shipment_id=".$shipment_data['shipment_id']);
		}else{
			$this->error('添加失败');
		}

	}

	function AddSendDetail($shipment_id=0){
		$this->title="添加发货明细列表";
		$shipment_id=I('shipment_id');
		$shipment=M('shipment');
		$shipment_data=$shipment->find($shipment_id);
		$db=null;
		switch($shipment_data['company_name']){
			case "上虞欧科电器有限公司":
				$db="DB_OK01";
				break;
			case "绍兴美科电器进出口有限公司":
				$db="DB_MKJC";
				break;
			case "景德镇欧科电器有限公司":
				$db="DB_JXOK";
				break;
			default :
				$db="master";
				break;
		}
		if($shipment_data){
			if($shipment_data['up_id']=='T'){
				$this->error('此订单已提交不可更改');
			}else{
				$this->shipment_data=$shipment_data;
				$this->db=$db;
				$this->display();
			}
		}else{
			$this->error('没有这个发货单');
		}
	}

	function AddSendDetailResult($shipment_id=0){
		$this->title="保存发货清单明细";
		$shipment_id=I('shipment_id');
		$shipment=M('shipment');
		$shipment_data=$shipment->find($shipment_id);
		if($shipment_data['up_id']=='T'){
			$this->error('次订单已提交不可更改');
		}
		//添加表头总数据
		$data['totle_weight']=I('totle_weight');
		$data['totle_qty']=I('totle_qty');
		$data['totle_packing_qty']=I('totle_packing_qty');
		$data['totle_volume']=I('totle_volume');
		$shipment_result=$shipment->where("shipment_id=".$shipment_id)->save($data);
		//先删除全部旧表体数据
		$shipment_detail=M('shipment_detail');
		$shipment_detail->where("shipment_id=".$shipment_id)->delete();
		//再一次添加所有数据
		$bat_no=I('bat_no');
		$prd_no=I('prd_no');
		$prd_name=I('prd_name');
		$stock=I('stock');
		$mark_no=I('mark_no');
		$send_qty=I('send_qty');
		$packing_spc=I('packing_spc');
		$packing_qty=I('packing_qty');
		$package_weight=I('package_weight');
		$package_volume=I('package_volume');
		$factory=I('factory');
		$rem=I('rem');
		for($i=0;$i<count($bat_no);$i++){
			$data=array('shipment_id' => $shipment_id,'itm' =>$i+1,'bat_no'=>$bat_no[$i],'prd_no'=>$prd_no[$i],'prd_name'=>$prd_name[$i],'stock'=>$stock[$i],'mark_no'=>$mark_no[$i],'send_qty'=>$send_qty[$i],'packing_spc'=>$packing_spc[$i],'packing_qty'=>$packing_qty[$i],'package_weight'=>$package_weight[$i],'package_volume'=>$package_volume[$i],'factory'=>$factory[$i],'rem'=>$rem[$i]);
			$shipment_detail->create($data);
			$add_result=$shipment_detail->add();
		}
		if($add_result&&$shipment_result){
			unset($_SESSION['cart']);
			$this->success('保存成功',"SendDetail?shipment_id=".$shipment_id);
		}else{
			$this->error('保存失败');
		}
	}

	function DownloadFile($filename=null,$showname=null){
		header("Content-type:text/html;charset=utf-8");
		$filename="c:\\xampp/htdocs/ok_erp/tpl/Public/upload/".I('filename');
		$showname=I('showname').".doc";
		import('ORG.NET.Http');
		$Http=new Http();
		$Http->download($filename,$showname);
	}

	function SelectStock(){
		$this->title="查询订单库存";
		$this->display();
	}

	function SelectStockResult($bat_no=null){
		$this->title="查询订单库存";
		$bat_no=I('bat_no','');
		if(!$bat_no){
			$this->error('请输入订单号');
			exit;
		}
		$db=array('DB_OK01'=>'上虞欧科电器','DB_MKJC'=>'绍兴美科进出口','DB_JXOK'=>'景德镇欧科');
		$prd_mark=trim(I('prd_mark'));
		$parameter=I('parameter');
		$para_arr=explode(' ',trim($parameter));
		$stock=M('');
		//$select=null;
		$stock->db(1,"mssql://sa:mkdq@192.168.1.221/DB_OK01");
		foreach ($db as $key=>$company_name){
			$select="select bat_no,prd_no,prd_name,prd_mark,sum(case when ps_id='PC' then isnull(qty,0) when ps_id='PB' then isnull(-qty,0) end) pc,sum(case when ps_id='SA' then isnull(qty,0) when ps_id='SB' then isnull(-qty,0) end) sa,sum(case when ps_id='PC' then isnull(qty,0) when ps_id='SA' then isnull(-qty,0) when ps_id='PB' then isnull(-qty,0) when ps_id='SB' then isnull(qty,0) else 0 end) stock from ".$key.".dbo.tf_pss where upper(bat_no) like upper('%".$bat_no."%') and upper(prd_mark) like upper('%".$prd_mark."%')";
			for($i=0;$i<count($para_arr);$i++){
				$select_add=" and upper(prd_name) like upper('%".$para_arr[$i]."%')";
				$select=$select.$select_add;
			}
			$select_group="group by bat_no,prd_no,prd_name,prd_mark";
			
			$select=$select.$select_group;
			$stock_data[$company_name]=$stock->query(iconv("UTF-8","GBK",$select));
		}
		if($stock_data){
			$this->stock_data=$stock_data;
			$this->display();
		}else{
			echo "没有数据";
		}
	}

	function SelectStockDetail(){
		$this->title="查询存货明细帐";
		$this->display();
	}

	function SelectStockDetailResult($bat_no=null,$prd_no=null){
		$this->title="查询存货明细帐";
		$bat_no=trim(I('bat_no'));
		$prd_no=trim(I('prd_no'));
		$prd_mark=trim(I('prd_mark'));
		if(!$bat_no || !$prd_no){
			echo "请输入批号及品号";
			exit;
		}
		$db=array('DB_OK01'=>'上虞欧科电器','DB_MKJC'=>'绍兴美科进出口','DB_JXOK'=>'景德镇欧科');
		$stock=M('');
		$stock->db(1,"mssql://sa:mkdq@192.168.1.221/DB_OK01");
		foreach ($db as $key=>$company_name){
			$select =iconv("UTF-8","GBK","select case when ps_id='SA' then '销售出库' when ps_id='SB' then '销售退回' when ps_id='PC' then '采购入库' when ps_id='PB' then '采购退回' end ps_id,bat_no,prd_no,prd_name,prd_mark,ps_no,convert(varchar,eff_dd,023) eff_dd,isnull(case when ps_id='PC' then qty when ps_id='PB' then -qty end,0) pc_qty,isnull(case when ps_id='SA' then qty when ps_id='SB' then -qty end,0) sa_qty,case when ps_id='SA' then amt when ps_id='SB' then -AMT end sa_amt from ".$key.".dbo.tf_pss where upper(bat_no) like upper('%".$bat_no."%') and prd_no like '%".$prd_no."%' and prd_mark like '".$prd_mark."' and (ps_id= 'SA' or ps_id='SB' or ps_id='PC' or ps_id='PB') order by prd_no,prd_mark,eff_dd,ps_id ");
			$stock_data[$company_name]=$stock->query($select);
		}
		if($stock_data){
			$this->stock_data=$stock_data;
			$this->display();
		}else{
			echo "没有数据";
		}
	}

	function AddDetail(){
		//ajax  添加发货明细记录
		load("@.functions");
		$shipment_data=I('shipment_data','');
		$j=0;
		if($shipment_data){
			$car_data=null;
			for ($i=0;$i<count($shipment_data);$i++){
				$line=htmlspecialchars_decode($shipment_data[$i]);
				$str=explode('&?!',$line);
				if(addItem($str[0],$str[1],$str[2],$str[3],$str[4])){
					$j++;
				}
			}
			echo "已添加".$j."条记录。发货单中共有".count(all())."条记录"; 
		}else{
			echo "请选择发货物品";
		}
	}

	function ModifySendDetail($shipment_id=0){
		$this->title="生成发货清单";
		$shipment_id=I('shipment_id',0);
		$shipment=M('shipment');
		$shipment_data=$shipment->find($shipment_id);
		if($shipment_data){
			if($shipment_data['up_id']=='T'){
				$this->error('此订单已被提交不可更改');
			}
			$this->shipment_data=$shipment_data;
			$shipment_detail=M('shipment_detail');
			$ship_detail_data=$shipment_detail->where("shipment_id=".$shipment_id)->select();
			$this->ship_detail_data=$ship_detail_data;
			$this->cart_data=$_SESSION['cart'];
			$this->display();
		}else{
			$this->error('没有这个发货单');
		}
	}

	function ModifyShipment($shipment_id=0){
		$this->title="修改发货单";
		$shipment_id=I('shipment_id');
		$shipment=M('shipment');
		$shipment_data=$shipment->find($shipment_id);
		$company=M('company');
		$company_data=$company->select();
		if($shipment_data){
			if($shipment_data['up_id']=='T'){
				$this->error('此订单已被提交不可更改');
			}
			$this->shipment_data=$shipment_data;
			$this->company_data=$company_data;
			$this->display();
		}else{
			$this->error('找不到发货单号');
		}
	}

	function UnSetCart($key=null){
		load("@.functions");
		$key=I('field');
		delItem($key);
	}

	function DeleteDetailItem(){
		$field=I('field');
		$str=explodeed ('&',htmlspecialchars_decode($field));
		$data['shipment_id']=$str[0];
		$data['itm']=$str[1];
		$shipment_detail=M('shipment_detail');
		$shipment=M('shipment');
		$shipment_data=$shipment->find($data['shipment_id']);
		if($shipment_data['up_id']=='T'){
			echo '此订单已被提交不可更改';
			exit;
		}
		$result=$shipment_detail->where($data)->delete();
		if($result){
			echo "删除成功";
		}
	}
	
	function SelectSend(){
		$this->title="查询发货单";
		$this->display();
	}

	function SelectSendResult(){
		$this->title="查询发货单结果";
		import('ORG.Util.Page');
		$warehouse_no=I('warehouse_no')?I('warehouse_no'):'%';
		$sale_name=I('sale_name','%');
		$shipment=M('shipment');
		$condition['warehouse_no']=array('like',$warehouse_no);
		$condition['sale_name']=array('like',$sale_name.'%');
		$count=$shipment->where($condition)->count();
		$Page=new Page($count,15);
		$show = $Page -> show();
		$list=$shipment->where($condition)->limit($Page->firstRow.','.$Page->listRows)->select();
		if($list){
			$this->assign('list',$list);
			$this->assign('page',$show);
			$this->display();
		}else{
			$this->error('查询不到数据');
		}
	}

	function SendDetail($shipment_id=0){
		$this->title="发货单明细数据";
		$shipment_id=I('shipment_id');
		$shipment=M('shipment');
		$shipment_data=$shipment->find($shipment_id);
		$shipment_detail=M('shipment_detail');
		$ship_detail_data=$shipment_detail->where("shipment_id=".$shipment_id)->select();
		if($shipment_data){
			$this->shipment_data=$shipment_data;
			$this->ship_detail_data=$ship_detail_data;
			$this->display();
		}else{
			$this->error('没有这个发货单');
		}
	}

	function SendUp($shipment_id=0){
		$this->title="提交";
		$shipment_id=I('field');
		$shipment=M('shipment');
		$data['up_id']='T';
		$result=$shipment->where("shipment_id=".$shipment_id)->save($data);
		if($result){
			echo "提交完成";
		}else{
			echo "提交失败";
		}
	}
	
	function DeleteShipment($shipment_id=0){
		$this->title="删除发货单";
		$shipment_id=I('field');
		$shipment=M('shipment');
		$shipment_data=$shipment->find($shipment_id);
		if($shipment_data['up_id']=='T'){
				$this->error('此订单已被提交不可更改');
			}
		$result=$shipment->delete($shipment_id);
		if($result){
			echo "删除成功";
		}else{
			echo "删除失败";
		}
	}

	function CheckSendGoods(){
		$this->title="核对发出商品";
		$sendgoods=M('sendgoods');
		$sendgoods_data=$sendgoods->order("company_name,so_no")->where("confirm=0")->select();
		$this->sendgoods_data=$sendgoods_data;
		$this->display();
		
	}

	function CheckSendGoodsDetail(){
		$this->title="核对发出商品明细";
		$so_no =I('so_no');
		//$str_so_no=substr($so_no,0,2);
		$sendgoods=M('sendgoods');
		$select="select * from sendgoods where confirm=0 and so_no like '".$so_no."%'";
		$sendgoods_data=$sendgoods->query($select);
		if($sendgoods_data){
			$this->sendgoods_data=$sendgoods_data;
			$this->display();
		}else{
			$this->error("不知道发生了什么,请刷新重试");
		}
	}

	function CheckSendGoodsDetailResult(){
		$this->title="核对发出商品明细结果";
		$id=I('id');
		$rem=I('rem');
		$check=I("is_check");
		$sale_name=I('sale_name');
		if(!$sale_name){
			$this->error('请填写业务员名字');
		}
		$sendgoods=M('sendgoods');
		for ($i=0;$i<count($id);$i++){
			$data['check']=$check[$i];
			$data['sale_name']=$sale_name;
			$data['rem']=$rem[$i];
			$data['confirm']='1';
			$data['change_dd']=date("Y-m-d H:i:s");
			$result[]=$sendgoods->where("id=".$id[$i])->save($data);
		}
		if($result){
			$this->success("核对完成","CheckSendGoods");
		}
	}

	function CheckStorageGoods(){
		$this->title="核对库存商品";
		$storagegoods=M('storagegoods');
		$storagegoods_data=$storagegoods->where("is_check='0'")->order('company_name,so_no')->select();
		if($storagegoods_data){
			$this->storagegoods_data=$storagegoods_data;
			$this->display();
		}else{
			echo "没有需要核对的数据";
		}		
	}

	function CheckStorageGoodsDetail(){
		$this->title="核对库存商品明细";
		$so_no=I('so_no');
		if(!$so_no){
			$this->error('没有获取到订单号');
		}
		$storagegoods=M('storagegoods');
		$condition['so_no']=array('like',$so_no."%");
		$storagegoods_data=$storagegoods->where($condition)->select();
		if($storagegoods_data){
			$this->storagegoods_data=$storagegoods_data;
			$this->display();
		}else{
			$this->error('系统中没有这个订单号');
		}
	}

	function CheckStorageGoodsDetailResult(){
		$this->title="提交核对库存商品明细结果";
		$id=I('id');
		$state=I('state');
		$rem=I('rem');
		$sale_name=I('sale_name');
		$prd_name=I('prd_name');
		if(!$sale_name){
			$this->error('请填写业务员名字');
		}
		$storagegoods=M('storagegoods');
		$data=array();
		for($i=0;$i<count($id);$i++){
			if(!$state[$i]){
				$this->error("(".$prd_name[$i].")--没有选择状态");
			}
			$data[$i]['id']=$id[$i];
			$data[$i]['state']=$state[$i];
			$data[$i]['rem']=$rem[$i];
			$data[$i]['sale_name']=$sale_name;
			$data[$i]['is_check']='1';
			$data[$i]['checked_dd']=date("Y-m-d H:i:s");
		}
		for($i=0;$i<count($data);$i++){
			$result[]=$storagegoods->where("id=".$data[$i]['id'])->save($data[$i]);
		}		
		if(count($result)==count($id)){
			$this->success('提交完成',"CheckStorageGoods");
		}else{
			$this->error('提交失败');
		}
	}
}