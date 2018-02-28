<?php
class SuggestAction extends Action {

	public function _initialize(){
		if(session('user_group')=='sale'){
			$this->user_group='销售部';
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
}