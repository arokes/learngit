<?php
import('ORG.Util.Page');
import('ORG.Util.PHPExcel');
load("@.functions");
class AccountAction extends Action {

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

	public function SelectAccount(){
		$this->title="查询台帐";
		$this->display();
	}

	public function SelectAccountResult(){
		$this->title="查询台帐结果";
		$selectmode=I('selectmode');
		$parameter=I('parameter')?"%".trim(I('parameter'))."%":'%';
		$contidion[$selectmode]=array('like',$parameter);
		$account=M('account');
		$count=$account->where($contidion)->count();
		$Page=new Page($count,15);
		$show=$Page->show();
		$list=$account->where($contidion)->order('so_id')->limit($Page->firstRow.','.$Page->listRows)->select();
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
		$result_tf=$tf_exchange->where('so_id='.$so_id)->select();
		$so_no_like=substr($result_acc['so_no'],0,4);
		$data_tf=$tf_exchange->alias('a')->join("mf_exchange b on a.id=b.id")->where("a.so_no like '".$so_no_like."%' and b.sal_name like '".$result_acc['sal_name']."%' and a.cls_id='F'")->select();
		//dump($data_tf);
		if($result_acc){
			$this->account=$result_acc;
			$this->tf_exchange=$result_tf;
			$this->assign('data_tf',$data_tf);
			$this->url=$_SESSION['SAR_url'];
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
		$receviable=$result_acc['receivable'];
		for($i=0;$i<count($result_tf);$i++){
			if($result_acc['CUR_ID']==$result_tf[$i]['CUR_ID']){
				$amount=$amount+$result_tf[$i]['amount_apart']+$result_tf[$i]['brokerage'];
				if($result_tf[$i]['amount_apart']<0){
					$receviable=$receviable+$result_tf[$i]['amount_apart'];
				}
			}else{
				$this->error('台帐币别与收汇不一致');
			}
		}
		if(round($amount,2)>=round($receviable,2)){
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
   
	function WriteOff(){
   		$this->title="核销台帐";
   		$this->display();
   	}

   	//核销所有已认领全部金额的台帐
	function WriteOffResult(){
   		header("Content-type: text/html; charset=utf-8");
   		//先查出没有被核销的台帐信息
    	$account=M('account');
    	$account_data=$account->where("write_off='F'")->select();
    	//dump($account_data);
    	foreach($account_data as $array_acc){
    		if($array_acc['receivable']>0){
	    		//查询与台帐ID相同的认领
	    		$tf_exchange=M('tf_exchange');
	    		$tf_exchange_data=$tf_exchange->where("so_id=".$array_acc['so_id'])->select();
	    		if($tf_exchange_data){
	    			echo $array_acc['so_no'].":";
	    			//把认领所有金额相加
		    		$sum_amount=0;
		    		$receivable=$array_acc['receivable'];
		    		foreach($tf_exchange_data as $array_tf){
		    			if($array_tf['CUR_ID']==$array_acc['CUR_ID']){
		    				$sum_amount=$sum_amount+$array_tf['amount_apart']+$array_tf['brokerage'];
		    				if($array_tf['amount_apart']<0){
		    					$receivable=$receivable+$array_tf['amount_apart'];
		    				}
		    			}else{
		    				echo "币别不一致->";
		    				break;
		    			}	
		    		}
	    			echo "总金额->".round($sum_amount,2)."原应收账款->".round($array_acc['receivable'],2)."实际应收账款->".round($receivable,2)."----";
	    			//把所有到账金额跟台帐的应收账款对比,如果金额大于应收账款,则核销该台帐
	    			if(round($sum_amount,2)>=round($receivable,2)){	    				
	    				$data_acc['write_off']='T';
	    				$data_tf['cls_id']='T';
	    				$account->startTrans();
	    				$result_acc=$account->where("so_id=".$array_acc['so_id'])->save($data_acc);//核销台帐
	    				$result_tf=$tf_exchange->where("so_id=".$array_acc['so_id'])->save($data_tf);//锁定认领 不能修改
	    				if($result_acc&&$result_tf){
	    					$account->commit();
	    					echo "---已核销";
	    				}else{
	    					$account->rollback();
	    					echo "---未核销";
	    				}
	    			}else{
	    				echo "---收款金额小于应收金额,不能核销";
	    			}
	    			echo "<br />";
	    		}
    		}
    	
    	}
    }

    function ReWriteOff(){
    	$this->title="反核销台帐";
    	$this->display();
    }

    function ReWriteOffResult($so_no=null){
    	$this->title="反核销台帐结果";
    	$so_no = I('so_no');
    	$account=M('account');
    	$account_data=$account->where("so_no like '".$so_no."' and write_off='T'")->select();
    	if($account_data){
    		$account->write_off="F";
    		$account_result=$account->where("so_id=".$account_data[0]['so_id'])->save();
    		$tf_exchange=M('tf_exchange');
    		$tf_exchange->cls_id="F";
    		$tf_exchange_result=$tf_exchange->where("so_id=".$account_data[0]['so_id'])->save();
    		if($account_result && $tf_exchange_result){
    			$this->success('反核销成功');
    		}else{
    			$this->success('反核销失败');
    		}
    	}else{
    		$this->error('请核查订单号码是否正确');
    	}
    }

    function ModifyAccount($so_id=null){
    	$this->titile="修改台账";
    	$so_id=I('so_id');
    	if(empty($so_id)){
    		$this->error('没有订单ID');
    	}
    	$account=M('account');
    	$listaccount=$account->find($so_id);
    	if($listaccount){
    		$this->listaccount=$listaccount;
    		$this->display();
    	}else{
    		$this->error('查询不到结果');
    	}
    }

    function ModifyAccountResult($so_id=null){
    	$this->title="修改台账结果";
    	$so_id=I('so_id');
    	if(empty($so_id)){
    		$this->error('没有订单ID');
    	}
    	$data['sal_name']=I('sal_name');
    	$data['so_no']=I('so_no');
    	$data['so_pi']=I('CHINA_PI');
    	$data['contract_cust']=I('cust_name');
    	$account=M('account');
    	$account->create();
    	$result_acc=$account->where("so_id=".$so_id)->save();
    	if(!$result_acc){
    		$this->error('修改不成功');
    	}else{
    		$tf_exchange=M('tf_exchange');
    		$tf_exchange->where("so_id=".$so_id)->save($data);
    		$update=sprintf("update mf_exchange as a,tf_exchange as b set a.sal_name='%s' where a.id = b.id and b.so_id = '%s'",$data['sal_name'],$so_id);
    		$tf_exchange->query($update);
    		$this->success("修改成功","SelectAccountDetail?so_id=".$so_id);
    	}
    }
}