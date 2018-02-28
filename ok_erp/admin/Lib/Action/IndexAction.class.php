<?php

class IndexAction extends Action {

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

    public function index(){
		$this->title="首页";
		$this->display();
    }
   
  	public function logout(){
   		$this->title="注销用户";
   		if(session('user_group')){
   			if(session_destroy()){
   				$this->success('退出登录',"http://".$_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT']);
   			}else{
   				$this->error('发生错误了,请直接关闭浏览器');
   			}
   		}else{
			header("Content-type: text/html; charset=utf-8");
   			redirect("http://".$_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT'],2,'<h1><center>未登录.页面跳转中...</center></h1>');
   		}
   	}
}