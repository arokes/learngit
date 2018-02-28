<?php
import('ORG.Util.Page');
//load("@.functions");
class UsersAction extends Action {

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

    function NewUsers(){
		$this->title="新增用户";
		$dep=M('dep');
		$user_group=M('user_group');
		$dep_data=$dep->select();
		$user_group_data=$user_group->select();
		$this->user_group_data=$user_group_data;
		$this->dep_data=$dep_data;
		$this->display();
    }

    function NewUsersResult(){
    	$this->title="新增用户结果";
    	$user=M('user');
    	$user->create();
    	$user->user_pswd=sha1(md5('0000')."mkserver");
    	$is_follow=I('is_follow');
    	if($is_follow=='T'){
    		$user_dep_no=I('user_dep_no');
    		$user_data=$user->where("user_dep_no like '".$user_dep_no."_%'")->select();
    		$num=count($user_data)+1;
    		if($num<10){
    			$user->user_dep_no=$user_dep_no."0".$num;
    		}else{
    			$user->user_dep_no=$user_dep_no.$num;
    		}
    	}
    	$user_result=$user->add();
    	if($user_result){
    		$this->success('新增成功');
    	}else{
    		$this->error('新增失败');
    	}
    }

    function SelectUsers(){
    	$this->title="查询用户列表";
    	$this->display();
    }

    function SelectUsersResult(){
    	$this->title="查询用户列表结果";
    	$selectmode=I('selectmode');
    	$parameter=I('parameter');
    	$user=M('user');
    	$condition["user.".$selectmode]=array('like',$parameter."%");
    	$count=$user->where($condition)->join('user_group on user_group.user_group=user.user_group')->count();
    	$page = new Page($count,20);
    	$show=$page->show();
    	$user_data=$user->where($condition)->join('user_group on user_group.user_group=user.user_group')->limit($page->firstRow.','.$page->listRows)->select();
    	if($user_data){
    		$this->user_data=$user_data;
    		$this->show=$show;
    		$this->display();
    	}else{
    		$this->error('查询不到数据');
    	}
    }

    function ResetUserPswd($user_id=null){
        $this->title="重置密码";
        $user_id=I('user_id');
        if(empty($user_id)){
            echo "未获取到ID";
        }
        $user=M('user');
        $data['user_pswd']=sha1(md5('0000')."mkserver");
        $result=$user->where("user_id=".$user_id)->save($data);
        if($result){
            echo "重置成功";
        }else{
            echo "重置失败";
        }
    }

    function ModifyUser($user_id=null){
        $this->title="修改用户";
        $user_id=I('user_id');
        if(empty($user_id)){
            $this->error('未获取到ID');
        }
        $user=M('user');
        $listuser=$user->find($user_id);
        if($listuser){
            $this->listuser=$listuser;
            $this->display();
        }else{
            $this->error('查询不到数据');
        }
    }

    function ModifyUserResult($user_id=null){
        $this->title="修改用户结果";
        if(empty($user_id)){
            $this->error('未获取到ID');
        }
        $user=M('user');
        $user->create();
        $result=$user->where("user_id=".$user_id)->save();
        if($result){
            $this->success("修改成功");
        }else{
            $this->error("修改失败");
        }
    }

    function ModifyUserPswd(){
        $this->title="修改用户密码";
        $user_id=SESSION('user_id');
        if(empty($user_id)){
            session_destroy();
            $this->error('未获取到用户ID',"http://".$_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT']);
        }else{
            $this->display();
        }
    }

    function ModifyUserPswdResult(){
        $this->title="修改用户密码结果";
         $user_id=SESSION('user_id');
        if(empty($user_id)){
            session_destroy();
            $this->error('未获取到用户ID',"http://".$_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT']);
        }
        $user_pswd_old=I('user_pswd_old','');
        if(empty($user_pswd_old)){
            $this->error('请输入现在使用的密码');
        }
        $user=M('user');
        $pswd=$user->find($user_id);
        if(sha1(md5($user_pswd_old)."mkserver")!=$pswd['user_pswd']){
            $this->error('密码错误');
        }
        $user_pswd_new1=trim(I('user_pswd_new1'));
        $user_pswd_new2=trim(I('user_pswd_new2'));
        if(empty($user_pswd_new1)||empty($user_pswd_new2)){
            $this->error('密码不可为空');
        }
        if($user_pswd_new1!=$user_pswd_new2){
            $this->error('两次输入的密码不一致');
        }
        $data['user_pswd']=sha1(md5($user_pswd_new1)."mkserver");
        $result=$user->where("user_id=$user_id")->save($data);
        if($result){
            $this->success("修改完成,请重新登录","http://".$_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT']);
        }else{
            $this->error('修改失败');
        }
    }

    function DisableUser($user_id=null){
        $this->title="禁用用户";
        $user_id=I('user_id');
        if(empty($user_id)){
            $this->error('未获取到ID');
        }
        $user=M('user');
        $data['disable']=0;
        $result=$user->where("user_id=".$user_id)->save($data);
        if($result){
            echo "禁用完成";
        }else{
            echo "未能禁用";
        }
    }

    function EnableUser($user_id=null){
        $this->title="启用用户";
        $user_id=I('user_id');
        if(empty($user_id)){
            $this->error('未获取到ID');
        }
        $user=M('user');
        $data['disable']=1;
        $result=$user->where("user_id=".$user_id)->save($data);
        if($result){
            echo "启用完成";
        }else{
            echo "未能启用";
        }
    }

    function SelectUserLoad(){
        $this->title="查询用户登录列表";
        $this->display();
    }

    function SelectUserLoadResult(){
        $this->title="查询用户登录列表结果";
        header("Content-type: text/html; charset=utf-8"); 
        $user_name=I('user_name')?I('user_name'):'%';
        $user_ip=I('user_ip')?I('user_ip'):'%';
        $date_min=I('date_min')?I('date_min'):'2018-01-01';
        $date_max=I('date_max')?I('date_max'):date('Y-m-d');
        $condition['user_name']=array('like',$user_name);
        $condition['user_ip']=array('like',$user_ip);
        $condition['date(load_date)']=array('between',"$date_min,$date_max");
        $userload=M('userload');
        $count=$userload->where($condition)->count();
        $page= new Page($count,20);
        $show=$page->show();
        $userload_result=$userload->where($condition)->limit($page->firstRow.','.$page->listRows)->select();
        if($userload_result){
            for($i=0;$i<count($userload_result);$i++){
                if(empty($userload_result[$i]['user_area'])){
                    $area=file_get_contents("http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json&ip=".$userload_result[$i]['user_ip']);
                    $area=json_decode($area,true);                    
                    $user_area['user_area']=sprintf("%s %s %s",$area['province'],$area['city'],$area['isp']);
                    $userload->where("id=".$userload_result[$i]['id'])->save($user_area);
                    $userload_result[$i]['user_area']=$user_area['user_area'];          
                }
            }
            $this->userload_result=$userload_result;
            $this->show=$show;
            $this->display();
        }else{
            $this->error('查询失败');
        }

    }

    function DownloadOkerpFiles($id=null){
        header("Content-type:text/html;charset=utf-8");
        $this->title="下载全部全部网站文件";
        $zipfilename='C:/xampp/htdocs/Public/ok_erp.rar';
        //zip('C:/xampp/htdocs/ok_erp',$zipname); 
        if(!file_exists($zipfilename)){
            $this->error('没有可下载的数据文件');
        }
        import('ORG.NET.Http');
        $Http=new Http();
        $Http->download($zipfilename);
        
    }
   
}