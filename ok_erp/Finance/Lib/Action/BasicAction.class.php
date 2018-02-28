<?php
class BasicAction extends Action{

	public function _initialize(){
		if(session('user_group')=='finance'){
			$this->user_group='财务部';
		}else{
			header("Content-type: text/html; charset=utf-8");
			redirect("http://".$_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT'],2,'<h1><center>未登录.页面跳转中...</center></h1>');
		}
	}

	public function Company(){
		$this->title="公司资料";
		$company=M('company');
		$result_c=$company->select();
		if($result_c){
			$this->company=$result_c;
			$this->display();
		}else{
			$this->error('查询不到数据');
		}
	}

	public function AddCompany(){
		$company=D('company');
		if($company->create()){
			$result=$company->add();
			if($result){
				$this->success('添加公司完成',"__URL__/Company");
			}else{
				$this->error('添加公司失败');
			}
		}else{
			$this->error($company->getError());
		}
	}

	public function DeleteCompany($company_id=0){
		$company_id=I('company_id',0);
		$mf_ex=M('mf_exchange');
		$result=$mf_ex->where('company_id='.$company_id)->select();
		if($result){
			echo '这个公司已被使用,不能删除';
		}else{
			$company=M('company');
			$result_c=$company->delete($company_id);
			if($result_c){
				echo '删除公司成功';
			}else{
				echo '删除失败';
			}
		}
	}

	function ModifyCompany($company_id=null){
		$this->title="修改公司";
		$company_id=I('company_id');
		if(empty($company_id)){
			$this->error('没有获取到公司ID');
		}
		$mf_ex=M('mf_exchange');
		$result=$mf_ex->where('company_id='.$company_id)->select();
		if($result){
			$this->error('这个公司已被使用,不能修改');
		}
		$company=M('company');
		$datacompany=$company->find($company_id);
		if($datacompany){
			$this->datacompany=$datacompany;
			$this->display();
		}else{
			$this->error("未查询到数据");
		}
	}

	function ModifyCompanyResult($company_id=null){
		$this->title="修改公司结果";
		$company_id=I('company_id');
		if(empty($company_id)){
			$this->error('没有获取到公司ID');
		}
		$company=M('company');
		if($company->create()){
			$result=$company->where("company_id=$company_id")->save();
			if($result){
				$this->success("修改完成","Company");
			}else{
				$this->error("修改失败");
			}
		}else{
			$this->error($company->getError());
		}
	}

	public function Bank(){
		$this->title="银行资料";
		$bank=M('bank');
		$result_b=$bank->select();
		if($result_b){
			$this->bank=$result_b;
			$this->display();
		}else{
			$this->error('查询不到数据');
		}
	}

	public function AddBank(){
		$bank=D('bank');
		if($bank->create()){
			$result=$bank->add();
			if($result){
				$this->success('添加银行完成',"__URL__/Bank");
			}else{
				$this->error('添加银行失败');
			}
		}else{
			$this->error($bank->getError());
		}
	}	

	public function DeleteBank($bank_id=0){
		$bank_id=I('bank_id',0);
		$mf_ex=M('mf_exchange');
		$result=$mf_ex->where('bank_id='.$bank_id)->select();
		if($result){
			echo '这个银行已被使用,不能删除';
		}else{
			$bank=M('bank');
			$result_c=$bank->delete($bank_id);
			if($result_c){
				echo '删除银行成功';
			}else{
				echo '删除失败';
			}
		}
	}

	function ModifyBank($Bank_id=null){
		$this->title="修改公司";
		$bank_id=I('bank_id');
		if(empty($bank_id)){
			$this->error('没有获取到银行ID');
		}
		$mf_ex=M('mf_exchange');
		$result=$mf_ex->where('bank_id='.$bank_id)->select();
		if($result){
			$this->error('这个银行已被使用,不能修改');
		}
		$bank=M('bank');
		$databank=$bank->find($bank_id);
		if($databank){
			$this->databank=$databank;
			$this->display();
		}else{
			$this->error("未查询到数据");
		}
	}

	function ModifyBankResult($bank_id=null){
		$this->title="修改公司结果";
		$bank_id=I('bank_id');
		if(empty($bank_id)){
			$this->error('没有获取到公司ID');
		}
		$bank=M('bank');
		if($bank->create()){
			$result=$bank->where("bank_id=$bank_id")->save();
			if($result){
				$this->success("修改完成","bank");
			}else{
				$this->error("修改失败");
			}
		}else{
			$this->error($bank->getError());
		}
	}
}