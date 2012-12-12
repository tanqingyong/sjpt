<?php
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/app.php');
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/include/library/DB.class.php');
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/include/library/Cache.class.php');
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/include/library/Config.class.php');
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/include/library/Category.class.php');
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/include/library/TreeRender.class.php');
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/include/library/PHPExcel.php');
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/include/library/PHPExcel/Writer/Excel2007.php');

need_page();
$arr_cost_element = array();
$import_count = 0;
$update_income_cost_count = 0;
$update_oa = '';
$user_id = $_SESSION['user_id'];

$upload_dir = dirname(dirname(dirname(dirname(__FILE__))))."/include/data/upload/";
if($full_file=upload_file('excel_file', $upload_dir)){
	$fileName = $full_file;
	$filePath =$fileName;
	$PHPExcel = new PHPExcel();
	$PHPReader = new PHPExcel_Reader_Excel2007();
	if(!$PHPReader->canRead($filePath)){
		$PHPReader = new PHPExcel_Reader_Excel5();
		if(!$PHPReader->canRead($filePath)){
			echo 'no Excel';
			return ;
		}
	}
	try{
		$PHPExcel = $PHPReader->load($filePath);
	}catch(Exception $e){
		if(!isset($PHPExcel)) return "无法解析文件";
	}
	$currentSheet = $PHPExcel->getSheet(0);
	/**取得一共有多少列*/
	$allColumn = $currentSheet->getHighestColumn();
	/**取得一共有多少行*/
	$allRow = $currentSheet->getHighestRow();
	$sql = "replace into cost values ";
	if($allColumn=='C'){
		if(mysql_escape_string(trim($currentSheet->getCell("A1")->getFormattedValue()))=="发送日期"
		   &&mysql_escape_string(trim($currentSheet->getCell("B1")->getFormattedValue()))=="任务ID"
		   &&mysql_escape_string(trim($currentSheet->getCell("C1")->getFormattedValue()))=="发送成本"){
			for($currentRow = 2;$currentRow<=$allRow;$currentRow++){
				$send_date = mysql_escape_string(trim($currentSheet->getCell("A$currentRow")->getFormattedValue()));
				if(!$send_date){
					continue;
				}
				$task_id = mysql_escape_string(trim($currentSheet->getCell("B$currentRow")->getFormattedValue()));
				if(!$task_id){
					continue;
				}
			    $cost = mysql_escape_string(trim($currentSheet->getCell("C$currentRow")->getFormattedValue()));
	            if(!$cost){
	                continue;
	            }
	            $update_time = time();
	            if($currentRow!=$allRow){
				    $sql .= "('$task_id','$send_date',$cost,$user_id,$update_time),";
	            }else{
	            	$sql .= "('$task_id','$send_date',$cost,$user_id,$update_time)";
	            }
				
			}
		    $result_oa = DB::Query($sql);
	        if($result_oa){
	            $import_count += mysql_affected_rows();
	        }
	        if($import_count){
			    $notice = '导入成功';
	        }else{
	        	$notice = '导入失败';
	        }
		}else{
			$notice = '数据格式不正确。请导入正确的excel文件!';
		}
	}else{
		$notice = '数据格式不正确。请导入正确的excel文件!';
	}
}else{
	$notice = '上传文件失败,文件格式不正确。请导入正确的excel文件!';
}
Session::Set('notice', $notice);
redirect( WEB_ROOT . "/manage/edm/task_data/import_cost.php");
?>