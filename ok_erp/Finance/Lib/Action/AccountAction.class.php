<?php
import('ORG.Util.Page');
import('ORG.Util.PHPExcel');
load("@.functions");
class AccountAction extends Action{

	public function _initialize(){
		if(session('user_group')=='finance'){
			$this->user_group='财务部';
		}else{
			header("Content-type: text/html; charset=utf-8");
			redirect("http://".$_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT'],2,'<h1><center>未登录.页面跳转中...</center></h1>');
		}
	}

	public function SelectAccount(){
		$this->title="查询台帐";
		$this->display();
	}

	public function SelectAccountResult(){
		$this->title="查询台帐结果";
		$contidion['write_off']=array('like',I('write_off','%'));
		$contidion['so_no']=array('like',I('so_no','')?I('so_no'):'%');
		$contidion['sal_name']=array('like',I('sal_name','')?I('sal_name'):'%');
		$account=M('account');
		$count=$account->where($contidion)->count();
		$Page=new Page($count,15);
		$show=$Page->show();
		$list=$account->where($contidion)->order('so_id')->limit($Page->firstRow.','.$Page->listRows)->select();
		if($list){
			$_SESSION['SAR_url']=$_SERVER['REQUEST_URI'];
			
			$this->assign('list',$list);
			$this->assign('page',$show);
			$this->url="javascript:history.back(-1);";
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
			$this->url="javascript:history.back(-1);";
			$this->display();
		}else{
			$this->error('查找不到台帐数据');
		}
	}

	public function Write_offAccount($so_id=0){
		$this->title="台帐核销";
		$so_id=I('so_id',0);
		$account=M('account');
		$result_acc=$account->find($so_id);
		$tf_exchange=M('tf_exchange');
		$result_tf=$tf_exchange->where("so_id=$so_id")->select();
		$amount=0.00;
		for($i=0;$i<count($result_tf);$i++){
			if($result_acc['CUR_ID']==$result_tf[$i]['CUR_ID']){
				$amount=$amount+$result_tf[$i]['amount_apart']+$result_tf[$i]['brokerage'];
			}else{
				$this->error('台帐币别与收汇不一致');
			}
		}
		if(round($amount,2)>=round($result_acc['receivable'],2)){
			$res_acc=$account->where("so_id=$so_id")->setField('write_off','T');
			$res_tf=$tf_exchange->where("so_id=$so_id")->setField('cls_id','T');
			if($res_acc&&$res_tf){
				$this->success('核销成功');
			}else{
				$this->error('出现了未知错误');
			}
		}else{
			$this->error('收款金额小于应收金额,不能核销');
		}
	}

		public function DownloadAccountDetail(){
		@ini_set('memory_limit','128M');
		set_time_limit(0);
		$this->title="下载台帐明细";
		$so_no=I('so_no','')?I('so_no'):'%';
		$sal_name=I('sal_name','')?I('sal_name'):'%';
		$write_off=I('write_off','%');
		$account=M('');
		$result=$account->query("select a.so_id '订单编号',a.cust_name '客户名称',a.country '国家',a.so_no '台帐订单号',a.CUR_ID '币别',a.HONGKONG_PI '与香港公司签订PI金额',a.CHINA_PI '与国内公司签订PI金额',a.expect_sale '预计出货日期',a.receivable '应收款',
			a.account_period  '账期',a.recevable_dd '预计收款日期',a.sal_name '业务员',a.rem '备注',a.write_off '核销',b.id '收汇编号',b.amount_apart '到账金额',b.brokerage '手续费',b.so_no '收汇订单号',b.so_pi '收汇订单PI金额',b.is_declare '报关',b.contract_cust '签约客户名称',
			b.lz_cust '开票客户名称',b.connect_cust '实际联系客户名称',b.debit '罚/扣款情况',b.rem '备注' from account a left join tf_exchange b on a.so_id=b.so_id and a.so_no like '".$so_no."' and sal_name like '".$sal_name."' and a.write_off like '".$write_off."' order by a.so_id");
		if($result){
			$objPHPExcel = new PhpExcel();
			getPhpExcelObjWriter($result,'台帐下载',$objPHPExcel);
		}else{
			$this->error('查询不到结果!');
		}
	}

	public function Manwrite_offAccount($so_id=0){
		$this->title="强制台帐核销";
		$so_id=I('so_id',0);
		$account=M('account');
		$tf_exchange=M('tf_exchange');
		$tf_id=I('tf_id','');
		$data['so_id']=$so_id;
		$data['cls_id']='T';
		if($tf_id){
			for($i=0;$i<count($tf_id);$i++){
				$tf_exchange->where($tf_id[$i])->save($data);
			}
		}
		$result=$account->where("so_id=$so_id")->setField('write_off','T');
		if($result){
			$this->success('核销完成');
		}else{
			$this->error('核销失败');
		}
		
	}
	
	function Write_offAllAccount(){
		$this->title="核销所有台账";
		$data_acc['write_off']="T";
		$data_tf_ex['cls_id']="T";
		$tf_exchange=M('tf_exchange');
		$account=M('account');
		$so_id=$account->field('so_id')->where("write_off='F'")->select();
		for($i=0;$i<count($so_id);$i++){
			$account_data=$account->field('receivable')->find($so_id[$i]['so_id']);
			$tf_exchange_data=$tf_exchange->where("so_id='".$so_id[$i]['so_id']."'")->sum('amount_apart');
			if($tf_exchange_data>=$account_data['receivable']){
				$account->where('so_id='.$so_id[$i]['so_id'])->save($data_acc);
				$tf_exchange->where('so_id='.$so_id[$i]['so_id'])->save($data_tf_ex);
				echo $so_id[$i]['so_id']."->T<br />";
			}
		}
			
	}

}