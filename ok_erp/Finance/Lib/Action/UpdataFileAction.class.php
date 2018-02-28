<?php
class UpdataFileAction extends Action{

	public function uploadFile(){
		$this->title='上传文件';
		$this->display();
	}

	public function uploadExchange(){
		import('ORG.Net.UploadFile');
		
		$upload = new UploadFile();
		$upload->maxSize = 3145728;
		$upload->allowExts = array('jpg','csv','gif','png','xls');
		$upload->savePath = '../Public/';
		if(!$upload->upload()){
			$this->error($upload->getErrorMsg());
		}else{
			$info = $upload->getUploadFileInfo();
		}
		$mf_ex=M('mf_exchange');
		$fp=file($info[0]['savepath'].$info[0]['savename']);
		
		for($i=1;$i<count($fp);$i++){
			$line=$fp[$i];
			$str=explode(',',$line);
			$data['foreign_trade']=$str[0];
			$data['company_id']=$str[1];
			$data['bank_id']=$str[2];
			$data['bank_cust']=$str[3];
			$data['country']=$str[4];
			$data['pay_dd']=$str[5];
			$data['CUR_ID']=$str[6];
			$data['amount']=trim($str[7]);
			$data['claim']=0;
			$data['record_dd']=date('Y-m-d h:i:s');
			$count[]=$mf_ex->add($data);
		}	
		if($count){
			$this->success('上传成功');
		}else{
			$this->error('上传失败');
		}
		
	}
}

