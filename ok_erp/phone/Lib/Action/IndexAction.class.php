<?php
class IndexAction Extends Action{
	
	public function _initialize(){
		header("Content-type: text/html; charset=utf-8");
		$user_group=$_SESSION['user_group'];
		$group="phone";
		if(strpos($user_group,$group)!==false){
			$this->user_group=session('user_group_name');
		}else{
			
			redirect("http://".$_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT'],2,'<h1><center>未登录.页面跳转中...</center></h1>');
		}
	}

	function Index(){
		$this->title="手机界面首页";
		$this->display();
	}



}