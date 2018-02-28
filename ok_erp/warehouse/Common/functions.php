<?php
function getPhpExcelObjWriter($array,$file_name,$objPHPExcel){
	//第一种查询结果加入excel表格
	if(isset($array)&&isset($file_name)&&isset($objPHPExcel)){
		//把键值写入对应单元格
		$array_keys=array_keys($array[0]);
		$j=ord('A');
		$objPHPExcel->setActiveSheetIndex(0);
		for ($i=0;$i<count($array_keys);$i++){
			$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue(chr($j)."2",empty($array_keys[$i])==true?"":iconv("GBK","utf-8",$array_keys[$i]));
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
						->setCellValue(chr($i).$num,$value?iconv("GBK","utf-8",$value):'null',PHPExcel_Cell_DataType::TYPE_STRING);	
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