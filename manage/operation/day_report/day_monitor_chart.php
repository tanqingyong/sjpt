<?php
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/app.php');

//need_login();
//need_page();
$filter_date_start = trim($_POST["startdate"]);
$index = trim($_POST["index_name"]);
$meiti = trim($_POST["meiti_name"]);

$condition ="";
if( !$filter_date_start ){
	$filter_date_start = date("Y-m-d");
	$date4 = $filter_date_start;
}else{
	$date4 = $filter_date_start;
}


$date3 = date("Y-m-d",strtotime($filter_date_start)-24*3600);
$date2 = date("Y-m-d",strtotime($filter_date_start)-7*24*3600);
$date1 = date("Y-m-d",strtotime($filter_date_start)-8*24*3600);

$date=$date1."','".$date2."','".$date3."','".$date4;

$condition .= " and hm.datetime in('".$date."') ";
$td_condition .= " and td.date in('".$date."') ";
$th_condition .= " and hm.datetime in('".$date."') ";

//if(strlen($meiti)){
//	$condition .=" and td.platform_id='".$meiti."' ";
//}
$grade = "";
$user_auth = Session::Get('user_auth');
if($user_auth){
	foreach ($user_auth as $permission){
		$grade = $permission['grade'];
		if((empty($meiti))&&$permission['grade']==1){
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
			$condition .= " and td.platform_id in $media ";
			$td_condition .= " and td.platform_id in $media ";
			break;
		}
		else if(strlen($meiti)){
			$condition .=" and td.platform_id='".$meiti."' ";
			$td_condition .=" and td.platform_id='".$meiti."' ";
			break;
		}
		break;
	}
}
if(!$index){
	$index='suc_total_price';
}

$sql="SELECT hm.datetime as 'date',hm.hours,hm.minuts,round(sum(td.".$index."),2) as 'res' from hhmm hm LEFT JOIN 
(SELECT td.date,td.divhour,td.mint,td.".$index." FROM platform_update_hour td
where 1=1 ".$td_condition." 
)td
ON hm.datetime=td.date AND hm.hours=td.divhour AND hm.minuts=td.mint
where 1=1 ".$th_condition." GROUP BY hm.datetime,hm.hours,hm.minuts";

$datas = DB::GetQueryResult($sql,false);
$sql_x = "SELECT CONCAT_WS(':',hm.hours,if(hm.minuts=30,'31~59','00~30')) as 'date' from hhmm hm WHERE hm.datetime='".$date4."' ";
$datas_x = DB::GetQueryResult($sql_x,false);

if($index=="suc_total_price"){
$tt =" round(sum(td.".$index."),2)";
}else{
$tt =" round(sum(td.".$index."))";	
	
}

$sql_temp="SELECT hm.datetime as 'date',".$tt." as 'res' from hhmm hm LEFT JOIN 
(SELECT td.date,td.divhour,td.mint,td.".$index." FROM platform_update_hour td
where 1=1 ".$td_condition." 
)td
ON hm.datetime=td.date AND hm.hours=td.divhour AND hm.minuts=td.mint
where 1=1 AND CONCAT(hm.hours,':',hm.minuts) in (SELECT CONCAT(divhour,':',mint) as 'hm' from platform_update_hour WHERE date='".$date4."' GROUP BY hm ) ".$th_condition." GROUP BY hm.datetime;";
 $datas_temp = DB::GetQueryResult($sql_temp,false);

 $sql_now = "SELECT CONCAT(divhour,'时',if(mint=30,'31分~59分','00分~30分')) as 'hm' from platform_update_hour WHERE date='".$date4."' GROUP BY hm order by hm desc limit 1;";
 $nowtime= DB::GetQueryResult($sql_now);

 $sql_all_meiti = "select p.platform_id,round(sum(p.".$index.")) as 'res' 
from platform_update_hour p where p.date='".$date4."' AND LENGTH(platform_id)>0 GROUP BY p.platform_id";
$res=DB::GetQueryResult($sql_all_meiti,false);
$all_meiti_data = array();
	foreach($res as $key=>$val){
		$all_meiti_data[$val['platform_id']]=$val['res'];
	}

$date1_data = array();
$date2_data = array();
$date3_data = array();
$date4_data = array();
$datetime = array();

foreach($datas as $key=>$val){
	if($val['date']==$date1){
		array_push($date1_data, round($val['res'],2));
	}
	if($val['date']==$date2){
		array_push($date2_data, round($val['res'],2));
	}
	if($val['date']==$date3){
		array_push($date3_data, round($val['res'],2));
	}
	if($val['date']==$date4){
		array_push($date4_data, round($val['res'],2));
	}
}
foreach($datas_x as $key=>$val){
	array_push($datetime, $val['date']);
}

$date1_data = json_encode($date1_data);
$date2_data = json_encode($date2_data);
$date3_data = json_encode($date3_data);
$date4_data = json_encode($date4_data);

$datetime = json_encode($datetime);
echo template ( 'manage_day_monitor_chart',
array ( 'date'=>$datetime,
                		'date1_data'=>$date1_data,
                		'date2_data'=>$date2_data,
                   		'date3_data'=>$date3_data,
                 		'date4_data'=>$date4_data,
						'date1'=>$date1,
                		'date2'=>$date2,
                   		'date3'=>$date3,
                 		'date4'=>$date4,
						'index'=>$index,
						'meiti'=>$meiti,
						'datas_temp'=>$datas_temp,
						'nowtime'=>$nowtime,
						'all_meiti_data'=>$all_meiti_data,
                        'filter_date_start' => $filter_date_start,
						'grade' =>$grade,
)
);
?>