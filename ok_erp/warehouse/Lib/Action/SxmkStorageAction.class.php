<?php
class SxmkStorageAction extends Action{

	Public function __initialize(){
		if(session('user_group')=='cust'){
			$this->user_group='客户';
		}else{
			header("Content-type: text/html; charset=utf-8");
			redirect("http://".$_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT'],2,'<h1><center>未登录.页面跳转中...</center></h1>');
		}
	}

	function SxmkStorage(){
		$this->title="绍兴美科科技库存";
		$this->display();
	}

	function SxmkStorageResult(){
		$this->title="绍兴美科科技库存";
		$storage=M('');
		$storage_db=$storage->db(1,'mssql://sa:mkdq@192.168.1.221/DB_SXMK');
		if(!$storage_db){
			$this->error('连接数据库失败');
		}
		$prd_name=I('prd_name');
		//echo $prd_name;
		$select=iconv("UTF-8","GBK","select a.prd_no,b.name,cast(a.qty as numeric(13,0)) qty,cast(a.qty_on_way as numeric(13,0)) qty_on_way from prdt1 a,prdt b where a.prd_no = b.prd_no and (a.wh='51' or a.wh='53') and upper(b.name) like upper('%".$prd_name."%') ");
		$storage_data=$storage->query($select);
		if($storage_data){
			//dump($storage_data);
			$this->storage_data=$storage_data;
			$this->display();
		}else{
			$this->error('服务器出现了一点问题,请稍后再试');
		}
	}
}
?>