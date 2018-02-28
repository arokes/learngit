<?php
import('ORG.Util.Page');
import('ORG.Util.PHPExcel');
load("@.functions");
class ClaimAction extends Action{
	
	static $join=array('tf_exchange b on a.id=b.id','company c on a.company_id=c.company_id','bank d on a.bank_id=d.bank_id');
	static $url_back="javascript:history.back(-1);";	

	public function _initialize(){
		if(session('user_group')=='sale'){
			$this->user_group='销售部';
		}else{
			header("Content-type: text/html; charset=utf-8");
			redirect("http://".$_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT'],2,'<h1><center>未登录.页面跳转中...</center></h1>');
		}
	}

	public function NewClaim(){
		$NewClaim=new Model();
		$this->title="认领汇款";
		$result_mf=$NewClaim->query('select count(distinct bank_cust) count from mf_exchange where claim=0');
		$count=$result_mf[0]['count'];
		$Page=new Page($count,15);

		$data=$NewClaim->Table('mf_exchange a left join company b on a.company_id=b.company_id left join bank c on a.bank_id=c.bank_id')->where('a.claim=0')->order('id')->group('bank_cust ')->limit($Page->firstRow.','.$Page->listRows)->select();
		$show=$Page->show();
		if($data) {
			$this->NewClaim = $data;
			$this->page=$show;
			}else{
			$this->error('数据错误');
		}
		$this->display();
	}

	public function claimDetail($id=0){
		$this->title="汇款认领明细";
		$this->url_back=self::$url_back;
		$mf_exchange=new Model();
		$data_mf=$mf_exchange->Table('mf_exchange a,company b,bank c')->where('a.claim=0 and a.company_id=b.company_id and a.bank_id=c.bank_id and id='.$id)->select();
		$account=M('account');
		$data_acc=$account->where("write_off='F'")->Distinct(true)->field('sal_name')->select();
		if($data_mf&&$data_acc){
			$this->data=$data_mf;
			$this->data_acc=$data_acc;
		}else{
			$this->error('数据错误!');
		}
		$this->display();
	}
	
	public function claimResult(){
		header('Content-Type: text/html; charset=utf-8');
		$this->title="汇款认领结果";
		$mf_ex=D('mf_exchange');
		$tf_ex=D('tf_exchange');
		$account=M('account');
		$id=I('id');
		$tf_data=$tf_ex->find($id);
		if($tf_data){
			$this->error('需要认领的数据已经存在!!!');
		}
		$amount_sum=I('post.amount_sum','');
		$cur_id=I('CUR_ID','');
		$amount_apart=I('post.amount_apart','');
		$brokerage=I('post.brokerage','');
		$so_no=I('so_no','');
		foreach($so_no as $value){
			$account=M('account');
			$result=$account->where("so_no like '".htmlspecialchars_decode($value)."'")->select();
			$so_id[]=$result[0]['so_id'];
		}
		$so_pi=I('post.so_pi','');
		$contract_cust=I('post.contract_cust','');
		$lz_cust=I('post.lz_cust','');
		$connect_cust=I('post.connect_cust','');
		$getlz_name=I('post.getlz_name','');
		$debit=I('post.debit','');
		$rem=I('post.rem','');
		$amount=0.0000;
		for($i=0;$i<count($amount_apart);$i++){
			$amount=round($amount,4)+round($amount_apart[$i],4);
			$isdeclare="is_declare".($i+1);
			$data[$i]=array('id'=>$id,'itm'=>$i+1,'CUR_ID'=>$cur_id,'amount_apart'=>$amount_apart[$i],'brokerage'=>$brokerage[$i],'so_id'=>$so_id[$i],'so_no'=>$so_no[$i],'so_pi'=>$so_pi[$i],'is_declare'=>I($isdeclare),'contract_cust'=>$contract_cust[$i],'lz_cust'=>$lz_cust[$i],'connect_cust'=>$connect_cust[$i],'debit'=>$debit[$i],'rem'=>$rem[$i],'record_dd'=>date('Y-m-d H:i:s'));
		}
		if(round($amount_sum,2)!=round($amount,2)){
			$this->error('认领到账金额总和不等于汇款总金额!');
		}
		$mf_ex->startTrans();
		$result_tf=$tf_ex->addall($data);
		$mf_ex->create();
		$result_mf=$mf_ex->save();
		if($result_mf&&$result_tf){
			$mf_ex->commit();
			$this->success('认领成功','NewClaim');
		}else{
			$mf_ex->rollback();
			$this->error('认领失败');
		}		
	}
	
	public function selectClaim(){
		$this->title="查询认领";
		$company=M('company');
		$result=$company->order('company_id')->select();
		if($result){
			$this->data=$result;
			$this->display();
		}else{
			$this->error('公司信息读取错误');
		}
	}

	public function selectClaimResult(){
		$this->title="查询认领结果";
		$select=array();
		$date_min=I('date_min','')?I('date_min'):'2016-12-01';
		$date_max=I('date_max','')?I('date_max'):Date("Y-m-d");
		$select['b.so_no']=array('like',I('so_no','')?I('so_no'):'%');
		$select['a.sal_name']=array('like',I('sal_name','')?I('sal_name'):'%');
		$select['a.company_id'] = array('like',I('company_id','')?I('company_id'):'%');
		$select['a.pay_dd']=array('between',array($date_min,$date_max));
		$select['a.claim']='1';
		$this->title_name=$date_min.'至'.$date_max;
		$result=M('');
		$count = $result->Table('mf_exchange as a')->join(self::$join)->where($select)->count();
		$Page = new Page($count,15);
		$show = $Page -> show();
		$list=$result->Table('mf_exchange as a')->join(self::$join)->where($select)->order('b.record_dd')->limit($Page->firstRow.','.$Page->listRows)->select();
		
		if($list){
			$this->assign('list',$list);
			$this->assign('page',$show);
			$this->display();
		}else{
			$this->error('错误!查询不到数据');
		}
	}

	public function selectClaimDetail($id=0){
		$this->title="查询认领明细";
		$id=I('id',0);
		$this->url_bak="javascript:history.back(-1);";
		$result=M('mf_exchange');
		$data=$result->alias('a')->join(self::$join)->where('a.id='.$id)->select();
		if($data){
			$this->data=$data;
		}else{
			$this->error('查询不到这个数据');
		}
		$this->display();
	}

	public function downloadClaimDetail(){
		@ini_set('memory_limit','128M');
		set_time_limit(0);
		$this->title="下载认领明细";
		$date_min=I('date_min','')?I('date_min'):get_month_first_day();
		$date_max=I('date_max','')?I('date_max'):Date("Y-m-d");
		$so_no=I('so_no','')?I('so_no'):'%';
		$sal_name=I('sal_name','')?I('sal_name'):'%';
		$company_id= I('company_id','')?I('company_id'):'%';
		$mf_ex=M('');
		$result=$mf_ex->query("select a.id '收汇编号',a.foreign_trade '销售方式',c.company_name '公司',d.bank_name '银行',a.pay_dd '付款日期',a.bank_cust '汇款客户',a.country '汇款国家',a.CUR_ID '币别',a.amount '汇款金额',a.sal_name '业务员',a.getlz_name '发票业务员',b.amount_apart '到账金额',b.brokerage '手续费',b.so_no '订单号',b.so_pi '订单PI总金额',b.debit '扣款/罚款',b.rem '备注',b.is_declare '是否报关',b.contract_cust '合同客户',b.lz_cust '发票客户',b.connect_cust '业务联系客户' from mf_exchange as a,tf_exchange as b,company as c,bank as d where a.id=b.id and a.company_id=c.company_id and a.bank_id=d.bank_id	and a.claim=1  and a.pay_dd>='".$date_min."' and a.pay_dd<='".$date_max."' and upper(b.so_no) like upper('".$so_no."') and upper(a.sal_name) like upper('".$sal_name."') and a.company_id like '".$company_id."' ");
		
		if($result){
			$objPHPExcel = new PhpExcel();
			getPhpExcelObjWriter($result,'收汇下载'.$date_min.'--'.$date_max,$objPHPExcel);
		}else{
			$this->error('查询不到结果!');
		}
	}

	public function changeClaim($id=0){
		$this->title="修改认领";
		$this->url="javascript:history.back(-1);";
		$id=I('id',0);
		$result=M('mf_exchange');
		$data=$result->alias('a')->join(self::$join)->where('a.id='.$id)->select();
		if($data){
			$this->data=$data;
		}else{
			$this->error('查询不到这个数据');
		}
		$this->display();
	}

	public function changeClaimResult(){
		$this->title="修改认领结果";
		$id=I('id',0);
		//echo $id;
		$itm=I('itm',0);
		$amount_sum=I('amount_sum','');
		$amount_apart=I('amount_apart','');
		$brokerage=I('brokerage','');
		$so_no=I('so_no','');
		$is_declare=I('is_declare','是');
		$lz_cust=I('lz_cust','');
		$connect_cust=I('connect_cust','');
		$debit=I('debit','');
		$rem=I('rem','');
		$amount=0.0000;
		$data=array();

		for($i=0;$i<count($amount_apart);$i++){
			$amount=round($amount,4)+round($amount_apart[$i],4);
			$data[$i]=array('amount_apart'=>$amount_apart[$i],'brokerage'=>$brokerage[$i],'is_declare'=>I("is_declare".($i+1)),'lz_cust'=>$lz_cust[$i],'connect_cust'=>$connect_cust[$i],'debit'=>$debit[$i],'rem'=>$rem[$i]);
			$condition[$i]=array('id'=>$id,'itm'=>$itm[$i]);
		}
		if(round($amount,2)==round($amount_sum,2)){
			$tf_ex=D('tf_exchange');
			$tf_ex->startTrans();
			for($i=0;$i<count($data);$i++){
				$result=$tf_ex->where($condition[$i])->save($data[$i]);
				if($result){
					$result_sum[]=$result;
				}
			}
			
			if($result_sum){
				$tf_ex->commit();
				$this->success('保存成功','selectClaimDetail?id='.$id);
			}else{
				$tf_ex->rollback();
				$this->error('保存失败!请检查台帐是否被核销?');
			}

		}else{
			$this->error('到账金额总和不等于汇款总金额');
		}
	}

	public function deleteClaim($id=0){
		$this->title="删除认领";
		$this->url="javascript:history.back(-1);";
		$id=I('id',0);
		$result=M('mf_exchange');
		$data=$result->alias('a')->join(self::$join)->where('a.id='.$id)->select();
		if($data){
			$this->data=$data;
		}else{
			$this->error('查询不到这个数据');
		}
		$this->display();
	}

	public function deleteClaimResult(){
		$this->title="删除认领结果";
		$id=I('id',0);
		$tf_ex=M('tf_exchange');
		$list_tf=$tf_ex->where("id=$id ")->select();
		if($list_tf){
			$k=0;
			foreach($list as $value){
				if($value['cls_id']=='T'){
					$k++;
				}
			}
		}
		if($k>0){
			$this->error('此认领已有被核销台账,不能删除,请联系管理员');
		}
		$result_tf=$tf_ex->where("id=$id")->delete();
		if($result_tf){
			$mf_ex=D('mf_exchange');
			$data['sal_name']=null;
			$data['getlz_name']=null;
			$data['claim']=0;
			$result_mf=$mf_ex->where("id=$id")->save($data);
			if($result_mf){
				$this->success('删除成功','selectClaim');
			}else{
				$this->error('更新表头数据失败');
			}
		}else{
			$this->error('删除失败');
		}
	}

	public function getAccountData($id=0,$sal_name=null){
		$this->title="汇款认领--查询可选择的台帐";
		$this->url_back="javascript:history.back(-1);";
		$id=I('id');
		$mf_exchange=new Model();
		$data_mf=$mf_exchange->Table('mf_exchange a,company b,bank c')->where('a.claim=0 and a.company_id=b.company_id and a.bank_id=c.bank_id and id='.$id)->find();
		$sal_name=I('sal_name','');
		$sal_name2=trim(I('sal_name2',''));
		$sal_name2_arr=null;
		if($sal_name2){
			$sal_name2_arr=explode(" ",$sal_name2);
		}
		$condition="'".$sal_name."'";
		if($sal_name2_arr){
			for($i=0;$i<count($sal_name2_arr);$i++){
				$condition=$condition.",'".$sal_name2_arr[$i]."'";
				$sal_name=$sal_name."&".$sal_name2_arr[$i];
			}	
		}
		$account=M('account');
		$select=sprintf("select a.sal_name,a.so_id,a.so_no,a.cust_name,a.country,a.CUR_ID,a.HONGKONG_PI,a.CHINA_PI,a.receivable,a.rem,sum(ifnull(b.amount_apart,0)) as amount_apart,(a.receivable-sum(ifnull(b.amount_apart,0))-sum(ifnull(b.brokerage,0))) as balance_amount,sum(ifnull(b.brokerage,0)) as brokerage FROM account as a left join tf_exchange as b on a.so_id = b.so_id  where a.write_off = 'F' AND a.sal_name in (%s) group by a.sal_name,a.so_id,a.so_no,a.cust_name,a.country,a.CUR_ID,a.HONGKONG_PI,a.CHINA_PI,a.receivable,a.rem  ORDER BY a.so_no",$condition);
		$data_acc=$account->query($select);
		if($data_acc&&$data_mf){
			$this->assign('data_mf',$data_mf);
			$this->assign('data_acc',$data_acc);
			$this->assign('sal_name',$sal_name);
			$this->display();
		}else{
			$this->error('没有可用的数据');
		}
	}

	public function writeExchangeValue($id=0){
		$this->title="汇款认领--填写到账金额";
		$this->url_back=self::$url_back;
		$so_id=I('so_id','');
		$id=I('id','');
		if(empty($so_id)&&empty($id)){
			$this->error('请在需要认领的订单前打勾');
		}
		$sal_name=I('sal_name');
		$mf_exchange=M('');
		$data_mf=$mf_exchange->Table('mf_exchange a,company b,bank c')->where('a.claim=0 and a.company_id=b.company_id and a.bank_id=c.bank_id and id='.$id)->find();
		if(empty($data_mf)){
			$this->error($mf_exchange->getError());
		}
		$account=M('account');
		$so_id_str=0;
		for($i=0;$i<count($so_id);$i++){
			$so_id_str=$so_id_str.",".$so_id[$i];
		}
		$select =sprintf("select a.so_id,a.so_no,a.cust_name,a.CUR_ID,a.CHINA_PI,a.HONGKONG_PI,a.rem,(a.receivable-sum(ifnull(b.amount_apart,0))) as balance_amount FROM account as a left join tf_exchange as b on a.so_id = b.so_id  where a.write_off = 'F' AND a.so_id IN (%s) group by a.so_id,a.so_no,a.cust_name,a.CUR_ID,a.CHINA_PI,a.HONGKONG_PI,a.rem  ORDER BY a.so_no",$so_id_str);
		$data_acc=$account->query($select);
		if($data_mf&&$data_acc){
			$this->assign('data_mf',$data_mf);
			$this->assign('data_acc',$data_acc);
			$this->assign('sal_name',$sal_name);
			$this->display();
		}else{
			$this->error('没有可用的订单数据');
		}
	}
}