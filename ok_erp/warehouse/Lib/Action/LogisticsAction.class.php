<?php
class LogisticsAction extends Action{

	public function _initialize(){
		if(session('user_group')=='factory'){
			$this->user_group='工厂组';
		}else{
			header("Content-type: text/html; charset=utf-8");
			redirect("http://".$_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT'],2,'<h1><center>未登录.页面跳转中...</center></h1>');
		}
	}

	public function InStorage(){
		$this->title="查询近期贸易公司出入库数量";
		$date['今天']=date('Y-m-d');
		$date['昨天']=date("Y-m-d",strtotime("-1 day"));
		$date['前天']=date("Y-m-d",strtotime("-2 day"));
		$this->date=$date;
		$this->display();
	}
	
	function SelectInStorage(){
		$date=I('date_min');
		$state=I('state');
		if(!$date||!$state){
			$this->error('没有日期');
		}
		$cus_no=array('mk'=>'C19001','jx'=>'C19056');
		$user=substr(session('user_name'),0,2);
		$db=array('DB_OK01'=>'上虞欧科电器','DB_MKJC'=>'绍兴美科进出口','DB_JXOK'=>'景德镇欧科','DB_SXMK'=>'绍兴美科照明科技');
		$instorage=M('');
		$instorage->db(1,"mssql://sa:mkdq@192.168.1.221/DB_OK01");
		foreach($db as $key=>$value){
			if($key!='DB_SXMK'){
				$instorage_data[$value]=$instorage->query("select a.bat_no,a.ps_no,a.prd_no,a.prd_name,a.prd_mark,convert(numeric(20,2),a.qty) qty,convert(varchar,a.eff_dd,023) eff_dd from ".$key.".dbo.tf_pss a left join ".$key.".dbo.tf_pos b on a.bat_no = b.bat_no and a.prd_no = b.prd_no and a.prd_mark=b.prd_mark left join ".$key.".dbo.mf_pos c on b.os_no =c.os_no where convert(varchar,a.eff_dd,023)='".$date."' and a.ps_id= '".$state."'
 			and b.os_id='PO' and c.cus_no ='".$cus_no[$user]."' and b.qty_ps>0 ");
			}else{
				$cus_no=array('mk'=>'C19058','jx'=>'C19056');
				$instorage_data[$value]=$instorage->query("select a.os_no bat_no,a.ps_no,a.prd_no,a.prd_name,a.prd_mark,convert(numeric(20,2),a.qty) qty,convert(varchar,a.eff_dd,023) eff_dd from ".$key.".dbo.tf_pss a left join ".$key.".dbo.mf_pss b on a.ps_no = b.ps_no  where convert(varchar,a.eff_dd,023)='".$date."' and a.ps_id= 'PC' and b.cus_no ='".$cus_no[$user]."' ");
			}
			
		}
		if($instorage_data){
			$this->instorage_data=$instorage_data;
			$this->display();
		}else{
			$this->error('没有数据');
		}		
	}

	public function IntervalStorage(){
		$this->title="查询贸易公司出入库数量";
		$this->display();
	}
	
	function SelectIntervalStorage(){
		$date_min=I('date_min')?I('date_min'):date("Y-m-d",strtotime("-7 day"));
		$date_max=I('date_max')?I('date_max'):date("Y-m-d");
		$company=I('company');
		$state=I('state');
		
		if(!$company||!$state){
			$this->error('没有选择公司或出入库');
		}
		$cus_no=array('mk'=>'C19001','jx'=>'C19056');
		$user=substr(session('user_name'),0,2);
		$db=array('DB_OK01'=>'上虞欧科电器','DB_MKJC'=>'绍兴美科进出口','DB_JXOK'=>'景德镇欧科','DB_SXMK'=>'绍兴美科照明科技');		
		$intervalstorage=M('');
		$intervalstorage->db(1,"mssql://sa:mkdq@192.168.1.221/DB_OK01");
		if($company!='DB_SXMK'){
			$select="select a.bat_no,a.ps_no,a.prd_no,a.prd_name,a.prd_mark,convert(numeric(20,2),a.qty) qty,convert(varchar,a.eff_dd,023) eff_dd from ".$company.".dbo.tf_pss a left join ".$company.".dbo.tf_pos b on a.bat_no = b.bat_no and a.prd_no = b.prd_no and a.prd_mark=b.prd_mark left join ".$company.".dbo.mf_pos c on b.os_no =c.os_no where convert(varchar,a.eff_dd,023)>='".$date_min."' and convert(varchar,a.eff_dd,023)<='".$date_max."'  and a.ps_id= '".$state."'
 			and b.os_id='PO' and c.cus_no ='".$cus_no[$user]."' and b.qty_ps>0 ";
			
		}else{
			$cus_no=array('mk'=>'C19058','jx'=>'C19056');
			$select="select a.os_no bat_no,a.ps_no,a.prd_no,a.prd_name,a.prd_mark,convert(numeric(20,2),a.qty) qty,convert(varchar,a.eff_dd,023) eff_dd from ".$company.".dbo.tf_pss a left join ".$company.".dbo.mf_pss b on a.ps_no = b.ps_no  where convert(varchar,a.eff_dd,023)>='".$date_min."' and  convert(varchar,a.eff_dd,023)<='".$date_max."' and a.ps_id= '".$state."' and b.cus_no ='".$cus_no[$user]."'";
		}
		
		$intervalstorage_data=$intervalstorage->query($select);
		if($intervalstorage_data){
			$this->company_name=$db[$company];
			$this->intervalstorage_data=$intervalstorage_data;
			$this->display();
		}else{
			echo "没有数据";
		}		
	}


	function DownloadStorage(){
		@ini_set('memory_limit','128M');
		set_time_limit(0);
		import('ORG.Util.PHPExcel');
		load("@.functions");
		$date_min=I('date_min')?I('date_min'):date("Y-m-d",strtotime("-7 day"));
		$date_max=I('date_max')?I('date_max'):date("Y-m-d");
		$company=I('company');
		$state=I('state');
		if(!$company||!$state){
			$this->error('没有选择公司或出入库');
		}
		$cus_no=array('mk'=>'C19001','jx'=>'C19056');
		$user=substr(session('user_name'),0,2);
		$db=array('DB_OK01'=>'上虞欧科电器','DB_MKJC'=>'绍兴美科进出口','DB_JXOK'=>'景德镇欧科','DB_SXMK'=>'绍兴美科照明科技');		
		$intervalstorage=M('');
		$intervalstorage->db(1,"mssql://sa:mkdq@192.168.1.221/DB_OK01");
		if($company!='DB_SXMK'){
			$select="select a.bat_no,a.ps_no,a.prd_no,a.prd_name,a.prd_mark,convert(numeric(20,2),a.qty) qty,convert(varchar,a.eff_dd,023) eff_dd from ".$company.".dbo.tf_pss a left join ".$company.".dbo.tf_pos b on a.bat_no = b.bat_no and a.prd_no = b.prd_no and a.prd_mark=b.prd_mark left join ".$company.".dbo.mf_pos c on b.os_no =c.os_no where convert(varchar,a.eff_dd,023)>='".$date_min."' and convert(varchar,a.eff_dd,023)<='".$date_max."'  and a.ps_id= '".$state."'
 			and b.os_id='PO' and c.cus_no ='".$cus_no[$user]."' and b.qty_ps>0 ";
			
		}else{
			$cus_no=array('mk'=>'C19058','jx'=>'C19056');
			$select="select a.os_no bat_no,a.ps_no,a.prd_no,a.prd_name,a.prd_mark,convert(numeric(20,2),a.qty) qty,convert(varchar,a.eff_dd,023) eff_dd from ".$company.".dbo.tf_pss a left join ".$company.".dbo.mf_pss b on a.ps_no = b.ps_no  where convert(varchar,a.eff_dd,023)>='".$date_min."' and  convert(varchar,a.eff_dd,023)<='".$date_max."' and a.ps_id= '".$state."' and b.cus_no ='".$cus_no[$user]."' ";
		}
		$intervalstorage_data=$intervalstorage->query($select);
		if($intervalstorage_data){
			$objPHPExcel= new PhpExcel();
			getPhpExcelObjWriter($intervalstorage_data,$db[$company].$date_min."---".$date_max,$objPHPExcel);
		}else{
			$this->error("查询不到数据");
		}
	}
}