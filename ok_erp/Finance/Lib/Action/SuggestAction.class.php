<?php
class SuggestAction extends Action{

	public function _initialize(){
		if(session('user_group')=='finance'){
			$this->user_group='财务部';
		}else{
			header("Content-type: text/html; charset=utf-8");
			redirect("http://".$_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT'],2,'<h1><center>未登录.页面跳转中...</center></h1>');
		}
	}
	
	public function Suggest(){
		$this->title="查看建议";
		$suggest=M("suggest");
		$result=$suggest->select();
		if($result){
			$this->data=$result;
			$this->display();
		}else{
			$this->error('没有数据');
		}
	}
}