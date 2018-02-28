<?php

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


/****

function zip($dir_path,$zipName){
    $relationArr[$dir_path]=array(
    	'originName'=>$dir_path,
    	'is_dir' => true,
    	'children'=>array()
    );
    modifiyFileName($dir_path,$relationArr[$dir_path]['children']);
    $zip = new ZipArchive();
    $zip->open($zipName,ZipArchive::CREATE);
    zipDir(array_keys($relationArr[0]),'',$zip,array_values($relationArr[0]['children']));
    $zip->close();
    restoreFileName(array_keys($relationArr[0]),array_values($relationArr[0]['children']));
}

function zipDir($real_path,$zip_path,&$zip,$relationArr){
    $sub_zip_path = empty($zip_path)?'':$zip_path.'\\';
    if (is_dir($real_path)){
        foreach($relationArr as $k=>$v){
            if($v['is_dir']){  //是文件夹
                $zip->addEmptyDir($sub_zip_path.$v['originName']);
                zipDir($real_path.'\\'.$k,$sub_zip_path.$v['originName'],$zip,$v['children']);
            }else{ //不是文件夹
                $zip->addFile($real_path.'\\'.$k,$sub_zip_path.$k);
                $zip->deleteName($sub_zip_path.$v['originName']);
                $zip->renameName($sub_zip_path.$k,$sub_zip_path.$v['originName']);
            }
        }
    }
}
function modifiyFileName($path,&$relationArr){
    if(!is_dir($path) || !is_array($relationArr)){
        return false;
    }
    if($dh = opendir($path)){
        $count = 0;
        while (($file = readdir($dh)) !== false){
            if(in_array($file,['.','..',null])) continue; //无效文件，重来
            if(is_dir($path.'\\'.$file)){
                $newName = md5(rand(0,99999).rand(0,99999).rand(0,99999).microtime().'dir'.$count);
                $relationArr[$newName] = [
                    'originName' => iconv('GBK','UTF-8',$file),
                    'is_dir' => true,
                    'children' => []
                ];
                rename($path.'\\'.$file, $path.'\\'.$newName);
                modifiyFileName($path.'\\'.$newName,$relationArr[$newName]['children']);
                $count++;
            }
            else{
                $extension = strchr($file,'.');
                $newName = md5(rand(0,99999).rand(0,99999).rand(0,99999).microtime().'file'.$count);
                $relationArr[$newName.$extension] = [
                    'originName' => iconv('GBK','UTF-8',$file),
                    'is_dir' => false,
                    'children' => []
                ];
                rename($path.'\\'.$file, $path.'\\'.$newName.$extension);
                $count++;
            }
        }
    }
}
function restoreFileName($path,$relationArr){
    foreach($relationArr as $k=>$v){
        if(!empty($v['children'])){
            restoreFileName($path.'\\'.$k,$v['children']);
            rename($path.'\\'.$k,iconv('UTF-8','GBK',$path.'\\'.$v['originName']));
        }else{
            rename($path.'\\'.$k,iconv('UTF-8','GBK',$path.'\\'.$v['originName']));
        }
    }
}
****/
?>