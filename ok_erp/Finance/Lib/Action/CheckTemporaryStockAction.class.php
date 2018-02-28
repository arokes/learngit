<?php
load("@.functions");
class CheckTemporaryStockAction extends Action{
	private $factory_db_array=array("DB_MK15"=>"浙江美科电器","DB_JX16"=>"江西美科光电科技","DB_JM17"=>"江门江海美科电器");
	private $company_db_array=array("DB_MKJC"=>"绍兴美科电器进出口","DB_OK01"=>"上虞欧科电器","DB_JXOK"=>"景德镇欧科电器","DB_SXMK"=>"绍兴美科照明科技");
	public function _initialize(){
		if(session('user_group')=='finance'){
			$this->user_group='财务部';
		}else{
			header("Content-type: text/html; charset=utf-8");
			redirect("http://".$_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT'],2,'<h1><center>未登录.页面跳转中...</center></h1>');
		}
	}

	public function SetParam(){
		$this->title="选择公司批号参数";
		$this->display();
	}

	public function FindTemporaryStockSumResult(){
		$this->title="查找账套批号汇总数据";
		$bat_no_real = trim(I('bat_no'));
		if(empty($bat_no_real)){
			$this->error("请填写批号");
		}
		$bat_no = "%".$bat_no_real."%";
		$factory=trim(I("factory"));
		$factory_db=$factory.".dbo.";
		$company=trim(I("company"));
		$company_db=$company.".dbo.";
		$param=trim(I("param"));
		$arr_param=explode(' ',$param);
		$date_max=I("date_max")?I("date_max"):"2999-01-01";
		//查询工厂数据
		//查询订单
		$select_so = "select bat_no,prd_no,prd_name,cast(sum(case when os_id = 'SO' then qty else -qty end) as numeric(13,2)) so_qty from ".$factory_db."tf_pos where bat_no like upper('".$bat_no."') and os_id like 'S%'";
		$select_so_prd="select prd_no from ".$factory_db."tf_pos where bat_no like upper('".$bat_no."') and os_id = 'SO'";
		foreach($arr_param as $value){
			$prd_name=iconv("utf-8","GBK"," and prd_name like upper('%".$value."%') ");
			$select_so = $select_so.$prd_name;
			$select_so_prd = $select_so_prd.$prd_name;
		}
		$select_so=$select_so." group by bat_no,prd_no,prd_name";
		$factory_so_list = M("tf_pos",Null,"DB_CONFIG1")->query($select_so);
		//删除数量为0订单
		for($i=0;$i<count($factory_so_list);$i++){
			if($factory_so_list[$i]['so_qty']==0){
				unset($factory_so_list[$i]);
			}
		}
		$factory_so_list=array_values($factory_so_list);
		//查询缴库
		$select_mm = "select ltrim(bat_no) bat_no,prd_no,sum(cast(qty as numeric(13,2))) mm_qty from ".$factory_db."tf_mm where bat_no like upper('%".$bat_no."') and convert(varchar,eff_dd,023)<='".$date_max."' and prd_no in (".$select_so_prd.") group by ltrim(bat_no),prd_no ";
		$factory_mm_list=M("tf_mm",Null,"DB_CONFIG1")->query($select_mm);
		//查询出库
		$select_sa = "select bat_no,prd_no,sum(case when ps_id = 'SA' then  cast(qty as numeric(13,2)) when ps_id = 'SB' then cast(-qty as numeric(13,2)) end) sa_qty from ".$factory_db."tf_pss where bat_no like upper('".$bat_no."') and ps_id like 'S%' and convert(varchar,eff_dd,023)<='".$date_max."' and prd_no in (".$select_so_prd.") group by bat_no,prd_no ";
		$factory_sa_list=M("tf_mm",Null,"DB_CONFIG1")->query($select_sa);
		//查询开票
		$select_lz = "select a.bat_no,a.prd_no,sum(cast(a.qty as numeric(13,2))) lz_qty,sum(cast(a.amtn_net as numeric(13,2))) lz_amtn_net from ".$factory_db."tf_lz a,".$factory_db."mf_lz b where a.lz_no = b.lz_no and  a.bat_no like upper('".$bat_no."') and convert(varchar,b.eff_dd,023)<='".$date_max."' and a.prd_no in (".$select_so_prd.") group by a.bat_no,a.prd_no ";
		$factory_lz_list=M("tf_lz",Null,"DB_CONFIG1")->query($select_lz);
		//合并工厂数据
		$factory_list=merge_bat_prd(merge_bat_prd(merge_bat_prd($factory_so_list,$factory_mm_list),$factory_sa_list),$factory_lz_list);
		//查询贸易公司数据
		//科技公司不按批号查询
		if($company=='DB_SXMK'){
			//查询科技入库订单
			$select_pc = sprintf("select '%s' bat_no,prd_no,prd_name,prd_mark,sum(case when ps_id='PC' then cast(qty as numeric(13,2)) when ps_id ='PB' then cast(-qty as numeric(13,2)) end) pc_qty from %stf_pss where (upper(os_no) like upper('%s') or os_no in (select ps_no from %stf_pss where upper(os_no) like upper('%s'))) and convert(varchar,eff_dd,023)<='%s'",$bat_no_real,$company_db,$bat_no_real,$company_db,$bat_no_real,$date_max);
			foreach($arr_param as $value){
				$prd_name=iconv("utf-8","GBK"," and prd_name like upper('%".$value."%') ");
				$select_pc = $select_pc.$prd_name;
			}	
			$select_pc=$select_pc." group by prd_no,prd_name,prd_mark order by prd_no";
			
			$company_pc_list=M("tf_pss",Null,"DB_CONFIG1")->query($select_pc);
			//查询收票数据
			$select_lz1 = sprintf("select '%s' bat_no,a.prd_no,a.prd_name,a.prd_mark,sum(cast(a.qty as numeric(13,2))) lz1_qty,sum(cast(a.amtn_net as numeric(13,2))) lz1_amtn_net from %stf_lz1 a,%smf_lz1 b where a.lz_no = b.lz_no and convert(varchar,b.eff_dd,023)<='%s' and (a.os_no like '%s' or a.os_no in (select ps_no from %stf_pss where upper(os_no) like upper('%s'))) group by a.prd_no,a.prd_name,a.prd_mark",$bat_no_real,$company_db,$company_db,$date_max,$bat_no_real,$company_db,$bat_no_real);
			
			$company_lz1_list=M("tf_pss",Null,"DB_CONFIG1")->query($select_lz1);
			//合并科技公司数据
			$company_list=merge_bat_prd($company_pc_list,$company_lz1_list);
		}else{
			//查询外贸公司
			//查询入库数据
			$select_pc = "select bat_no,prd_no,prd_name,prd_mark,sum(case when ps_id = 'PC' then cast(qty as numeric(13,2)) when ps_id = 'PB' then cast(-qty as numeric(13,2)) end) pc_qty from ".$company_db."tf_pss where bat_no like upper('".$bat_no."') and convert(varchar,eff_dd,023)<='".$date_max."' ";
			foreach($arr_param as $value){
				$prd_name=iconv("utf-8","GBK"," and prd_name like upper('%".$value."%') ");
				$select_pc = $select_pc.$prd_name;
			}
			$select_pc=$select_pc." group by bat_no,prd_no,prd_name,prd_mark order by bat_no,prd_no,prd_mark";
			$company_pc_list=M("tf_pss",Null,"DB_CONFIG1")->query($select_pc);

			//查询收票数据
			$select_lz1 = "select a.bat_no,a.prd_no,a.prd_mark,sum(cast(a.qty as numeric(13,2))) lz1_qty,sum(cast(a.amtn_net as numeric(13,2))) lz1_amtn_net from ".$company_db."tf_lz1 a,".$company_db."mf_lz1 b where a.bat_no like upper('".$bat_no."') and a.lz_no = b.lz_no and convert(varchar,b.eff_dd,023)<='".$date_max."' group by a.bat_no,a.prd_no,a.prd_mark";

			$company_lz1_list=M("tf_pss",Null,"DB_CONFIG1")->query($select_lz1);
			//查询出库数据
			$select_sa = "select bat_no,prd_no,prd_name,prd_mark,sum(case when ps_id = 'SA' then cast(qty as numeric(13,2)) when ps_id = 'SB' then cast(-qty as numeric(13,2)) end) sa_qty from ".$company_db."tf_pss where bat_no like upper('".$bat_no."') and convert(varchar,eff_dd,023)<='".$date_max."' group by bat_no,prd_no,prd_name,prd_mark";
			$company_sa_list=M("tf_pss",Null,"DB_CONFIG1")->query($select_sa);

			//查询开票数据
			$select_lz = "select a.bat_no,a.prd_no,a.prd_mark,sum(cast(a.qty as numeric(13,2))) lz_qty,sum(cast(a.amtn_net as numeric(13,2))) lz_amtn_net from ".$company_db."tf_lz a,".$company_db."mf_lz b where a.bat_no like upper('".$bat_no."') and a.lz_no = b.lz_no and convert(varchar,b.eff_dd,023)<='".$date_max."' group by a.bat_no,a.prd_no,a.prd_mark";
			$company_lz_list=M("tf_pss",Null,"DB_CONFIG1")->query($select_lz);
			//合并贸易公司数据
			$company_list=merge_bat_prd(merge_bat_prd(merge_bat_prd($company_pc_list,$company_lz1_list),$company_sa_list),$company_lz_list);
		}
		
		if(!empty($factory_list)){
			$this->factory_list=$factory_list;
		}else{
			$this->error("未查询到工厂数据");
		}
		if(!empty($company_list)){
			$other_param['factory_name']=$this->factory_db_array[$factory];
			$other_param['factory_db']=$factory;
			$other_param['company_name']=$this->company_db_array[$company];
			$other_param['company_db']=$company;
			$other_param['date_max']=$date_max;
			$this->company_list=$company_list;
			$this->other_param=$other_param;
			$this->display();
		}else{
			$this->error("未查询到公司数据");
		}
	}

	function FindTemporaryStockDetailResult(){
		//header("Content-type: text/html; charset=utf-8");
		$this->title="对比开票数据明细";
		$factory_param=I('factory_param');
		$company_param=I('company_param');
		if(empty($factory_param)||empty($company_param)){
			$this->error('没有可查询的参数');
		}
		$factory_param_array=explode(';',$factory_param);
		$company_param_array=explode(';',$company_param);
		$factory['db']=$factory_param_array[0];
		$factory['bat_no']=$factory_param_array[1];
		$factory['prd_no']=$factory_param_array[2];
		$company['db']=$company_param_array[0];
		$company['bat_no']=$company_param_array[1];
		$company['prd_no']=$company_param_array[2];
		$select_factory=sprintf("select b.inv_no ,b.lz_no fc_lz_no,convert(varchar,b.eff_dd,023) fc_eff_dd,b.rem fc_rem,a.bat_no fc_bat_no,a.prd_no fc_prd_no,a.prd_name fc_prd_name,cast(sum(a.qty) as numeric(13,2)) fc_qty,cast(sum(a.amtn_net) as numeric(13,2)) fc_amtn_net from %s.dbo.tf_lz a,%s.dbo.mf_lz b where a.lz_no = b.lz_no and  a.bat_no='%s' and a.prd_no ='%s' and b.amtn_net!=0 group by b.inv_no,b.lz_no,b.rem,a.bat_no,a.prd_no,a.prd_name,b.eff_dd",$factory['db'],$factory['db'],$factory['bat_no'],$factory['prd_no']);
		if($company['db']=='DB_SXMK'){
			$select_company=sprintf("select b.inv_no ,b.lz_no com_lz_no,convert(varchar,b.eff_dd,023) com_eff_dd,b.rem com_rem,'%s' bat_no,a.prd_no prd_no,a.prd_name prd_name,a.prd_mark prd_mark,cast(sum(a.qty) as numeric(13,2) ) com_qty,cast(sum(a.amtn_net) as numeric(13,2)) com_amtn_net from %s.dbo.tf_lz1 a,%s.dbo.mf_lz1 b where a.lz_no = b.lz_no and  a.ps_no in (select ps_no from %s.dbo.tf_pss where os_no like '%s' or os_no in (select ps_no from %s.dbo.tf_pss where os_no like '%s'))and a.prd_no ='%s' and b.amtn_net!=0 group by b.inv_no,b.lz_no,b.rem,a.bat_no,a.prd_no,a.prd_name,a.prd_mark,B.EFF_DD",$company['bat_no'],$company['db'],$company['db'],$company['db'],$company['bat_no'],$company['db'],$company['bat_no'],iconv("UTF-8","GBK",$company['prd_no']));
		}else{
			$select_company=sprintf("select b.inv_no ,b.lz_no com_lz_no,convert(varchar,b.eff_dd,023) com_eff_dd,b.rem com_rem,a.bat_no bat_no,a.prd_no prd_no,a.prd_name prd_name,cast(sum(a.qty) as numeric(13,2) ) com_qty,cast(sum(a.amtn_net) as numeric(13,2)) com_amtn_net from %s.dbo.tf_lz1 a,%s.dbo.mf_lz1 b where a.lz_no = b.lz_no and  a.bat_no='%s' and a.prd_no like '%s%s' and b.amtn_net!=0 group by b.inv_no,b.lz_no,b.rem,a.bat_no,a.prd_no,a.prd_name,b.eff_dd",$company['db'],$company['db'],$company['bat_no'],'%',$company['prd_no'],iconv("UTF-8","GBK"));
		}
		//echo $select_company;
		$db_content=M("tf_pos",Null,"DB_CONFIG1");
		$factory_lz_result=$db_content->query($select_factory);
		$company_lz1_result=$db_content->query($select_company);
		//dump($factory_lz_result);

			$check_result=merge_lz($factory_lz_result,$company_lz1_result);

			$this->check_result=$check_result;
			$this->company_result=$company_lz1_result;
			$this->factory_name=$this->factory_db_array[$factory['db']];
			$this->company_name=$this->company_db_array[$company['db']];
			$this->display();
		
	}

	function FindPoDetail($bat_no = null,$prd_no=null,$prd_mark=null){
		$this->title="查询贸易公司采购订单";
		$bat_no=I('bat_no');
		$prd_no = I('prd_no');
		if(empty($bat_no)){
			$this->error('没有批号');
		}
		if(substr($prd_no,0,1)=='X'){
			$prd_no = sprintf('%s%s','%',substr($prd_no,1));
		}
		$billdetail_po = M("tf_pos",Null,"DB_CONFIG1");
		foreach($this->company_db_array as $key=>$value){
			if($key=='DB_SXMK'){
				$select =sprintf("select c.name,a.os_no bat_no,a.prd_no,a.prd_name,a.prd_mark,a.wh,a.qty,a.qty_pre,a.qty_ps from %s.dbo.tf_pos a,%s.dbo.mf_pos b,%s.dbo.cust c where a.os_no = b.os_no and c.cus_no = b.cus_no and upper(a.os_no) like upper('%s%s') and a.prd_no like '%s%s' and a.os_id = 'PO'",$key,$key,$key,'%',$bat_no,'%',$prd_no);
			}else{
				$select=sprintf("select c.name,a.bat_no,a.prd_no,a.prd_name,a.prd_mark,a.wh,a.qty,a.qty_pre,a.qty_ps from %s.dbo.tf_pos a,%s.dbo.mf_pos b,%s.dbo.cust c where a.os_no = b.os_no and c.cus_no = b.cus_no and upper(a.bat_no) like upper('%s%s') and a.prd_no like '%s'  and a.os_id = 'PO' and a.os_id = b.os_id",$key,$key,$key,'%',$bat_no,$prd_no);
			}
			//echo $select;
			$result=$billdetail_po->query($select);
			if($result){
				$billdetail_po_result[$value]=$result;
			}			
		}
		if($billdetail_po_result){
			$this->billdetail_po_result = $billdetail_po_result;
			$this->display();
		}else{
			$this->error('查询不到数据');
		}
	}

	function FindNakedDetail($bat_no=null,$prd_no=null){
		$this->title="查询工厂裸灯数量";
		$bat_no =trim(I('bat_no'));
		$prd_no = trim(I('prd_no'));
		if(empty($bat_no)){
			$this->error('没有批号');
		}
		$billdetail_naked = M("tf_pos",Null,"DB_CONFIG1");
		foreach($this->factory_db_array as $key=>$value){
			$select=sprintf("select bat_no,mrp_no_so,a.prd_no,b.name prd_name,sum(qty) qty from %s.dbo.tf_mm a left join %s.dbo.prdt b on a.prd_no =b.prd_no where  a.bat_no like '%s%s' and mrp_no_so like '%s' and a.wh=90 group by  bat_no,mrp_no_so,a.prd_no,b.name",$key,$key,'%',$bat_no,$prd_no);
			$result=$billdetail_naked->query($select);
			if($result){
				$billdetail_naked_result[$value]=$result;
			}
		}
		if($billdetail_naked_result){
			$this->billdetail_naked_result=$billdetail_naked_result;
			$this->display();
		}else{
			$this->error('查询不到数据');
		}
	}

	function FindSoDetail($bat_no=null,$prd_no = null){
		$this->title="查询工厂销售订单数量";
		$bat_no =trim(I('bat_no'));
		$prd_no = trim(I('prd_no'));
		if(empty($bat_no)){
			$this->error('没有批号');
		}
		$billdetail_so = M("tf_pos",Null,"DB_CONFIG1");
		foreach($this->factory_db_array as $key=>$value){
			$select=sprintf("select a.bat_no,a.prd_no,a.prd_name,a.qty,a.qty_pre,c.name cus_name,d.name sal_name from %s.dbo.tf_pos a,%s.dbo.mf_pos b,%s.dbo.cust c,%s.dbo.salm d where b.sal_no =d.sal_no and a.os_no = b.os_no and b.cus_no = c.cus_no and a.os_id='SO' and a.bat_no like '%s' and a.prd_no like '%s' ",$key,$key,$key,$key,$bat_no,$prd_no);
			//echo $select;
			$result=$billdetail_so->query($select);
			if($result){
				$billdetail_so_result[$value]=$result;
			}
		}
		if($billdetail_so_result){
			$this->billdetail_so_result=$billdetail_so_result;
			$this->display();
		}else{
			$this->error('查询不到数据');
		}		
	}

	function CheckLzResult(){
		$this->title="关联公司开票/收票对比";
		$factory_param=I('factory_param');
		$company_param=I('company_param');
		$factory_lz_no=I('factory_lz_no');
		$company_lz1_no=I('company_lz1_no');
		$factory=explode(';',$factory_param);
		$company=explode(';',$company_param);
		$factory_array['db']=array_search($factory[0],$this->factory_db_array);
		$factory_array['prd_no']=$factory[1];
		$factory_array['lz_no']=$factory_lz_no;
		$company_array['db']=array_search($company[0],$this->company_db_array);
		$company_array['prd_no']=$company[1];
		$company_array['lz_no']=$company_lz1_no;
		$param=trim(I('param'));
		if(empty($factory_array) || empty($company_array)){
			$this->error('没有可查询的参数');
		}
		if(empty($param)){
			$select_factory_lz=sprintf("select bat_no,prd_no,prd_name,cast(sum(qty) as numeric(13,2)) fc_qty,cast(sum(amtn_net) as numeric(13,2)) fc_amtn_net  from %s.dbo.tf_lz where lz_no = '%s' and  prd_no like '%s' group by bat_no,prd_no,prd_name order by bat_no",$factory_array['db'],$factory_array['lz_no'],$factory_array['prd_no']);
			if($company_array['db']=='DB_SXMK'){
				$select_company_lz1=sprintf("select os_no bat_no,prd_no,prd_name,cast(sum(qty) as numeric(13,2)) com_qty,cast(sum(amtn_net) as numeric(13,2)) com_amtn_net from %s.dbo.tf_lz1 where lz_no = '%s' and prd_no like '%s' group by os_no,prd_no,prd_name order by os_no ",$company_array['db'],$company_array['lz_no'],iconv("UTF-8","GBK",$company_array['prd_no']));
			}else{
				$select_company_lz1=sprintf("select bat_no,prd_no,prd_name,prd_mark,cast(sum(qty) as numeric(13,2)) com_qty,cast(sum(amtn_net) as numeric(13,2)) com_amtn_net from %s.dbo.tf_lz1 where lz_no = '%s' and prd_no like '%s%s' group by bat_no,prd_no,prd_name,prd_mark order by bat_no ",$company_array['db'],$company_array['lz_no'],'%',iconv("UTF-8","GBK",$company_array['prd_no']));
			}
		}else{
			
		}
		
		//echo $select_factory_lz;
		//echo $select_company_lz1;
		$db_content=M("tf_pos",Null,"DB_CONFIG1");
		$factory_result=$db_content->query($select_factory_lz);
		$company_result=$db_content->query($select_company_lz1);
		$other['factory_name']=$factory[0];
		$other['factory_lz_no']=$factory_lz_no;
		$other['company_name']=$company[0];
		$other['company_lz1_no']=$company_lz1_no;
		if($factory_result && $company_result){
			$this->list_result=array_merge($factory_result,$company_result);
			
			$this->other=$other;
			$this->display();
		}else{
			$this->error('查询不到数据');
		}
	}

	function FindInvnoResult($inv_no=null){
		$this->title="查询发票号对应收开票单据";
		$result=null;
		$result_fc=null;
		$inv_no = I('inv_no');
		if(empty($inv_no)){
			$this->error('没有发票号码');
		}
		$pattern="/\/|\-/";
		$inv_no_array=preg_split($pattern,$inv_no);
		$db_content=M("tf_pos",Null,"DB_CONFIG1");
		foreach($this->factory_db_array as $key=>$value){
			$select_factory=sprintf("select a.inv_no,a.lz_no,convert(varchar,a.lz_dd,023) lz_dd,a.rem,b.bat_no,b.prd_no,b.prd_name,cast(sum(b.qty) as numeric(13,2)) qty,cast(sum(b.amtn_net) as numeric(13,2)) amtn_net from %s.dbo.mf_lz a,%s.dbo.tf_lz b where a.lz_no = b.lz_no and a.inv_no like '%s%s' group by a.inv_no,a.lz_no,a.lz_dd,a.rem,b.bat_no,b.prd_no,b.prd_name order by a.inv_no,a.lz_no,b.bat_no,b.prd_no",$key,$key,$inv_no_array[0],'%');
			
			$result_fc=$db_content->query($select_factory);
			if($result_fc){
				$factory_result[$value]=$result_fc;
			}
		}
	
		foreach($this->company_db_array as $key=>$value){
			if($key=='DB_SXMK'){
				$select_company=sprintf("select a.inv_no,a.lz_no,convert(varchar,a.lz_dd,023) lz_dd,a.rem,b.os_no bat_no,b.prd_no,b.prd_name,cast(sum(b.qty) as numeric(13,2)) qty,cast(sum(b.amtn_net) as numeric(13,2)) amtn_net from %s.dbo.mf_lz1 a,%s.dbo.tf_lz1 b where a.lz_no = b.lz_no and a.inv_no like '%s%s' group by a.inv_no,a.lz_no,a.lz_dd,a.rem,b.os_no,b.prd_no,b.prd_name order by a.inv_no,a.lz_no,b.os_no,b.prd_no",$key,$key,$inv_no_array[0],'%');
			}else{
				$select_company=sprintf("select a.inv_no,a.lz_no,convert(varchar,a.lz_dd,023) lz_dd,a.rem,b.bat_no,b.prd_no,b.prd_name,cast(sum(b.qty) as numeric(13,2)) qty,cast(sum(b.amtn_net) as numeric(13,2)) amtn_net from %s.dbo.mf_lz1 a,%s.dbo.tf_lz1 b where a.lz_no = b.lz_no and a.inv_no like '%s%s' group by a.inv_no,a.lz_no,a.rem,a.lz_dd,b.bat_no,b.prd_no,b.prd_name order by a.inv_no,a.lz_no,b.bat_no,b.prd_no",$key,$key,$inv_no_array[0],'%');
			}
			//echo $select_company;
			$result=null;
			$result=$db_content->query($select_company);
			if($result){
				$company_result[$value]=$result;
			}
		}
		$this->factory_result=$factory_result;
		$this->company_result=$company_result;
		$this->display();
	}

	function FindLzDetail($lz_no=null){
		$this->title="工厂开票明细";
		$lz_no = I('lz_no');
		$factory_name=I('factory_name');
		$factory_db=array_search($factory_name,$this->factory_db_array);
		$db_content=M("tf_pos",Null,"DB_CONFIG1");
		$select_mf=sprintf("select a.lz_no,convert(varchar,a.lz_dd,023) lz_dd,a.inv_no,a.rem,b.name,cast(a.amtn_net as numeric(13,2)) amtn_net,a.voh_no,a.usr from %s.dbo.mf_lz a,%s.dbo.cust b where a.cus_no = b.cus_no and a.lz_no = '%s'",$factory_db,$factory_db,$lz_no);
		$select_tf=sprintf("select lz_no,ck_no,bat_no,prd_no,prd_name,cast(qty as numeric(13,2)) qty,cast(amtn_net as numeric(13,2)) amtn_net from %s.dbo.tf_lz where lz_no = '%s' order by bat_no,prd_no,qty ",$factory_db,$lz_no);
		$list_mf=$db_content->query($select_mf);
		$list_tf=$db_content->query($select_tf);
		if($list_mf&&$list_tf){
			$this->list_mf=$list_mf;
			$this->list_tf=$list_tf;
			$this->factory_name=$factory_name;
			$this->display();
		}else{
			$this->error('查询不到数据');
		}
	}

	function FindLpDetail($lz_no=null){
		$this->title="公司收票明细";
		$lz_no = I('lz_no');
		$ps_id = I('ps_id')?I('ps_id'):'PC';
		$company_name=I('company_name');
		$company_db=array_search($company_name,$this->company_db_array);
		
		$select_mf=sprintf("select a.lz_no,convert(varchar,a.lz_dd,023) lz_dd,a.inv_no,a.rem,b.name,cast(a.amtn_net as numeric(13,2)) amtn_net,a.voh_no,a.usr from %s.dbo.mf_lz1 a,%s.dbo.cust b where a.cus_no = b.cus_no and a.lz_no = '%s'",$company_db,$company_db,$lz_no);
		$select_tf=null;
		if($ps_id=='PC'||$ps_id=='PB'){
			if($company_db=='DB_SXMK'){
				$select_tf=sprintf("select lz_no,ps_no,os_no bat_no,prd_no,prd_name,prd_mark,cast(qty as numeric(13,2)) qty,cast(amtn_net as numeric(13,2)) amtn_net from %s.dbo.tf_lz1 where lz_no = '%s' order by os_no,prd_no,qty ",$company_db,$lz_no);
			}else{
				$select_tf=sprintf("select lz_no,ps_no,bat_no,prd_no,prd_name,prd_mark,cast(qty as numeric(13,2)) qty,cast(amtn_net as numeric(13,2)) amtn_net from %s.dbo.tf_lz1 where lz_no = '%s' order by bat_no,prd_no,qty ",$company_db,$lz_no);
			}
			$select_mf=sprintf("select a.lz_no,convert(varchar,a.lz_dd,023) lz_dd,a.inv_no,a.rem,b.name,cast(a.amtn_net as numeric(13,2)) amtn_net,a.voh_no,a.usr from %s.dbo.mf_lz1 a,%s.dbo.cust b where a.cus_no = b.cus_no and a.lz_no = '%s'",$company_db,$company_db,$lz_no);
		}elseif($ps_id=='SA' || $PS_ID=='SB'){
			if($company_db=='DB_SXMK'){
				$select_tf=sprintf("select lz_no,ck_no ps_no,os_no bat_no,prd_no,prd_name,prd_mark,cast(qty as numeric(13,2)) qty,cast(amtn_net as numeric(13,2)) amtn_net from %s.dbo.tf_lz where lz_no = '%s' order by os_no,prd_no,qty ",$company_db,$lz_no);
			}else{
				$select_tf=sprintf("select lz_no,ck_no ps_no,bat_no,prd_no,prd_name,prd_mark,cast(qty as numeric(13,2)) qty,cast(amtn_net as numeric(13,2)) amtn_net from %s.dbo.tf_lz where lz_no = '%s' order by bat_no,prd_no,qty ",$company_db,$lz_no);
			}
			$select_mf=sprintf("select a.lz_no,convert(varchar,a.lz_dd,023) lz_dd,a.inv_no,a.rem,b.name,cast(a.amtn_net as numeric(13,2)) amtn_net,a.voh_no,a.usr from %s.dbo.mf_lz a,%s.dbo.cust b where a.cus_no = b.cus_no and a.lz_no = '%s'",$company_db,$company_db,$lz_no);
		}
		$db_content=M("tf_pos",Null,"DB_CONFIG1");
		$list_mf=$db_content->query($select_mf);
		$list_tf=$db_content->query($select_tf);
		if($list_mf&&$list_tf){
			$this->list_mf=$list_mf;
			$this->list_tf=$list_tf;
			$this->company_name=$company_name;
			$this->display();
		}else{
			$this->error('查询不到数据');
		}
	}

	function FindBillingList($ps_id=null,$ps_no=null,$prd_no=null){
		$this->title="查询票据列表";
		$ps_id=I('ps_id');
		$ps_no = I('ps_no');
		$prd_no = I('prd_no');
		$company_name=I('company_name');
		$company_db=array_search($company_name,$this->company_db_array);
		if(empty($ps_id)||empty($ps_no)||empty($prd_no)){
			$this->error('未获取到参数');
		}
		$select=null;//初始化查询语句
		if($ps_id=='PC'||$ps_id=='PB'){
			if($company_db=='DB_SXMK'){
				$select=sprintf("select lz_no,os_no bat_no,ps_no,prd_no,prd_name,prd_mark,qty,amt,amtn_net,tax from %s.dbo.tf_lz1 where ps_no='%s' and prd_no = '%s'",$company_db,$ps_no,$prd_no);
			}else{
				$select=sprintf("select lz_no,bat_no,ps_no,prd_no,prd_name,prd_mark,qty,amt,amtn_net,tax from %s.dbo.tf_lz1 where ps_no='%s' and prd_no = '%s'",$company_db,$ps_no,$prd_no);
			}
		}elseif($ps_id=='SA' || $ps_id =='SB'){
			if($company_db=='DB_SXMK'){
				$select=sprintf("select lz_no,os_no bat_no,ck_no ps_no,prd_no,prd_name,prd_mark,qty,amt,amtn_net,tax from %s.dbo.tf_lz where ck_no='%s' and prd_no = '%s'",$company_db,$ps_no,$prd_no);
			}else{
				$select=sprintf("select lz_no,bat_no,ck_no ps_no,prd_no,prd_name,prd_mark,qty,amt,amtn_net,tax from %s.dbo.tf_lz where ck_no='%s' and prd_no = '%s'",$company_db,$ps_no,$prd_no);
			}
		}
		//echo $select;
		$db_content=M("tf_pos",Null,"DB_CONFIG1");
		$list_lz=$db_content->query($select);
		if($list_lz){
			$this->list_lz=$list_lz;
			$this->company_name=$company_name;
			$this->ps_id=$ps_id;
			$this->display();
		}else{
			$this->error("查询不到数据");
		}
	}
}
