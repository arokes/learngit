<?php
class BasicAction extends Action{

	public function _initialize(){
		if(session('user_group')=='sale'){
			$this->user_group='销售部';
		}else{
			header("Content-type: text/html; charset=utf-8");
			redirect("http://".$_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT'],2,'<h1><center>未登录.页面跳转中...</center></h1>');
		}
	}

	function SelectPrdt(){
		$this->title="查询成品代号";
		$this->display();
	}

	function SelectPrdtResult(){
		$this->title="查询成品代号结果";
		ini_set('memory_limit', '-1'); 
		$parameter=I('parameter');
		$para_arr=explode(' ',trim($parameter));
		$select="select a.prd_no,a.name prd_name,a.spc,b.obj_type,b.name obj_name,a.pic from prdt a left join type_detail b on a.obj_type=b.obj_type where prd_no like '%' ";
		$prdt=M('');
		$prdt->db(1,"mssql://sa:mkdq@192.168.1.221/DB_MKJC");
		for($i=0;$i<count($para_arr);$i++){
			$select_add=" and upper(a.name) like upper('%".iconv("utf-8","GBK",$para_arr[$i])."%')";
			$select=$select.$select_add;
		}
		//echo $select;
		$prdt_data=$prdt->query($select);
		if($prdt_data){
			$this->prdt_data=$prdt_data;
			$this->display();
		}else{
			echo "没有查询到这个成品代号";
		}

	}

	 

	function ImageView($prd_no=null){
		load("@.functions");
		$this->title="查看图片";
		$prd_no=I('prd_no');
		if(!$prd_no){
			$this->error('没有获取到品号');
		}
		$prdt=M('');
		$prdt->db(1,"mssql://sa:mkdq@192.168.1.221/DB_MKJC");
		$select="select pic from prdt where prd_no = '".$prd_no."'";
		$prdt_data=$prdt->query($select);
		
		if($prdt_data){
			//$this->prdt_data=$prdt_data;
			//header( "Content-type:image/jpg");
			$pic=hextobin($prdt_data[0]['pic']);
			//dump($prdt_data);
			echo $pic;
			dump($pic);
			//dump($prdt_data[0]['pic']);
		}else{
			$this->error('此货品没有图片');
		}
	}

	function ChangePasswordCheck(){
		$this->title="修改密码-检测原始密码";
		$this->display();
	}

	function ChangePasswordChange($user_pswd=null){
		$this->title="修改密码-输入新密码";
		$user=M('user');
		$user_id=$_SESSION['user_id'];
		$user_pswd=I('password');
		//$user_name=$_SESSION['user_name'];
		$user_data=$user->find($user_id);
		if($user_data['user_pswd']==MD5($user_pswd)){
			$this->display();
		}else{
			$this->error('密码不正确');
		}
	}

	function ChangePasswordChangeResult(){
		$this->title="修改密码--结果";
		$user=M('user');
		$user_id=$_SESSION['user_id'];
		$pswd_old=I('password_old');
		$user=M('user');
		$user_data=$user->find($user_id);
		if($user_data['user_pswd']!==sha1(md5($pswd_old)."mkserver")){
			$this->error('旧密码不正确');
		}


		$pswd_new1=I('password_new1');
		$pswd_new2=I('password_new2');
		if($pswd_new1!==$pswd_new2||empty($pswd_new1)||empty($pswd_new2)){
			$this->error('两次输入新密码不一致');
		}
		
		
		$data['user_pswd']=sha1(md5($pswd_new1)."mkserver");
		$result=$user->where("user_id='".$user_id."'")->save($data);
		if($result){
			$this->success('修改成功，请重新登录',"http://".$_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT']);
		}else{
			$this->error('修改失败');
		}
	}
}
