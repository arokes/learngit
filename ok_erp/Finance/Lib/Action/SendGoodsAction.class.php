<?php
load('@.functions');
class SendGoodsAction extends Action{
	private $db=array("DB_MKJC"=>"绍兴美科电器进出口","DB_OK01"=>"上虞欧科电器","DB_JXOK"=>"景德镇欧科电器","DB_SXMK"=>"绍兴美科照明科技");
	private $factory_db_array=array("DB_MK15"=>"浙江美科电器","DB_JX16"=>"江西美科光电科技","DB_JM17"=>"江门江海美科电器");
	private $bill_type=array('PC'=>'采购入库单','PB'=>'采购退回单','SA'=>'销售出库单','SB'=>'销售退回单','LP'=>'未记账收票单','LZ'=>'未记账开票单');
	private $zhang_type=array('1'=>'记应付帐','2'=>'不立账','3'=>'开票记账');
	private $tax_type=array('1'=>'不计税','2'=>'含税价','3'=>'不含税价');
	public function _initialize(){
		if(session('user_group')=='finance'){
			$this->user_group='财务部';
		}else{
			header("Content-type: text/html; charset=utf-8");
			redirect("http://".$_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT'],2,'<h1><center>未登录.页面跳转中...</center></h1>');
		}
	}

	function UploadFile(){
		$this->title='上传文件';
		$this->display();
	}

	function UploadSendGoods(){
		header("Content-type: text/html; charset=utf-8");
		import('ORG.Net.UploadFile');//导入上传文件类
		import('ORG.Util.PHPExcel');//导入PHPEXCEL类
		//实例化上传文件类
		$upload = new UploadFile();
		$upload->maxSize = 3145728;
		$upload->allowExts = array('xls','xlsx');
		$upload->savePath='D:/Upload/';
		if(!$upload->upload()){
			$this->error($upload->getErrorMsg());
		}else{
			$info=$upload->getUploadFileInfo();
		}
		$filePath=$info[0]['savepath'].$info[0]['savename'];
		//实例化phpexcel类
		$phpExcel = new PHPExcel();
		$PHPReader = PHPExcel_IOFactory::createReaderForFile($filePath);
		$PHPExcel = $PHPReader->load($filePath);
		$currentSheet= $PHPExcel->getSheet(0);
		$allColumn = $currentSheet->getHighestColumn();
		$allRow = $currentSheet->getHighestRow();		
		//$fp=file($info[0]['savepath'].$info[0]['savename']);
		
		$sendgoods=M('sendgoods');//实例化数据表
		$sendgoods->query('delete from sendgoods');//删除原表中数据
		for($i=2;$i<=$allRow;$i++){
			$row="A".$i.":".$allColumn.$i."";
			$str=$currentSheet->rangeToArray($pRange = $row,null,true,false);
			$data['company_name']=$str[0][0];
			$data['cust_name']=$str[0][1];
			$data['so_no']=$str[0][2];
			$data['prd_no']=$str[0][3];
			$data['prd_name']=$str[0][4];
			$data['ps_no']=$str[0][5];
			$data['ps_dd']=$str[0][6];
			$data['out_qty']=$str[0][7];
			$data['record_dd']=date('Y-m-d h:i:s');
			//dump($data);
			$count[]=$sendgoods->add($data);
		}
		if($count){
			$this->success('上传成功');
		}else{
			$this->error('上传失败');
		}
		
	}

	function SelectStock(){
		$this->title="查询订单库存";
		$this->display();
	}

	function SelectStockResult($bat_no=null){
		$this->title="查询订单库存";
		$bat_no=I('bat_no','');
		if(!$bat_no){
			$this->error('请输入订单号');
			exit;
		}
		$prd_mark=trim(I('prd_mark'));
		$parameter=I('parameter');
		$para_arr=explode(' ',trim($parameter));
		$stock=M("tf_pss",Null,"DB_CONFIG1");
		//$select=null;
		foreach ($this->db as $key=>$company_name){
			$select="select bat_no,prd_no,prd_name,prd_mark,sum(case when ps_id='PC' then isnull(qty,0) when ps_id='PB' then isnull(-qty,0) end) pc,sum(case when ps_id='SA' then isnull(qty,0) when ps_id='SB' then isnull(-qty,0) end) sa,sum(case when ps_id='PC' then isnull(qty,0) when ps_id='SA' then isnull(-qty,0) when ps_id='PB' then isnull(-qty,0) when ps_id='SB' then isnull(qty,0) else 0 end) stock from ".$key.".dbo.tf_pss where upper(bat_no) like upper('%".$bat_no."%') and upper(prd_mark) like upper('%".$prd_mark."%')";
			for($i=0;$i<count($para_arr);$i++){
				$select_add=" and upper(prd_name) like upper('%".$para_arr[$i]."%')";
				$select=$select.$select_add;
			}
			$select_group="group by bat_no,prd_no,prd_name,prd_mark order by bat_no,prd_mark,prd_no";
			
			$select=$select.$select_group;
			$stock_data[$company_name]=$stock->query(iconv("UTF-8","GBK",$select));
		}
		if($stock_data){
			$this->stock_data=$stock_data;
			$this->display();
		}else{
			echo "没有数据";
		}
	}

	function SelectStockDetailResult($bat_no=null,$prd_no=null){
		header("Content-type: text/html; charset=utf-8");
		$this->title="查询存货明细帐";
		$bat_no=trim(I('bat_no'));
		$prd_no=trim(I('prd_no'));
		$prd_mark=sprintf("%s%s",'%',trim(I('prd_mark')));
		if(!$bat_no || !$prd_no){
			echo "请输入批号及品号";
			exit;
		}
		$stock=M("tf_pss",Null,"DB_CONFIG1");
		foreach ($this->db as $key=>$company_name){
			if($key=='DB_SXMK'){
				$select=sprintf("select ps_id,os_no bat_no,prd_no,prd_name,ps_no,convert(varchar,eff_dd,023) eff_dd,isnull(case when ps_id='PC' then qty when ps_id='PB' then -qty end,0) pc_qty, isnull(case when ps_id='PC' then qty_fp when ps_id='PB' then -qty_fp end,0) pc_fp_qty from %s.dbo.tf_pss where prd_no like '%s' and (os_no like '%s' or os_no in (select ps_no from tf_pss where os_no like '%s'))",$key,$prd_no,$bat_no,$bat_no);
			}else{
				$select =iconv("UTF-8","GBK","select ps_id,bat_no,prd_no,prd_name,prd_mark,ps_no,convert(varchar,eff_dd,023) eff_dd,isnull(case when ps_id='PC' then qty when ps_id='PB' then -qty end,0) pc_qty,isnull(case when ps_id='PC' then qty_fp when ps_id='PB' then -qty_fp end,0) pc_fp_qty,inv_no,isnull(case when ps_id='SA' then qty when ps_id='SB' then -qty end,0) sa_qty,isnull(case when ps_id='SA' then qty_fp when ps_id='SB' then -qty_fp end,0) sa_fp_qty,case when ps_id='SA' then amt when ps_id='SB' then -AMT end sa_amt from ".$key.".dbo.tf_pss where upper(bat_no) like upper('".$bat_no."') and prd_no like '".$prd_no."' and prd_mark like '".$prd_mark."' and (ps_id= 'SA' or ps_id='SB' or ps_id='PC' or ps_id='PB') order by prd_no,prd_mark,eff_dd,ps_id ");
			}
			if($result=$stock->query($select)){
				$stock_data[$company_name]=$result;
			}
			
		}
		if($stock_data){
			$this->stock_data=$stock_data;
			$this->display();
		}else{
			echo "没有数据";
		}
	}

	function SelectPsBillDetail($ps_no = null,$ps_id=null,$company_name=null){
		$this->title="查询出入库单据";
		$ps_no = I('ps_no');
		$ps_id = I('ps_id');
		$company_name = I('company_name');
		if($ps_no==null&&$ps_id==null){
			$this->error('没有单号或单据类别');
		}
		$db_no=array_search($company_name,$this->db);
		$select_mf=sprintf("select a.ps_no,convert(varchar,a.ps_dd,023) ps_dd,a.cus_no,b.name cus_name,a.inv_no,a.os_no,a.zhang_id,a.tax_id,a.cur_id,a.exc_rto,a.usr,convert(varchar,a.eff_dd,023) eff_dd,convert(varchar,a.record_dd,023) record_dd,c.name usr_name from %s.dbo.mf_pss a,%s.dbo.cust b,Tbrsystem.dbo.pswd c where a.cus_no = b.cus_no and a.ps_no='%s' and a.ps_id = '%s' and a.usr = c.usr and c.compno='%s'",$db_no,$db_no,$ps_no,$ps_id,substr($db_no,3));
		if($db_no == 'DB_SXMK'){
			$select_tf=sprintf("select ps_no,ps_id,os_no bat_no,prd_no,prd_mark,prd_name,qty,qty_fp,qty_rtn,amt,amtn_net,tax from %s.dbo.tf_pss where ps_no='%s' and ps_id = '%s'",$db_no,$ps_no,$ps_id);
		}else{
			$select_tf=sprintf("select ps_no,ps_id,bat_no,prd_no,prd_mark,prd_name,qty,qty_fp,qty_rtn,amt,amtn_net,tax from %s.dbo.tf_pss where ps_no='%s' and ps_id = '%s'",$db_no,$ps_no,$ps_id);
		}		
		
		$billdetail=M("tf_pss",Null,"DB_CONFIG1");
		$billdetail_mf=$billdetail->query($select_mf);
		$billdetail_tf=$billdetail->query($select_tf);
		if($billdetail_mf&&$billdetail_tf){
			$this->billdetail_mf=$billdetail_mf;
			$this->billdetail_tf=$billdetail_tf;
			$this->company_name=$company_name;
			$this->bill_name=$this->bill_type[$ps_id];
			$this->zhang_name=$this->zhang_type[$billdetail_mf[0]['zhang_id']];
			$this->tax_name=$this->tax_type[$billdetail_mf[0]['tax_id']];
			$this->display();
		}else{
			$this->error('查询不到数据');
		}
	}

	function SelectFactoryStockDetail(){
		$this->title="查询工厂库存明细";
		$factory_db=I('factory_db');
		$bat_no="%".trim(I('bat_no'));
		$prd_no=I('prd_no');
		if(empty($bat_no)){
			$this->error('没有批号');
		}
		//实例化数据库连接
		$db_content=M("tf_mm",Null,"DB_CONFIG1");
		//查询成品缴库明细
		$select_mm=sprintf("select 'MM' ps_id,a.mm_no ps_no,convert(varchar,a.eff_dd,023) eff_dd,a.bat_no,a.prd_no,b.name prd_name,a.qty pc_qty,'' sa_qty,'' sa_amtn_net,'' qty_fp from %s.dbo.tf_mm a,%s.dbo.prdt b where a.prd_no = b.prd_no and a.bat_no like '%s' and a.prd_no like '%s'",$factory_db,$factory_db,$bat_no,$prd_no);
		$select_ps=sprintf("select ps_id,ps_no,convert(varchar,eff_dd,023) eff_dd,bat_no,prd_no,prd_name,case when ps_id = 'PC' then qty when ps_id = 'PB' then -qty end pc_qty,case when ps_id = 'SA' then qty when ps_id= 'SB' then -qty end sa_qty,case when ps_id = 'SA' then amtn_net when ps_id = 'SB' then -amtn_net end sa_amtn_net,qty_fp from %s.dbo.tf_pss where bat_no like '%s' and prd_no like '%s'",$factory_db,$bat_no,$prd_no);
		$list_mm_result=$db_content->query($select_mm);
		$list_ps_result=$db_content->query($select_ps);
		$list_result=array_merge($list_mm_result,$list_ps_result);
		if(empty($list_result)){
			$this->error('查询不到数据');
		}
		$date=array();
		$ps_id=array();
		$prd_no=array();
		foreach($list_result as $value){
			$date[]=$value['eff_dd'];
			$ps_id[]=$value['ps_id'];
			$prd_no[]=$value['prd_no'];
		}
		//对合并查询结果进行排序，按日期和单据类别
		array_multisort($prd_no,SORT_ASC,$date,SORT_ASC,$ps_id,SORT_ASC,$list_result);
		$this->list_result=$list_result;
		$this->factory_name=$this->factory_db_array[$factory_db];
		$this->display();
	}

	function SelectFactoryBillDetail(){
		$this->title="查询缴库出库单据";
		$ps_no = I('ps_no');
		$ps_id = I('ps_id');
		$company_name = I('factory_name');
		if($ps_no==null&&$ps_id==null){
			$this->error('没有单号或单据类别');
		}
		$db_no=array_search($factory_name,$this->db);
		if($ps_id=='MM'){
			$select_mf=sprintf("select a.mm_no ps_no,convert(varchar,a.eff_dd,023) ps_dd ,'' cus_name,");
		}
			$select_mf=sprintf("select a.ps_no,convert(varchar,a.ps_dd,023) ps_dd,a.cus_no,b.name cus_name,a.inv_no,a.os_no,a.zhang_id,a.tax_id,a.cur_id,a.exc_rto,a.usr,convert(varchar,a.eff_dd,023) eff_dd,convert(varchar,a.record_dd,023) record_dd,c.name usr_name from %s.dbo.mf_pss a,%s.dbo.cust b,Tbrsystem.dbo.pswd c where a.cus_no = b.cus_no and a.ps_no='%s' and a.ps_id = '%s' and a.usr = c.usr and c.compno='%s'",$db_no,$db_no,$ps_no,$ps_id,substr($db_no,3));
			$select_tf=sprintf("select bat_no,prd_no,prd_mark,prd_name,qty,qty_fp,qty_rtn,amt,amtn_net,tax from %s.dbo.tf_pss where ps_no='%s' and ps_id = '%s'",$db_no,$ps_no,$ps_id);	
		
		$billdetail=M("tf_pss",Null,"DB_CONFIG1");
		$billdetail_mf=$billdetail->query($select_mf);
		$billdetail_tf=$billdetail->query($select_tf);
		if($billdetail_mf&&$billdetail_tf){
			$this->billdetail_mf=$billdetail_mf;
			$this->billdetail_tf=$billdetail_tf;
			$this->factory_name=$factory_name;
			$this->bill_name=$this->bill_type[$ps_id];
			$this->zhang_name=$this->zhang_type[$billdetail_mf[0]['zhang_id']];
			$this->tax_name=$this->tax_type[$billdetail_mf[0]['tax_id']];
			$this->display();
		}else{
			$this->error('查询不到数据');
		}
	}


}