<?php

function merge_bat_prd($array1,$array2){
	for($i=0;$i<count($array1);$i++){
		foreach($array2 as $value2){
			if($array1[$i]['bat_no']==$value2['bat_no']&&$array1[$i]['prd_no']==$value2['prd_no']&&$array1[$i]['prd_mark']==$value2['prd_mark']){
				$array1[$i]=array_merge($array1[$i],$value2);
				break;
			}
		}			
	}
	return $array1;
}

function merge_lz($array1,$array2){
	$array3=$array1;
	for($i=0;$i<count($array2);$i++){
		$k=0;
		for($j=0;$j<count($array1);$j++){
			if($array2[$i]['inv_no']==$array1[$j]['inv_no']){
				$array3[$j]=array_merge($array1[$j],$array2[$i]);
				$k=1;
				break;
			}
		}
		if($k==0){
			$array3[]=$array2[$i];
		}			
	}
	return $array3;
}

function get_client_time(){
		return Date("Y-m-d H:i:s");
	}
function get_month_first_day(){
	return  Date("Y-m")."-01";
	
}

function getPhpExcelObjWriter($array,$file_name,$objPHPExcel){
	//第一种查询结果加入excel表格
	if(isset($array)&&isset($file_name)&&isset($objPHPExcel)){
		//把键值写入对应单元格
		$array_keys=array_keys($array[0]);
		$j=ord('A');
		$objPHPExcel->setActiveSheetIndex(0);
		for ($i=0;$i<count($array_keys);$i++){
			$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue(chr($j)."2",empty($array_keys[$i])==true?"":$array_keys[$i]);
			$objPHPExcel->getActiveSheet()->getColumnDimension(chr($j))->setAutoSize(true);		
			$j++;
		}
		unset($i);
		//把数据写入单元格
		$num=3;//第三行开始写入
		for($k=0;$k<count($array);$k++){
			$i=ord('A');
			foreach($array[$k] as $value){
				$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue(chr($i).$num,$value?$value:'null',PHPExcel_Cell_DataType::TYPE_STRING);	
				$i++;				
				}
			$num++;
		} 
		//设置第一行为表名
		$objPHPExcel->getActiveSheet()->setCellValue('A1',$file_name);
		$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);//设置字体
		$objPHPExcel->getActiveSheet()->mergeCells('A1:'.chr($j-1).'1'); //合并单元格
		$objPHPExcel->getActiveSheet()->setTitle('sheet1');
		//其他基础设置
		$objPHPExcel->getProperties()->setCreator("arokes")
							 ->setLastModifiedBy("arokes")
							 ->setTitle("Office 2007 XLSX Test Document")
							 ->setSubject("Office 2007 XLSX Test Document")
							 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("Test result file");
		// Redirect output to a client’s web browser (Excel2007)
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename='.$file_name.'.xlsx');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		
		$objWriter->save('php://output');//输出表格
	}else{
		echo "getPhpExcelObjWriter没有参数";
	}
}

?>