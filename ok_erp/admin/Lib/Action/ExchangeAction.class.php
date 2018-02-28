<?php
import('ORG.Util.Page');
class ExchangeAction extends Action{
	static $join=array('company c on a.company_id=c.company_id','bank d on a.bank_id=d.bank_id');

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

	function SelectExchange(){
		$this->title="查询到汇";
		$this->display();
	}

	function SelectExchangeResult(){
		$this->title="查询到汇结果";
		$selectmode=I('selectmode');
		if(empty($selectmode)){
			$this->error('没有获取到查询模式');
		}
		$condition[$selectmode]=array('like',I('parameter')?"%".trim(I('parameter'))."%":'%');
		$mf_exchange=M('mf_exchange');
		$count=$mf_exchange->where($condition)->count();
		$page=new Page($count,20);
		$listmf=$mf_exchange->where($condition)->limit($page->firstRow.','.$page->listRows)->select();
		if($listmf){
			$this->show=$page->show();
			$this->listmf=$listmf;
			$this->display();
		}else{
			$this->error('查询不到任何数据');
		}

	}

	public function ModifyExchangeDetail($id=0){
		$this->title='修改收汇明细';
		$company=M('company');
		$this->company=$company->select();
		$bank=M('bank');
		$this->bank=$bank->select();
		$id=I('id',0);
		if(empty($id)){
			$this->error('没有获取到ID');
		}
		$mf_ex=M('mf_exchange');
		$listmf=$mf_ex->find($id);
		if($listmf){
			$this->listmf=$listmf;
			$this->display();
		}else{
			$this->error('查询不到数据');
		}
	}

	public function ModifyExchangeResult($id=0){
		$this->title='修改收汇结果';
		$id=I('id',0);
		$mf_ex=D('mf_exchange');
		if($mf_ex->create()){
			$result=$mf_ex->save();
			if($result){
				$this->success('修改成功!','SelectExchange');
			}else{
				$this->error('修改失败!');
			}
		}else{
			$this->error($mf_ex->getError());
		}
	}

	function DeleteExchange($id=null){
		$this->title="删除收汇";
		$id=I('id');
		if(empty($id)){
			$this->error('没有获取到ID');
		}
		$mf_exchange=M('mf_exchange');
		$claim=$mf_exchange->field('claim')->find($id);
		if($claim['claim']==0){
			$data['claim']=9;
			$result=$mf_exchange->where("id=$id")->save($data);
			if($result){
				echo "删除成功";
			}else{
				echo "删除失败";
			}
		}else{
			echo "不能删除";
		}
	}


	function NoDeleteExchange($id=null){
		$this->title="恢复收汇";
		$id=I('id');
		if(empty($id)){
			$this->error('没有获取到ID');
		}
		$mf_exchange=M('mf_exchange');
		$claim=$mf_exchange->field('claim')->find($id);
		if($claim['claim']==9){
			$data['claim']=0;
			$result=$mf_exchange->where("id=$id")->save($data);
			if($result){
				echo "恢复成功";
			}else{
				echo "恢复失败";
			}
		}else{
			echo "不能恢复";
		}
	}

}
