<?php
class SuggestAction extends Action {
	private $list_week=array("星期日"=>0,"星期一"=>1,"星期二"=>2,"星期三"=>3,"星期四"=>4,"星期五"=>5,"星期六"=>6);

	public function _initialize(){
		$pos=strpos(session('user_group'),'admin');
		$user_level=session('user_level');
		if($pos!==false&&$user_level=9){
			$this->user_group='管理员';
		}else{
			header("Content-type: text/html; charset=utf-8");
			redirect("http://".$_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT'],2,'页面跳转中...');
		}
	}
	
	public function add(){
		$this->title="建议";
		$this->display();
	}

	public function insert(){
		$this->title="建议";
		$Form = D('Suggest');
		if($Form->create()){
			$result = $Form->add();
			if($result){
				$this->success('操作成功!');
				
			}else{
				$this->error('写入错误!');
			}
		}else{
			$this->error($Form->getError());
		}
	}
	
	public function read($id=0){
		$this->title="查看建议";
		import('ORG.Util.Page');
		$suggest=M('Suggest');
		$count=$suggest->order('id desc')->count();
		$Page=new Page($coutn,10);
		$show=$Page->show();
		$list=$suggest->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		if($list) {
			$this->list =$list;// 模板变量赋值
		}else{
			$this->error('数据错误');
		}
		$this->display();
	}

	public function edit($id=0){
		$Form = M('Suggest');
		$this->vo = $Form ->find($id);
		$this->display();
	}
	public function update(){
		$Form = D('Suggest');
		if($Form->create()){
			$result= $Form->save();
			if($result){
				$this->success('操作成功!');
			}else{
				$this->error('写入错误!');
			}
		}else{
			$this->error($Form->getError());
		}
	}
	public function delete($id=0){
		$Form = M("Suggest");
		$result = $Form ->delete($id);
		if($result==0){
			$this->error('没有数据被删除');
		}elseif($result==false){
			$this->error('SQL Error');
		}else{
			$this->success($result.'删除成功!');
		}
	}

	function showCalendar(){
		$this->title="查看日历";
		$this->display();
	}

	function nongLi(){
		header("Content-type: text/html; charset=utf-8");
		$this->title="农历";
		$date=I('date');
		if(!$date){
			$date=date("Y-m-d");
		}
		//echo $date;
		$nongli=M('nongli');
		$condition['date']=array("like",substr($date,0,7)."%");
		$list_nongli=$nongli->where($condition)->select();
		if($list_nongli){
			for($i=0;$i<count($list_nongli);$i++){
				$arr_nongli=explode(" ",$list_nongli[$i]["nongli"]);
				$list_nongli[$i]['week']=$this->list_week[$arr_nongli[1]];
			}
			$this->date=$date;
			$this->list_nongli=$list_nongli;
			$this->display();
		}else{
			echo "您查看的日历还没有制作好";
		}
	}
}