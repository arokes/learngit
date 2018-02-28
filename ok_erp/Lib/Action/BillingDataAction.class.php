<?php

class BillingDataAction extends Action {
	
	public function _initialize(){
		header("Content-type: text/html; charset=utf-8");
		$pos=strpos(session('user_group'),'sale');
		$user_level=session('user_level');
		if($pos!==false&&$user_level>=2){
			$this->user_group='销售部';
		}else{	
			redirect("http://".$_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT'],2,'<h1><center>未登录.页面跳转中...</center></h1>');
		}
	}

	function UploadBillingData(){
		$this->title="上传开票资料";
		$this->display();
	}

	function UploadBillingDataResult($contract_no=null,$sale_name=null){
		import('ORG.Net.UploadFile');
		$this->title="上传开票资料结果";
		$contract_no=trim(I('contract_no'));
		$sale_name=trim(I('sale_name'));
		if(empty($contract_no)){
			$this->error('请输入合同协议号');
		}
		if(preg_match('/[^a-zA-Z0-9-]/',$contract_no)){
			$this->error('合同协议号不可有空格，字符仅可用英文字符-,请修改');
		}
		$billingdata=M('billingdata');
		$billingdata_data=$billingdata->where("contract_no='".$contract_no."'")->select();
		if($billingdata_data){
			$this->error('合同协议号已存在，请确认输入是否正确');
		}
		$upload = new UploadFile();
		$upload->maxSize=10485760;
		$upload->savePath="E:/upload/billingdata/".$contract_no."/";
		if(!$upload->upload()){
			$this->error($upload->getErrorMsg());
		}else{
			$info = $upload->getUploadFileInfo();
		}
		if(count($info)<4){
			$this->error('有文件没有选择上传');
		}
		$data['company_name']=I('company_name');
		$data['contract_no']=$contract_no;
		$data['sale_name']=$sale_name;
		$data['user_name']=$_SESSION['user_name'];
		$data['upload_date']=date("Y-m-d H:i:s");
		foreach($info as $one_file_info){
			$data[$one_file_info['key']]=$one_file_info['savepath'].$one_file_info['savename'];
		}
		$billingdata=M('billingdata');
		$result=$billingdata->add($data);
		if($result){
			$this->success('新增成功');
		}else{
			$this->error('上传出错啦!');
		}
	}

	function UploadBillingDataSelect(){
		$this->title="查询已上传开票资料";
		$this->display();
	}

	function UploadBillingDataSelectResult($sale_name=null,$contract_no=null){
		import('ORG.Util.Page');
		$this->title="查询已上传开票资料结果";
		$sale_name=I('sale_name');
		$contract_no=I('contract_no');
		$user_dep_no=$_SESSION['user_dep_no'];
		$billingdata=M('billingdata');
		$condition['contract_no']=array('like',"%".$contract_no."%");
		$condition['user_name']=array('exp',"in (select user_name from user where user_dep_no like '".$user_dep_no."%') and sale_name like '".$sale_name."%'");
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

	function UploadBillingDataModify($id=null){
		$this->title="修改已上传开票资料";
		$id=I('id');
		if($id==null){
			$this->error('没有找到ID');
		}
		$billingdata=M('billingdata');
		$billingdata_data=$billingdata->find($id);
		if($billingdata_data){
			$this->billingdata_data=$billingdata_data;
			$this->display();
		}else{
			$this->error('查询不到已开票资料的数据');
		}
	}

	function UploadBillingDataModifyResult(){
		import('ORG.Net.UploadFile');
		$this->title="修改已上传开票资料结果";
		$id=I('ID');
		if($id==null){
			$this->error('没有找到ID');
		}
		$billingdata=M('billingdata');
		$billingdata_data=$billingdata->find($id);
		$contract_no=$billingdata_data['contract_no'];
		$contract_file=$billingdata_data['contract_file'];
		//获取最后一个‘/’的位置
		$last_no=strrpos($contract_file,'/');
		//获取完整存储路径
		$savepath=substr($contract_file,0,$last_no).'/';
		$upload = new UploadFile();
		$upload->maxSize=10485760;
		$upload->savePath=$savepath;
		if(!$upload->upload()){
			$this->error($upload->getErrorMsg());
		}else{
			$info = $upload->getUploadFileInfo();
		}
		foreach($info as $one_file_info){
			$data[$one_file_info['key']]=$one_file_info['savepath'].$one_file_info['savename'];
		}
		$data['rem']=I('rem');
		$data['download_confirm']='F';
		$data['change_date']=date('Y-m-d H:i:s');
		$result=$billingdata->where("ID=".$id)->save($data);
		if($result){
			$this->success('修改完成','UploadBillingDataSelect');
		}else{
			$this->error('修改失败');
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
		$Http->download($filename,iconv("GBK","utf-8",$showname));
	}
}