<?php
require_once(dirname(dirname(dirname(__FILE__))) . '/app.php');

need_login();
need_page(); 
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

$sql_count = "select count(1) as count from operate_reguser_data where 1=1 ".$condition;
$result_count = DB::GetQueryResult($sql_count,true);
$count = $result_count['count'];

$page_size = $_GET['pagesize']?intval($_GET['pagesize']):30;
list($pagesize, $offset, $pagestring) = pagestring($count, $page_size);

$sql = "select * from operate_reguser_data o where 1=1 $condition  limit $offset,$pagesize  ";

$datas = DB::GetQueryResult($sql,false);



echo template ( 'manage_operate_reglogin_data',
                array ('datas'=>$datas,
                        'pagestring' => $pagestring,
                        'filter_date_start' => $filter_date_start,
                        'filter_date_end' => $filter_date_end,
                        'media_name' => $media_name,
                        'page_size' => $page_size
                       )
              );
?>