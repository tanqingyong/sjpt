<?php
require_once(dirname(dirname(dirname(__FILE__))) . '/app.php');
require_once(dirname(dirname(dirname(__FILE__))) . '/manage/export_excel.php');
need_login();

$table_array = array( 
                      'date'=>'日期',
					  'media'=>'媒体',
					  'category'=>'频道',
					  'ip'=>'ip',
					  'uv'=>'uv',
					  'pv'=>'pv',
					  'old_ip'=>'老用户ip',
					  'old_uv'=>'老用户uv',
					  'old_pv'=>'老用户pv',
					  'new_ip'=>'新用户ip',
					  'new_uv'=>'新用户uv',		
					  'new_pv'=>'新用户pv'
                    );
$filter_date_start = trim($_GET["startdate"]);
$filter_date_end = trim($_GET["enddate"]);
$order_type = trim($_GET["order_type"]);
$media_name = trim($_GET["media_name"]);
$city_name = trim($_GET["city_name"]);
$cate_name = trim($_GET["cate_name"]);

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

$sql = "select * from operate_all_data o where 1=1 $condition     ";


$datas = DB::GetQueryResult($sql,false);


//foreach($datas as $key => $data){
//    $datas[$key]['arpu']=round($data['pay_money']/$data['pay_user']*100,2)."%";
//
//}
$all_datas=array();

$excel_name = "媒体数据(整站流量)";
export_excel($datas,$table_array,$all_datas,$excel_name);
?>