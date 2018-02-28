<?php
class BillingDataAction extends Action {

	public function _initialize(){
		$pos=strpos(session('user_group'),'admin');
		$user_level=session('user_level');
		if($pos!==false&&$user_level>2){
			$this->user_group='管理员';
		}else{
			header("Content-type: text/html; charset=utf-8");
			redirect("http://".$_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT'],2,'<h1><center>未登录.页面跳转中...</center></h1>');
		}
	}

	function SelectBillingData(){
		$this->title="查询已上传开票资料";
		$this->display();
	}

	function SelectBillingDataResult($sale_name=null,$contract_no=null){
		import('ORG.Util.Page');
		$this->title="查询已上传开票资料结果";
		$sale_name=I('sale_name');
		$contract_no=I('contract_no');
		$billingdata=M('billingdata');
		$condition['sale_name']=array('like',"%".$sale_name."%");
		$condition['contract_no']=array('like',"%".$contract_no."%");
		$count=$billingdata->where($condition)->count();
		$Page=new Page($count,15);
		$show = $Page -> show();
		$list=$billingdata->where($condition)->limit($Page->firstRow.','.$Page->listRows)->select();
		if($list){
			$this->list=$list;
			$this->show=$show;
			$this->display();
		}else{
			$this->error('没有查询到结果');
		}
	}



	function ModifyBillingData($id=null){
		$this->title="修改开票资料";
		$id=I('id');
		if(empty($id)){
			$this->error('没有获取到ID');
		}
		$billingdata=M('billingdata');
		$listbillingdata=$billingdata->find($id);
		if($listbillingdata){
			$this->listbillingdata=$listbillingdata;
			$this->display();
		}else{
			$this->error('查询不到数据');
		}
	}

	function ModifyBillingDataResult($id=null){
		$this->title="修改开票资料结果";
		$id=I('id',0);
		if(empty($id)){
			$this->error('没有获取到ID');
		}
		$billingdata=M('billingdata');
		if($billingdata->create()){
			$result=$billingdata->where("ID=$id")->save();
			if($result){
				$this->success("修改成功");
			}else{
				$this->error("修改失败");
			}
		}else{
			$this->error($billingdata->getError());
		}
	}
}