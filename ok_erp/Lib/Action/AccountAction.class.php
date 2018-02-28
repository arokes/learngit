<?php
import('ORG.Util.Page');
class AccountAction extends Action{

	//static $main_url="http://".$_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT'];

	public function _initialize(){
		if(session('user_group')=='sale'){
			$this->user_group='销售部';
		}else{
			header("Content-type: text/html; charset=utf-8");
			redirect("http://".$_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT'],2,'<h1><center>未登录.页面跳转中...</center></h1>');
		}
	}

	public function NewAccount(){
		$this->title="客户台帐-从下单开始";
		$this->display();
	}

	public function AddAccount(){
		$this->title="新增客户台帐";
		$account=D("account");
		if($account->create()){
			$result=$account->add();
			if($result){
				$this->success('新增成功');
			}else{
				$this->error('新增失败');
			}	
		}else{
			$this->error($account->getError());
		}
	}

	public function SelectAccount(){
		$this->title="查询台帐";
		$this->display();
	}

	public function SelectAccountResult(){
		$this->title="查询台帐结果";
		$selectmode=I('selectmode')?I('selectmode'):'';
		$parameter=I('parameter')?"%".I('parameter')."%":'%';
		if($selectmode){
			$condition[$selectmode]=array('like',$parameter);
		}
		$account=M('account');
		$count=$account->where($condition)->count();
		$Page=new Page($count,15);
		$show=$Page->show();
		$list=$account->where($condition)->order('so_id')->limit($Page->firstRow.','.$Page->listRows)->select();
		if($list){
			$this->assign('list',$list);
			$this->assign('page',$show);
			$this->display();
		}else{
			$this->error('查询不到台帐数据');
		}

	}

	public function SelectAccountDetail($so_id=0){
		$this->title="查询台帐明细";
		$so_id=I('so_id',0);
		$account=M('account');
		$tf_exchange=M('tf_exchange');
		$result_acc=$account->find($so_id);
		$result_tf=$tf_exchange->table('tf_exchange as a')->join('mf_exchange as b on b.id=a.id')->join('company as c on b.company_id=c.company_id')->join('bank as d on b.bank_id = d.bank_id ')->where('a.so_id='.$so_id)->field('a.id,a.CUR_ID,a.amount_apart,a.debit,a.rem,c.company_snm,d.bank_snm,b.bank_cust,b.pay_dd,b.amount,b.country')->select();
		//dump($result_tf);
		if($result_acc){
			$this->account=$result_acc;
			$this->tf_exchange=$result_tf;
			$this->display();
		}else{
			$this->error('查找不到台帐数据');
		}
	}

	public function ModifyAccount($so_id=0){
		$this->title="修改客户台帐";
		$so_id=I('so_id',0);
		$account=M('account');
		$tf_exchange=M('tf_exchange');
		$result_acc=$account->where("write_off='F'")->find($so_id);
		$result_tf=$tf_exchange->where('so_id='.$so_id)->select();
		if($result_tf){
				$this->error('此单据已经有收汇不可修改');
		}else{
			if($result_acc){
				$this->assign('account',$result_acc);
				$this->url="javascript:history.back(-1);";
				$this->display();
			}else{
				$this->error('查找不到数据,或此单已经核销');
			}
		}
	}

	public function ModifyAccountAmount($so_id=0){
		$this->title="修改客户台帐";
		$so_id=I('so_id',0);
		$account=M('account');
		$tf_exchange=M('tf_exchange');
		$result_acc=$account->where("write_off='F'")->find($so_id);
		if($result_acc){
			$this->assign('account',$result_acc);
			$this->url="javascript:history.back(-1);";
			$this->display();
		}else{
			$this->error('查找不到数据,或此单已经核销');
		}
	}

	public function ModifyAccountResult($so_id=0){
		$this->title="修改客户台帐";
		$so_id=I('so_id',0);
		$data['so_pi']=I('CHINA_PI')==0.00?I('HONGKONG_PI'):I('CHINA_PI');
		$account=D('account');
		$tf_exchange=M('tf_exchange');
		$result_acc=$account->where("write_off='F'")->find($so_id);
		if($result_acc){
			if($account->create()){
				$result=$account->save();
				if($result){
					$tf_exchange->where("so_id='".$so_id."'")->save($data);
					$this->success('修改成功','SelectAccountDetail?so_id='.$so_id);
				}else{
					$this->error('修改失败');
				}
			}else{
				$this->error($account->getError());
			}
		}else{
			$this->error('查找不到数据,或此单已经核销');
		}
		
	}

	public function DeleteAccount($so_id=0){
		$this->title="删除客户台帐";
		$so_id=I('so_id',0);
		$account=M('account');
		$tf_exchange=M('tf_exchange');
		$result_acc=$account->where("write_off='F'")->find($so_id);
		$result_tf=$tf_exchange->where('so_id='.$so_id)->select();
		if($result_tf){
				$this->error('此单据已经有收汇不可删除');
		}else{
			if($result_acc){
				$this->assign('account',$result_acc);
				$this->url="javascript:history.back(-1);";
				$this->display();
			}else{
				$this->error('查找不到数据,或此单已手工核销');
			}
		}
	}

	public function DeleteAccountResult($so_id=0){
		$this->title="修改客户台帐";
		$so_id=I('so_id',0);
		$account=D('account');
		$tf_exchange=M('tf_exchange');
		$result_acc=$account->where("write_off='F'")->find($so_id);
		$result_tf=$tf_exchange->where('so_id='.$so_id)->select();
		if($result_tf){
				$this->error('此单据已经有收汇不可删除');
		}else{
			if($result_acc){
				$result=$account->where("so_id=".$so_id)->delete();
				if($result){
					$this->success('删除成功','SelectAccount');
				}else{
					$this->error('删除失败');
				}
			}else{
				$this->error('查找不到数据,或此单已经核销');
			}
		}
	}
}