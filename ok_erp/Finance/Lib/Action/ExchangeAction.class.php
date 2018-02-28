<?php
class ExchangeAction extends Action{
	static $join=array('company c on a.company_id=c.company_id','bank d on a.bank_id=d.bank_id');

	public function _initialize(){
		if(session('user_group')=='finance'){
			$this->user_group='财务部';
		}else{
			header("Content-type: text/html; charset=utf-8");
			redirect("http://".$_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT'],2,'<h1><center>未登录.页面跳转中...</center></h1>');
		}
	}
	
	public function NewExchange(){
		$this->title='新增收汇';
		$company=M('company');
		$this->company=$company->select();
		$bank=M('bank');
		$this->bank=$bank->select();
		$this->display();
	}

	public function NewExchangeResult(){
		$this->title='新增收汇结果';
		$mf_ex=D('mf_exchange');
		$data['bank_cust']=I('bank_cust');
		$data['pay_dd']=I('pay_dd');
		$data['amount']=I('amount');
		$data['rem']=I('rem');
		$result=$mf_ex->where($data)->select();
		if($result){
			$this->error('重复输入!');
		}
		if($mf_ex->create()){
			$result=$mf_ex->add();
			if($result){
				$this->success('新增认领完成!');
			}else{
				$this->error('新增失败!');
			}
		}else{
			$this->error($mf_ex->getError());
		}
	}

	public function ModifyExchange(){
		$this->title='查询收汇';
		$mf_ex=M('mf_exchange');
		$result=$mf_ex->alias('a')->join(self::$join)->where('claim=0')->order('id')->select();
		if($result){
			$this->data=$result;
			$this->display();
		}else{
			$this->error('查询不到任何数据');
		}
	}

	public function ModifyExchangeDetail($id=0){
		$this->title='修改收汇明细';
		$id=I('id',0);
		$tf_ex=M('tf_exchange');
		if($tf_ex->where('id='.$id)->select()){
			$this->error('此收汇已经被认领,不可修改');
		}
		$company=M('company');
		$bank=M('bank');
		$mf_ex=M('mf_exchange');
		$result=$mf_ex->where('claim!=1')->find($id);
		if($result){
			$this->listmf=$result;
			$this->company=$company->select();
			$this->bank=$bank->select();
			$this->display();
		}else{
			$this->error('查询不到数据');
		}
	}

	public function ModifyExchangeResult($id=0){
		$this->title='修改收汇结果';
		$id=I('id',0);
		$tf_ex=M('tf_exchange');
		if($tf_ex->where('id='.$id)->select()){
			$this->error('此收汇已经被认领,不可修改');
		}else{
		$mf_ex=D('mf_exchange');
			if($mf_ex->create()){
				$result=$mf_ex->save();
				if($result){
					$this->success('修改成功!');
				}else{
					$this->error('修改失败!');
				}
			}else{
				$this->error($mf_ex->getError());
			}
		}
	}

	public function DeleteExchangeDetail($id){
		$this->title='修改收汇明细';
		$id=I('id',0);
		$mf_ex=M('mf_exchange');
		$result=$mf_ex->alias('a')->join(self::$join)->where('a.claim=0 and a.id='.$id)->select();
		if($result){
			$this->data=$result;
			$this->display();
		}else{
			$this->error('查询不到数据');
		}

	}

	public function DeleteExchangeResult($id=0){
		$this->title='删除收汇结果';
		$id=I('id',0);
		$tf_ex=M('tf_exchange');
		if($tf_ex->where('id='.$id)->select()){
			echo '此收汇已经被认领,不可删除';
			exit;
		}
		$data['claim']=9;
		$mf_ex=D('mf_exchange');
		$result=$mf_ex->where("id=$id")->save($data);
		if($result){
			echo '删除成功!';
		}else{
			echo "删除失败!";
		}
	}

	function DeletedExchange(){
		$this->title="查询已删除汇款";
		$listcompany=M('company')->select();
		$listbank=M('bank')->select();
		if($listcompany&&$listbank){
			$this->listcompany=$listcompany;
			$this->listbank=$listbank;
			$this->display();
		}else{
			$this->error("没有获取到公司银行数据");
		}
		
	}

	function DeletedExchangeResult(){
		import("ORG.Util.Page");
		$this->title="查询已删除汇款";
		$bank_cust=I('bank_cust')?"%".trim(I('bank_cust'))."%":"%";
		$company_id=I('company_id')?I('company_id'):"%";
		$bank_id=I('bank_id')?I('bank_id'):"%";
		$amount=I('amount')?"%".I('amount')."%":"%";
		$condition['a.bank_cust']=array('like',$bank_cust);
		$condition['a.company_id']=array("like",$company_id);
		$condition['a.bank_id']=array("like",$bank_id);
		$condition['a.amount']=array("like",$amount);
		$condition['a.claim']=9;
		$mf_exchange=M('mf_exchange');
		$count=$mf_exchange->alias('a')->where($condition)->join(self::$join)->count();
		$page=new Page($count,15);
		$listmf=$mf_exchange->alias('a')->where($condition)->join(self::$join)->limit($page->firstRow.",".$page->listRows)->order("a.id")->select();
		if($listmf){
			$this->listmf=$listmf;
			$this->show=$page->show();
			$this->display();
		}else{
			$this->error("没有被删除的汇款");
		}
	}
}
