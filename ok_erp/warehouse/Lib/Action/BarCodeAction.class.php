<?php
class BarCodeAction extends Action {

	public function _initialize(){
		if(session('user_group')=='warehouse'){
			$this->user_group='仓库';
		}else{
			header("Content-type: text/html; charset=utf-8");
			redirect("http://".$_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT'],2,'<h1><center>未登录.页面跳转中...</center></h1>');
		}
	}
	public function BarCode(){
		//import('ORG.Util.BCGFont');
		import('ORG.Util.BCGDrawing');
		import('ORG.Util.BCGcode128');
		import('ORG.Util.BCGBarcode');
		$codebar = "BCGcode128"; //该软件支持的所有编码，只需调整$codebar参数即可。

		// Including the barcode technology
		include('class/'.$codebar.'.barcode.php'); 

		// Loading Font
		$font = new BCGFont('warehouse/Tpl/Public/font/Arial.ttf', 10);

		// The arguments are R, G, B for color.
		$color_black = new BCGColor(0, 0, 0);
		$color_white = new BCGColor(255, 255, 255); 

		$code = new $codebar();
		$code->setScale(1); // Resolution
		$code->setThickness(50); // Thickness
		$code->setForegroundColor($color_black); // Color of bars
		$code->setBackgroundColor($color_white); // Color of spaces
		$code->setFont($font); // Font (or 0)
		$bat_no=I('bat_no');
		$prd_no=I('prd_no');
		$text=$bat_no."@".$prd_no; //条形码将要数据的内容
		$code->parse($text); 

		/* Here is the list of the arguments
		1 - Filename (empty : display on screen)
		2 - Background color */
		$drawing = new BCGDrawing('', $color_white);
		$drawing->setBarcode($code);
		$drawing->draw();


		// Header that says it is an image (remove it if you save the barcode to a file)
		//header('Content-Type: image/png');

		//Draw (or save) the image into PNG format.
		$drawing->setFilename("./warehouse/Tpl/Public/barcode/".$bat_no.$prd_no.".gif");
		$drawing->finish(BCGDrawing::IMG_FORMAT_GIF);
		//$this->display();
	}

	function PurchaseOrder(){
		$this->title="查询货品";
		$this->display();
	}

	function SelectPrd($bat_no=null){
		$bat_no=I('bat_no')?I('bat_no'):null;
		if(empty($bat_no)){
			echo "请输入批号";
			exit;
		}
		$parameter=I('parameter')?I('parameter'):'%';
		$parameter_arr=explode(" ",trim($parameter));
		$PO=M('');
		$PO->db(1,"mssql://sa:mkdq@192.168.1.221/DB_OK01");
		$db=array('DB_OK01'=>'上虞欧科电器','DB_MKJC'=>'绍兴美科进出口','DB_JXOK'=>'景德镇欧科');
		foreach($db as $key=>$value){
			
			$select="select '".iconv("UTF-8","GBK",$value)."' company_name,a.bat_no,b.os_no,b.prd_no,b.prd_name,a.cus_no,c.name from ".$key.".dbo.mf_pos a left join  ".$key.".dbo.tf_pos b on a.os_no = b.os_no left join  ".$key.".dbo.cust c on a.cus_no = c.cus_no where a.os_id='PO' and upper(a.bat_no) like upper('".rtrim($bat_no)."') ";
			for($i=0;$i<count($parameter_arr);$i++){
				$select_add=" and upper(b.prd_name) like upper('%".iconv("utf-8","GBK",$parameter_arr[$i])."%')";
				$select=$select.$select_add;
			}
			
			
			$PO_data[$value]=$PO->query($select);
		}
		
		if($PO_data){
			$this->PO_data=$PO_data;
			$this->display();
		}else{
			echo "查询不到数据";
		}
	}
}