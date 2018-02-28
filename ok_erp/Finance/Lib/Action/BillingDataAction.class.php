<?php
class BillingDataAction extends Action{

	public function _initialize(){
		if(session('user_group')=='finance'){
			$this->user_group='财务部';
		}else{
			header("Content-type: text/html; charset=utf-8");
			redirect("http://".$_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT'],2,'<h1><center>未登录.页面跳转中...</center></h1>');
		}
	}

	function UndownloadBillingData(){
		$this->title="新上传开票资料";
		$billingdata=M('billingdata');
		$billingdata_data=$billingdata->where("download_confirm='F'")->select();
		if($billingdata_data){
			$this->billingdata_data=$billingdata_data;
			$this->display();
		}else{
			$this->billingdata_data=null;
			$this->display();
		}
	}

	function DownloadBillingData($filename=null,$showname=null){
		header("Content-type:text/html;charset=utf-8");
		$filename=I('filename');
		//获取后缀名
		$last_no=strrpos($filename,'.');
		$last_name=substr($filename,$last_no);
		$showname=I('showname').$last_name;
		import('ORG.NET.Http');
		$Http=new Http();
		$Http->download($filename,iconv("utf-8","GBK",$showname));
	}

	function DownloadAllBillingData($id=null){
		header("Content-type:text/html;charset=utf-8");
		$this->title="下载全部开票资料";
		$id=I('id');
		if(!$id){
			$this->error('没有获取到ID');
		}
		$billingdata=M('billingdata');
		$billingdata_data=$billingdata->find($id);
		if(!$billingdata_data){
			$this->error('没有查询到数据');
		}
		$contract_no=$billingdata_data['contract_no'];
		$contract_file=$billingdata_data['contract_file'];
		//获取最后一个‘/’的位置
		$last_no=strrpos($contract_file,'/');
		//获取完整存储路径
		$savepath=substr($contract_file,0,$last_no).'/';
		$zipfilename=$savepath.$contract_no.".zip";
		/**
		if(file_exists($zipfilename)){
			unlink($zipfilename);			
		}
		**/
		$zip=new ZipArchive;
		$res=$zip->open($zipfilename,ZipArchive::CREATE);
		$file_arr=array('prerecorded_file'=>'预录入单','contract_file'=>'内销合同','clearance_file'=>'清关资料','sendgoods_file'=>'发货清单');
		if($res===TRUE){
			foreach($file_arr as $key=>$value){
			$last_name_no=strrpos($billingdata_data[$key],'.');
			$last_name=substr($billingdata_data[$key],$last_name_no);
			$filename=$savepath.$contract_no.$value.$last_name;
			copy($billingdata_data[$key],iconv("utf-8","GBK",$filename));
			$zip->addfile(iconv("utf-8","GBK",$filename),iconv("utf-8","GBK",basename($filename)));
			//unlink(iconv("utf-8","GBK",$filename));		
			}	
		}else{
			echo "no res";
		}
		$zip->close();
		if(!file_exists($zipfilename)){
			$this->error('没有可下载的数据文件');
		}
		$showname=$contract_no."开票资料.zip";
		import('ORG.NET.Http');
		$Http=new Http();
		$Http->download($zipfilename,iconv("utf-8","GBK",$showname));
		
	}
	
	function DownloadConfirmBillingData($id=null){
		$this->title="确认已下载完全部开票资料";
		$id=I('id');
		if($id==null){
			echo "没有获取到ID";
			exit;
		}
		$billingdata=M('billingdata');
		$data['download_confirm']='T';
		$result=$billingdata->where("ID=".$id)->save($data);
		if($result){
			echo "修改完成";
		}else{
			echo "修改失败";
		}

	}

	function BillingDataSelect(){
		$this->title="查询已上传开票资料";
		$this->display();
	}

	function BillingDataSelectResult($sale_name=null,$contract_no=null){
		import('ORG.Util.Page');
		$this->title="查询已上传开票资料结果";
		$sale_name=I('sale_name');
		$contract_no=I('contract_no');
		$billingdata=M('billingdata');
		$condition['sale_name']=array('like',"%".$sale_name."%");
		$condition['contract_no']=array('like',"%".$contract_no."%");
		$count=$billingdata->where($condition)->count();
		$Page=new Page($count,15);
		$show = $Page -> show();
		$list=$billingdata->where($condition)->limit($Page->firstRow.','.$Page->listRows)->select();
		if($list){
			$this->list=$list;
			$this->show=$show;
			$this->display();
		}else{
			$this->error('没有查询到结果');
		}
	}

	function FormatBillingDetailList(){
		$this->title="格式化开票清单为XML";
		$this->display();
	}

	function AddBillingCompanyBasic(){
		$this->title="添加购方公司基础资料";
		$company=M('company');
		$company_data=$company->select();
		$this->company_data=$company_data;
		$this->display();
	}

	function AddBillingCompanyBasicResult(){
		$this->title="添加购方公司基础资料";
		$billingcompany=D("billingcompany");
		if($billingcompany->create()){
			$billingcompany->Hsbz='1';
			$billingcompany->Spbmbbh='1';
			$result=$billingcompany->add();
			if($result){
				$this->success('添加成功','UploadBillingDetailList');
			}else{
				$this->error('添加失败');
			}
		}else{
			$this->error($billingcompany->getError());
		}			
	}

	function SelectBillingCompanyBasic(){
		$this->title="查询购方公司资料";
		$this->display();
	}

	function SelectBillingCompanyBasicResult(){
		$this->title="查询购方公司资料结果";
		$selectmode=I('selectmode','');
		$parameter=I('parameter')?"%".I('parameter')."%":'%';
		if(empty($selectmode)){
			$this->error('没有获取到查询模式');
		}
		$condition[$selectmode]=array('like',$parameter);
		$billingcompany=M('billingcompany');
		$listbillingcompany=$billingcompany->where($condition)->select();
		if($listbillingcompany){
			$this->listbillingcompany=$listbillingcompany;
			$this->display();
		}else{
			$this->error('没有查询到数据');
		}
	}

	function DeleteBillingCompanyBasic(){
		$this->title="删除公司信息";
		$id=I('id');
		$billingcompany=M('billingcompany');
		$result=$billingcompany->delete($id);
		if($result){
			echo "删除成功";
		}else{
			echo "删除失败";
		}

	}

	function ModifyBillingCompanyBasicDetail(){
		$this->title="修改购方公司基本信息";
		$id=I('id');
		$billingcompany=M('billingcompany');
		$listbillingcompany=$billingcompany->find($id);
		if($listbillingcompany){
			//dump($listbillingcompany);
			$this->company_data=M('company')->select();
			$this->listbillingcompany=$listbillingcompany;
			$this->display();
		}else{
			$this->error("没有查询到数据");
		}
	}

	function ModifyBillingCompanyBasicDetailResult(){
		$this->title="修改购方公司基本信息";
		$id=I('id');
		if(empty($id)){
			$this->error("没有获取到ID");
		}
		$billingcompany=M('billingcompany');
		if($billingcompany->create()){
			$result=$billingcompany->where("id=$id")->save();
			if($result){
				$this->success('修改完成',"__URL__/FormatBillingDetailList");
			}else{
				$this->error("修改失败");
			}
		}else{
			$this->error($billingcomppany->getError());
		}
	}

	function UploadBillingDetailList(){
		$this->title="上传开票清单";
		$billingcompany=M('billingcompany');
		$mekacompany_data=$billingcompany->distinct(true)->field('mekacompany')->select();
		$this->mekacompany_data=$mekacompany_data;
		$this->display();
	}

	function getGfmcData(){
		$mekacompany=I('mekacompany');
		$billingcompany=M('billingcompany');
		$billingcompany_data=$billingcompany->where("mekacompany='".$mekacompany."'")->select();
		echo "<option value=\"\">-----------请选择----------</option>";
		for($i=0;$i<count($billingcompany_data);$i++){
			echo "<option value='".$billingcompany_data[$i]['id']."'>".$billingcompany_data[$i]['Gfmc']."</option>";
		}
	}

	function getCompanyBasic(){
		$id=I('id');
		if(empty($id)){
			exit;
		}
		$billingcompany=M('billingcompany');
		$billingcompany_data=$billingcompany->find($id);
		if($billingcompany_data){
			$this->billingcompany_data=$billingcompany_data;
			$this->display();
		}else{
			echo "没有找到公司信息";
		}
	}

	function FormatBillingDetailListResult(){
		$this->title="转换成XML格式并下载";	
		header("Content-Type: text/html; charset=GBK");	
		$file_head="<?xml version=\"1.0\" encoding=\"GBK\"?><Kp><Version>2.0</Version>";
		$id=I('Gfmc');
		if(empty($id)){
			$this->error('没有获取到公司ID');
		}
		$billingcompany=M('billingcompany');
		$billingcompany_data=$billingcompany->field("Gfmc,Gfsh,Gfyhzh,Gfdzdh,Bz,Fhr,Skr,Spbmbbh,Hsbz")->find($id);
		$file_company="<Fpxx><Zsl>1</Zsl><Fpsj><Fp><Djh>1</Djh>";
		foreach($billingcompany_data as $key=>$value){
			$file_company=$file_company."<".$key.">".$value."</".$key.">";
		}
		import('ORG.Net.UploadFile');//导入上传文件类
		import('ORG.Util.PHPExcel');//导入PHPEXCEL类
		//实例化上传文件类
		$upload = new UploadFile();
		$upload->maxSize = 3145728;
		$upload->allowExts = array('xls','xlsx');
		$upload->savePath='E:/Upload/';
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
		$file_detail="<Spxx>";
		for($i=2;$i<=$allRow;$i++){
			$row="A".$i.":".$allColumn.$i."";
			$str=$currentSheet->rangeToArray($pRange = $row,null,true,false);
			$file_detail=$file_detail."<Sph><Xh>".($i-1)."</Xh><Spmc>".$str[0][0]."</Spmc><Ggxh>".$str[0][1]."</Ggxh><Jldw>".$str[0][2]."</Jldw><Spbm>".sprintf('%-019s',$str[0][7])."</Spbm><Qyspbm></Qyspbm><Syyhzcbz></Syyhzcbz><Lslbz></Lslbz><Yhzcsm></Yhzcsm><Dj>".number_format($str[0][4]/1.17,15,'.','')."</Dj><Sl>".$str[0][3]."</Sl><Je>".number_format($str[0][5]/1.17,15,'.','')."</Je><Slv>0.17</Slv><Kce></Kce></Sph>";			
		}
		$file_content=$file_head.$file_company.$file_detail."</Spxx></Fp></Fpsj></Fpxx></Kp>";
		//写入文件
		$filepath="E:/upload/billingdetail/";
		$date=date("Ymdhis");
		$company_name=$billingcompany_data['Gfmc'];
		$file_name=$filepath.$company_name.$date.".xml";
		if(file_exists(iconv("UTF-8","GBK",$file_name))){
			unlink(iconv("UTF-8","GBK",$file_name));
		}
		$fp=fopen(iconv("utf-8","GBK",$file_name),"wb");
		fwrite($fp,iconv("utf-8","GBK",$file_content));
		fclose($fp);
		//下载文件
		import('ORG.NET.Http');
		$Http=new Http();
		$Http->download(iconv("UTF-8","GBK",$file_name));
	}
}