<?php
class StockAction Extends Action{
	
	public function _initialize(){
		header("Content-type: text/html; charset=utf-8");
		$user_group=$_SESSION['user_group'];
		$group="phone";
		if(strpos($user_group,$group)!==false){
			$this->user_group=session('user_group_name');
		}else{
			
			redirect("http://".$_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT'],2,'<h1><center>未登录.页面跳转中...</center></h1>');
		}
	}

	function SelectStock(){
		$this->title="查询贸易公司库存品号";
		$this->display();
	}

	function SelectStockResult($batno=null){
		$this->title="查询贸易公司库存结果";
		$batno=I('batno');
		if(!$batno){
			echo "请输入批号";
			exit;
		}
		$parameter=I('parameter');
		$mark=I('mark');
		$db=array('DB_OK01'=>'上虞欧科电器','DB_MKJC'=>'绍兴美科进出口','DB_JXOK'=>'景德镇欧科');
		$parameter=I('parameter');
		$para_arr=explode(' ',trim($parameter));
		$stock=M('');
		$stock->db(1,"mssql://sa:mkdq@192.168.1.221/DB_OK01");
		foreach ($db as $key=>$company_name){
			$select="select bat_no,prd_no,prd_name,prd_mark,sum(case when ps_id='PC' then isnull(qty,0) when ps_id='PB' then isnull(-qty,0) end) pc,sum(case when ps_id='SA' then isnull(qty,0) when ps_id='SB' then isnull(-qty,0) end) sa,sum(case when ps_id='PC' then isnull(qty,0) when ps_id='SA' then isnull(-qty,0) when ps_id='PB' then isnull(-qty,0) when ps_id='SB' then isnull(qty,0) else 0 end) stock,sum(case when ps_id='PC' then isnull(qty_fp,0) when ps_id='PB' then isnull(-qty_fp,0) end) pc_qty_fp,sum(case when ps_id='SA' then isnull(qty_fp,0) when ps_id='SB' then isnull(-qty_fp,0) end) sa_qty_fp from ".$key.".dbo.tf_pss where upper(bat_no) like upper('%".iconv("utf-8","GBK",$batno)."%') and upper(prd_mark) like upper('%".$mark."%')";
			for($i=0;$i<count($para_arr);$i++){
				$select_add=" and upper(prd_name) like upper('%".iconv("utf-8","GBK",$para_arr[$i])."%')";
				$select=$select.$select_add;
			}
			$select_group="group by bat_no,prd_no,prd_name,prd_mark order by bat_no,prd_no,prd_mark";
			$select=$select.$select_group;
			$stock_data[$company_name]=$stock->query($select);
		}
		if($stock_data){
			$this->stock_data=$stock_data;
			$this->display();
		}else{
			echo "没有数据";
		}
	}



}