<?php
require_once(dirname(dirname(dirname(__FILE__))) . '/app.php');
require_once(dirname(dirname(dirname(__FILE__))) . '/manage/export_excel.php');
need_login();

$table_array = array( 
                      'date'=>'日期',
					  'media'=>'媒体',
					  'reg_user'=>'注册用户',
					  'old_reg_user'=>'注册用户(老)',
					  'new_reg_user'=>'注册用户(新)',
					  'login_user'=>'登录用户',
					  'old_login_user'=>'登录用户(老)',		
					  'new_log_user'=>'登录用户(新)'
                    );
                    
$filter_date_start = trim($_GET["startdate"]);
$filter_date_end = trim($_GET["enddate"]);
$order_type = trim($_GET["order_type"]);
$media_name = trim($_GET["media_name"]);


$condition ="";

if( $filter_date_start ){
    $condition .= " and date = '$filter_date_start' ";
}else{
    $filter_date_start = date("Y-m-d",strtotime("-1 day"));
    $condition .= " and date = '$filter_date_start'  ";
}

$user_auth = Session::Get('user_auth');
if($user_auth){
	foreach ($user_auth as $permission){
		if((!$media_name)&&$permission['grade']==1){
			$medialist = get_platforms_for_search();
			//变成sql识别的in("","","")
			$media = "(";
			foreach($medialist as $key=>$value){
				$media.="'";
				$media.= $value;
				$media.="',";
			}
			$media = substr($media,0,strlen($media)-1);
			$media.= ")";
			$condition .= " and media in $media ";
			break;
		}
		elseif($media_name) {
			$condition .= " and media = '$media_name' ";
			break;
		}
		break;
	}
}

if($city_name){
    $condition .= "  and city='".$city_name."' ";
}
if($cate_name){
    $condition .= "  and city='".$cate_name."' ";
}

$sql = "select * from operate_reguser_data o where 1=1 $condition     ";


$datas = DB::GetQueryResult($sql,false);


foreach($datas as $key => $data){
    $datas[$key]['new_log_user']=$data['login_user']-$data['old_login_user'];

}
$all_datas=array();

$excel_name = "媒体数据(注册/登录)";
export_excel($datas,$table_array,$all_datas,$excel_name);
?>