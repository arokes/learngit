<?php
class SendGoodsAction extends Action{

	public function _initialize(){
		$pos=strpos(session('user_group'),'admin');
		$user_level=session('user_level');
		if($pos!==false&&$user_level=9){
			$this->user_group='管理员';
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
		//$fp=file($info[0]['savepath'].$info[0]['savename']);
		
		$sendgoods=M('sendgoods');//实例化数据表
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
			$data['prd_mark']=$str[0][7];
			$data['out_qty']=$str[0][8];
			$data['record_dd']=date('Y-m-d h:i:s');
			//dump($data);
			$select=sprintf("select id from sendgoods where so_no='%s' and  prd_no ='%s' and ps_no = '%s'",$data['so_no'],$data['prd_no'],$data['ps_no']);
			$find_sendgoods=$sendgoods->query($select);
			if(!$find_sendgoods){
				$count[]=$sendgoods->add($data);
			}
			
		}
		if($count){
			$this->success('上传成功');
		}else{
			$this->error('上传失败');
		}
		
	}

	function ListSendGoods(){
		$this->title = "查询已核对发出商品";
		$this->display();
	}

	function ListSendGoodsResult(){
		import('ORG.Util.Page');
		$this->title="查询已核对发出商品结果";
		$contidion['company_name'] =array('like',I('company_name','%'));
		$contidion['so_no'] =array('like',I('so_no')?I('so_no'):'%');
		$contidion['ps_no'] =array('like',I('ps_no')?I('ps_no'):'%');
		$contidion['sale_name'] =array('like',I('sale_name')?I('sale_name'):'%');
		$sendgoods=M('sendgoods');
		$count=$sendgoods->where($contidion)->count();
		$page = new Page($count,15);
		$show=$page->show();
		$list_sendgoods=$sendgoods->where($contidion)->limit($page->firstRow.','.$page->listRows)->select();
		if($list_sendgoods){
			$this->list_sendgoods=$list_sendgoods;
			$this->show=$show;
			$this->display();
		}else{
			$this->error('没有查询到结果');
		}
	}

	function CheckSendGoods(){
		$this->title="核对发出商品";
		$sendgoods=M('sendgoods');
		$sendgoods_data=$sendgoods->order("company_name,so_no")->where("confirm=0")->select();
		$this->sendgoods_data=$sendgoods_data;
		$this->display();
		
	}

	function CheckStroageGoods(){
		$this->title="核对库存商品";
		$this->display();
	}

	function UploadCheckStorageGoods(){
		$this->title="上传库存商品";
		$this->display();
	}

	function UploadCheckStorageGoodsResult(){
		header("Content-type: text/html; charset=utf-8");
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

		$storagegoods=M('storagegoods');//实例化数据表
		//取出EXCEL里的数据 插入到表
		for($i=2;$i<=$allRow;$i++){
			$row="A".$i.":".$allColumn.$i."";
			$str=$currentSheet->rangeToArray($pRange = $row,null,true,false);
			$data['company_name']=$str[0][0];
			$data['so_no']=$str[0][1];
			$data['prd_no']=$str[0][2];
			$data['prd_name']=$str[0][3];
			$data['prd_mark']=$str[0][4];
			$data['qty']=$str[0][5];
			$data['wh']=$str[0][6];
			$data['record_dd']=date('Y-m-d h:i:s');
			$count[]=$storagegoods->add($data);
		}
		if($count){
			$this->success('上传成功');
		}else{
			$this->error('上传失败');
		}
	}

}