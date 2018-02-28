<?php
class CheckWarehouseAccountAction extends Action{
	public function _initialize(){
		if(session('user_group')=='finance'){
			$this->user_group='财务部';
		}else{
			header("Content-type: text/html; charset=utf-8");
			redirect("http://".$_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT'],2,'<h1><center>未登录.页面跳转中...</center></h1>');
		}
	}

	function FindMekaMM(){
		$this->title="查找美科成品缴库数据";
		$this->display();
	}

	function FindMekaMMResult(){
		$this->title="查找美科成品缴库数据结果";
		$date_min=I('date_min')?I('date_min'):date('Y-m-d');
		$date_max=I('date_max')?I('date_max'):date('Y-m-d');
		$bat_no = trim(I('bat_no'));
		$select_mm="select a.bat_no,a.prd_no,b.name,cast(sum(a.qty) as numeric(13,2)) qty from DB_MK15.DBO.tf_mm a,DB_MK15.DBO.prdt b where a.prd_no = b.prd_no and upper(a.bat_no) like upper('%".$bat_no."%') and a.mm_dd>='".$date_min."' and a.mm_dd<='".$date_max."' and a.prd_no like '1%' group by a.bat_no,a.prd_no,b.name order by a.bat_no,a.prd_no";
		$mekamm_list = M("tf_mm",Null,"DB_CONFIG1")->query($select_mm);
		if($mekamm_list){
			$this->mekamm_list=$mekamm_list;
			$this->date_min=$date_min;
			$this->date_max=$date_max;
			$this->display();
		}else{
			$this->error("没有找到数据");
		}
	}

	function FindMekaMMSumResult(){
		$this->title="查找美科成品缴库数据对比销售公司入库数据";
		$date_min=I('date_min')?I('date_min'):date('Y-m-d');
		$date_max=I('date_max')?I('date_max'):date('Y-m-d');
		$select_mm="select distinct bat_no from DB_MK15.DBO.tf_mm where mm_dd>='".$date_min."' and mm_dd<='".$date_max."' and prd_no like '1%' ";
		$mekamm_list = M("tf_mm",Null,"DB_CONFIG1")->query($select_mm);
		if(!$mekamm_list){
			$this->error('没有查到美科缴库数据');
		}
		for ($i=0;$i<count($mekamm_list);$i++){
			$bat_no = $mekamm_list[$i]['bat_no'];
			$select_mm_sum="select sum(qty) qty from DB_MK15.DBO.tf_mm where bat_no like '".$bat_no."' and prd_no like '1%'";
			$mekamm_sum = M("tf_mm",Null,"DB_CONFIG1")->query($select_mm_sum);
			if($mekamm_sum){
				$mekamm_list[$i]['mm_qty']=round($mekamm_sum[0]['qty'],2);
			}
			$bat_last3=substr($bat_no,-3);
			if($bat_last3=='-MK' ||$bat_last3=='-TW'){
				$bat_no = substr(trim($mekamm_list[$i]['bat_no']),0,-3);
			}else{
				$bat_no = trim($mekamm_list[$i]['bat_no']);
			}
			$cus_no = 'C19001';
			$select_pc_sum = "select  sum(case when a.ps_id = 'PC' then a.qty when a.ps_id ='PB' then -a.qty end) qty from DB_MKjc.DBO.tf_pss a,db_mkjc.dbo.mf_pss b where a.bat_no like '%".$bat_no."%' and a.ps_no = b.ps_no and b.cus_no = '".$cus_no."' and a.ps_id like 'P%'";

			$jckpc_sum = M("tf_pss",Null,"DB_CONFIG1")->query($select_pc_sum);
			if($jckpc_sum){
				$mekamm_list[$i]['pc_qty']=round($jckpc_sum[0]['qty'],2);
				$mekamm_list[$i]['diff']=round($mekamm_list[$i]['mm_qty']-$mekamm_list[$i]['pc_qty'],2);
			}
		}
		$this->mekamm_list = $mekamm_list;
		$this->date_min=$date_min;
		$this->date_max = $date_max;
		$this->display();		
	}

	
	function FindWarehouseAccountSum(){
		$this->title="查询仓库做账数据总和";
		$bat_no = trim(I('bat_no'));
		$prd_no = I('prd_no');
		if(empty($bat_no)||empty($prd_no)){
			$this->error("没有获取到批号,品号");
		}
		//获取美科成品缴库数据
		$select_mm="select a.bat_no,a.prd_no,b.name,cast(sum(a.qty) as numeric(13,2)) qty from DB_MK15.DBO.tf_mm a,DB_MK15.DBO.prdt b where a.prd_no = b.prd_no and a.prd_no like '".$prd_no."' and a.bat_no like '%".$bat_no."%' group by a.bat_no,a.prd_no,b.name order by a.bat_no,a.prd_no";
		$mekamm_list = M("tf_mm",Null,"DB_CONFIG1")->query($select_mm);
		if($mekamm_list){
			$this->mekamm_list=$mekamm_list;
		}else{
			$this->error("没有查询到美科成品缴库数据");
		}
		//获取销售公司入库数据
		$str_last3=substr($bat_no,-3);
		if($str_last3=='-MK' || $str_last3=='-TW'){
			$bat_no =substr($bat_no,0,-3);
		}
		$list_db=array('DB_MKJC'=>'绍兴美科进出口','DB_JXOK'=>'景德镇欧科电器','DB_OK01'=>'上虞欧科电器','DB_SXMK'=>'绍兴美科科技照明');
		foreach($list_db as $key=>$value){
			if($key=='DB_SXMK'){
				$select_storage="select a.os_no bat_no,a.prd_no,a.prd_name,a.prd_mark,a.wh,cast(sum(case when a.ps_id = 'PC' then a.qty else -a.qty end) as numeric(13,2)) qty from ".$key.".dbo.tf_pss a,".$key.".dbo.mf_pss b where a.ps_no = b.ps_no and upper(a.os_no) like upper('%".$bat_no."%') and (a.ps_id= 'PC' or a.ps_id = 'PB')  group by a.os_no,a.prd_no,a.prd_name,a.wh,a.prd_mark order by a.os_no,a.prd_no ";
			}else{
				$select_storage="select a.bat_no,a.prd_no,a.prd_name,a.prd_mark,a.wh,cast(sum(case when a.ps_id = 'PC' then a.qty else -a.qty end) as numeric(13,2)) qty from ".$key.".dbo.tf_pss a,".$key.".dbo.mf_pss b where a.ps_no = b.ps_no and upper(a.bat_no) like upper('%".$bat_no."%')  and (a.ps_id= 'PC' or a.ps_id = 'PB')  group by a.bat_no,a.prd_no,a.prd_name,a.wh,a.prd_mark order by a.bat_no,a.prd_no ";
			}
			//echo $select_storage;
			$storage_list[$value]=M("tf_pss",Null,"DB_CONFIG1")->query($select_storage);			
		}
		$this->storage_list=$storage_list;
		$this->display();		
	}

	function FindWarehouseAccountDetail(){
		$this->title="查询仓库做账情况明细";
		$list_get['mk_bat_no'] = trim(I('mk_bat_no'));
		$list_get['mk_prd_no']= trim(I('mk_prd_no'));
		$list_get['sale_bat_no']=trim(I('sale_bat_no'));
		$list_get['sale_prd_no']=trim(I('sale_prd_no'));
		$list_get['sale_prd_mark']=trim(I('sale_prd_mark'));
		$list_get['company']=trim(I('company'));
		$qty['mk_qty']=trim(I('mk_qty'));
		$qty['sale_qty']=trim(I('sale_qty'));
		$qty['sale_qty']=I('sale_qty');
		if(empty($list_get['mk_bat_no'])||empty($list_get['mk_prd_no'])||empty($list_get['sale_bat_no'])||empty($list_get['sale_prd_no'])||empty($list_get['company'])){
			$this->error("数据不全");
		}
		//获取美科缴库明细
		$select_mm="select a.mm_no,a.bat_no,a.prd_no,b.name,convert(varchar,a.mm_dd,023) mm_dd,convert(varchar,a.eff_dd,023) eff_dd,cast(a.qty as numeric(13,2)) qty from DB_MK15.DBO.tf_mm a,DB_MK15.DBO.prdt b where a.prd_no = b.prd_no and a.prd_no like '".$list_get['mk_prd_no']."' and upper(a.bat_no) like ('%".$list_get['mk_bat_no']."%') order by a.mm_no,a.mm_dd,a.qty ";
		$mekamm_list = M("tf_mm",Null,"DB_CONFIG1")->query($select_mm);
		if($mekamm_list){
			$this->mekamm_list=$mekamm_list;
		}else{
			$this->error("没有查询到美科成品缴库数据");
		}
		//获取销售公司入库明细
		$list_db=array('DB_MKJC'=>'绍兴美科进出口','DB_JXOK'=>'景德镇欧科电器','DB_OK01'=>'上虞欧科电器','DB_SXMK'=>'绍兴美科科技照明');
		$db=array_search($list_get['company'],$list_db);
		if($db=="DB_SXMK"){
			$select_storage="select a.ps_no,a.os_no bat_no,a.prd_no,a.prd_name,a.prd_mark,a.wh,convert(varchar,a.ps_dd,023) ps_dd,cast((case when a.ps_id = 'PC' then a.qty else -a.qty end) as numeric(13,2)) qty,c.name from ".$db.".dbo.tf_pss a,".$db.".dbo.mf_pss b,TbrSystem.dbo.pswd c where a.ps_no = b.ps_no and upper(a.os_no) like upper('".$list_get['sale_bat_no']."') and prd_no like '".$list_get['sale_prd_no']."' and (a.ps_id= 'PC' or a.ps_id= 'PB') and b.usr=c.usr and c.compno ='".substr($db,3)."'  order by a.ps_no,a.ps_dd,a.qty ";
		}else{
			$select_storage="select a.ps_no,a.bat_no,a.prd_no,a.prd_name,a.prd_mark,a.wh,convert(varchar,a.ps_dd,023) ps_dd,cast((case when a.ps_id = 'PC' then a.qty else -a.qty end) as numeric(13,2)) qty,c.name from ".$db.".dbo.tf_pss a,".$db.".dbo.mf_pss b,TbrSystem.dbo.pswd c where a.ps_no = b.ps_no and a.bat_no like '%".$list_get['sale_bat_no']."' and a.prd_no like '".$list_get['sale_prd_no']."' and a.prd_mark like '".$list_get['sale_prd_mark']."' and (a.ps_id= 'PC' or a.ps_id = 'PB') and b.usr=c.usr and c.compno ='".substr($db,3)."' order by a.ps_no,a.ps_dd,a.qty ";
		}
		$storage_list=M("tf_pss",Null,"DB_CONFIG1")->query($select_storage);
		if($storage_list){
			$this->storage_list=$storage_list;
			$this->company=$list_get['company'];
			$this->qty=$qty;
			$this->display();
		}else{
			$this->error("没有查询到销售公司数据");
		}
	}

	function FindMekaMMDetail(){
		$this->title="查询美科成品缴库明细";
		$bat_no = I('bat_no');
		if(empty($bat_no)){
			$this->error("没有获取到批号");
		}
		$prd_no = I('prd_no');
		//获取美科成品缴库数据
		$select_mm="select a.bat_no,a.prd_no,b.name,a.mm_no,convert(varchar,a.mm_dd,023) mm_dd,convert(varchar,a.eff_dd,023) eff_dd,cast(a.qty as numeric(13,2)) qty from DB_MK15.DBO.tf_mm a,DB_MK15.DBO.prdt b where a.prd_no = b.prd_no  and a.bat_no = '".$bat_no."' and a.prd_no like '".$prd_no."' order by a.bat_no,a.prd_no,a.mm_dd ";
		$mekamm_list = M("tf_mm",Null,"DB_CONFIG1")->query($select_mm);
		
		if($mekamm_list){
			$this->mekamm_list=$mekamm_list;
			$this->display();
		}else{
			$this->error("没有查询到美科成品缴库数据");
		}
	 }
}