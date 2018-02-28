<?php
class SxmkAccountStatementAction extends Action{

	Public function __initialize(){
		header("Content-type: text/html; charset=utf-8");
		$pos=strpos(session('user_group'),'cust');
		$user_level=session('user_level');
		if($pos!==false&&$user_level>=2){
			$this->user_group='内销';
		}else{	
			redirect("http://".$_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT'],2,'<h1><center>未登录.页面跳转中...</center></h1>');
		}
	}

	function SxmkAccountStatement(){
		$this->title="绍兴美科科技客户对账单";
		$this->display();
	}

	function SxmkAccountStatementResult(){
		$this->title="绍兴美科科技客户对账单";
		$cust=M('');
		$cust_db=$cust->db(1,'mssql://sa:mkdq@192.168.1.221/DB_SXMK');
		if(!$cust_db){
			$this->error('连接数据库失败');
		}
		$date_min=I('date_min')?I('date_min'):date('Y-m')."-01";
		$date_max=I('date_max')?I('date_max'):date('Y-m-d');
		$cus_no = I('cusno');
		if(!$cus_no){
			$this->error('请输入客户代号');
		}
		//查询是否有归属客户
		$select="select cus_no from cust where mas_cus='".$cus_no."'";
		$cust_mas=$cust->query($select);
		$str_cus_no="'".$cus_no."'";
		if(count($cust_mas)>0){
			for($i=0;$i<count($cust_mas);$i++){
			$str_cus_no=$str_cus_no.','."'".$cust_mas[$i]['cus_no']."'";
			}
		}
		//计算期初余额
		$tf_mon=M('tf_mon');
		$select="select sum(amtn_bc) amt from tf_mon where cus_no in (".$str_cus_no.") and convert(varchar,eff_dd,023)<'".$date_min."'";
		
		$list_tf_mon=$tf_mon->db(1)->query($select);
		$tf_pss=M('tf_pss');
		$select ="select sum(case when a.ps_id='SA' then b.amt when a.ps_id='SB' then -b.amt when a.ps_id='SD' then -b.amt end) amt from mf_pss a,tf_pss b where a.cus_no in (".$str_cus_no.") and a.ps_no = b.ps_no and b.eff_dd<'".$date_min."'";
		
		$list_tf_pss=$tf_pss->db(1)->query($select);
		$initial_arrears=$list_tf_pss[0]['amt']-$list_tf_mon[0]['amt'];
		//导出本期数据
		$select ="select b.name,convert(varchar,a.rp_dd,023) ps_dd,'收款' ps_id,cast(a.amtn_bc as numeric(13,2)) amtn_bc from tf_mon a,cust b where a.cus_no = b.cus_no and a.cus_no in (".$str_cus_no.") and a.rp_dd>='".$date_min."' and a.rp_dd<='".$date_max."' ";
		$list_tf_mon=$tf_mon->db(1)->query(iconv("utf-8","GBK",$select));
		$select = "select c.name,convert(varchar,b.ps_dd,023) ps_dd,b.ps_no,case when a.ps_id='SA' then '销售出库单' when a.ps_id='SB' then '销售退回单' when a.ps_id='SD' then '销售折让单' end ps_id,b.prd_name,d.ut,cast(b.up as numeric(13,3)) up,case when a.ps_id='SA' then cast(b.qty as numeric(13,3)) else cast(-b.qty as numeric(13,3)) end qty,case when a.ps_id= 'SA' then cast(b.amt as numeric(13,2)) else cast(-b.amt as numeric(13,2)) end amt from mf_pss a,tf_pss b,cust c,prdt d where a.ps_no = b.ps_no and a.cus_no in (".$str_cus_no.") and a.cus_no = c.cus_no and a.ps_dd>='".$date_min."' and a.ps_dd <='".$date_max."' and b.prd_no = d.prd_no ";
		//echo $select;
		$list_tf_pss=$tf_pss->db(1)->query(iconv("utf-8","GBK",$select));
		//dump($list_tf_pss);
		//插入到客户对账单数组
		$sum_amt=0;
		$sum_amtn_bc=0;
		$k=1;
		$list_account=array();
		$list_account[0]['ps_id']=iconv("utf-8","GBK","期初");
		$list_account[0]['init']=$initial_arrears;
		for($i=0;$i<count($list_tf_mon);$i++){
			$list_account[$k]=$list_tf_mon[$i];
			$sum_amtn_bc=$sum_amtn_bc+$list_tf_mon[$i]['amtn_bc'];
			$k++;
		}
		for($i=0;$i<count($list_tf_pss);$i++){
			$list_account[$k]=$list_tf_pss[$i];
			$sum_amt=$sum_amt+$list_tf_pss[$i]['amt'];
			$k++;
		}
		
		function my_sort($a,$b){
			if($a['ps_dd']==$b['ps_dd']) return 0;
			return ($a['ps_dd']<$b['ps_dd'])?-1:1;
		}
		usort($list_account,"my_sort");
		$list_account[$k]['ps_id']=iconv("utf-8","GBK","合计");
		$list_account[$k]['amt']=$sum_amt;
		$list_account[$k]['amtn_bc']=$sum_amtn_bc;
		$list_account[$k]['init']=$initial_arrears+$sum_amt-$sum_amtn_bc;
		if($list_account){
			$this->list_account=$list_account;
			$this->display();
		}else{
			$this->error('查找不到数据');
		}
		

		
		
	}
}
?>