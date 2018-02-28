<?php
class SendGoodsAction extends Action{
	public function _initialize(){
		$pos=strpos(session('user_group'),'warehouse');
		if($pos!==false){
			$this->user_group='仓库';
		}else{
			header("Content-type: text/html; charset=utf-8");
			redirect("http://".$_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT'],2,'<h1><center>未登录.页面跳转中...</center></h1>');
		}
	}

	function WaitSendGoods(){
		$this->title="待发货清单";
		$shipment=M('shipment');
		$shipment_data=$shipment->where("up_id='T' and cls_id='F'")->select();
		$this->shipment_data=$shipment_data;
		$this->display();
	}

	function SendDetail($shipment_id=null){
		$this->title="发货明细单";
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

	function SendBack($shipment_id=null){
		$shipment_id=I('shipment_id');
		$data['up_id']='F';
		$shipment=M('shipment');
		$result=$shipment->where("shipment_id=".$shipment_id)->save($data);
		if($result){
			echo "反审核完成";
		}else{
			echo "反审核失败";
		}
	}

	function SendOut($shipment_id=null){
		$shipment_id=I('shipment_id');
		$data['cls_id']='T';
		$shipment=M('shipment');
		$result=$shipment->where("up_id='T' and shipment_id=".$shipment_id)->save($data);
		if($result){
			echo "发货完成";
		}else{
			echo "发货失败";
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
		$condition['up_id']=array('EQ','T');
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

	function DownloadFile($filename=null,$showname=null){
		header("Content-type:text/html;charset=utf-8");
		$filename="c:\\xampp/htdocs/ok_erp/tpl/Public/upload/".I('filename');
		$showname=I('showname').".doc";
		import('ORG.NET.Http');
		$Http=new Http();
		$Http->download($filename,$showname);
	}
}