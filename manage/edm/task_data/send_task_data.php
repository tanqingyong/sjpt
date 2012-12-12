<?php 
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/app.php');

need_login();
need_page(); 

$filter_date_start = trim($_GET['startdate']);
$filter_date_end = trim($_GET['enddate']);

$filter_mail_type = trim($_GET['mail_type']);
$filter_edm = trim($_GET['edm']);
$filter_task_id = trim($_GET['task_id']);

$sql ="select task_id, send_date, cost,
              case when substr(task_id,8,2)='gg' then 'hygg'
                   when substr(task_id,8,2)='hy' then 'hypd'
                   when substr(task_id,5,10)='webpowerqx' then 'qingxi'
                   when substr(task_id,5,8)='webpower' then 'webpower'
                   when substr(task_id,5,3)='crm' then 'crm'
                   when substr(task_id,5,6)='tixing' then 'tixing' 
              end as mail,
              case when substr(task_id,8,2)='gg' then 'huiyee'
                   when substr(task_id,8,2)='hy' then 'huiyee'
                   when substr(task_id,5,10)='webpowerqx' then 'webpower'
                   when substr(task_id,5,8)='webpower' then 'webpower'
                   when substr(task_id,5,3)='crm' then 'webpower'
                   when substr(task_id,5,6)='tixing' then 'huiyee'
              end as edm,sum(uniq_ip) as ip_sum,sum(uv) as uv_sum,sum(pv) as pv_sum,sum(order_num) as order_sum,
              sum(total_price) as money_sum,sum(register_num) as register_sum,sum(suc_order_num) as suc_order_sum,
              sum(suc_total_price) as suc_money_sum
		      from cost
			  left join adref_success a on a.ad = cost.task_id
			  where 1=1 ";
$sql_count = "select count(1) as count from cost where 1=1 ";
$edm_filter_array = array( "huiyee"=>array( "gg",
                                            "hy",
                                            "tixing",
                                          ),
		                   "webpower" => array( "webpower",
		                                        "crm",
		                                        "webpowerqx"
		                                       )
                  );
$mail_filter_array = array( "gg" => 'substr(task_id,8,2)',
		                    "hy" => 'substr(task_id,8,2)',
		                    "tixing" => 'substr(task_id,5,6)',
		                    "webpower" => 'substr(task_id,5,8)',
		                    "crm" => 'substr(task_id,5,3)',
		                    "webpowerqx" => 'substr(task_id,5,10)'
                 );
$mail_filter = array( "hypd" => "hy",
                      "hygg" => "gg",
                      "tixing" => "tixing",
                      "webpower" => "webpower",
                      "crm" => "crm",
                      "qingxi" => "webpowerqx"                         
                    );
$condition = " ";
if( $filter_date_start && $filter_date_end ){
	$filter_date_start = date("Y-m-d",strtotime($filter_date_start)-15*24*3600);
    $condition .= " and send_date between '$filter_date_start' and '$filter_date_end'";
}elseif( $filter_date_start ){
	$date_start = date("Y-m-d",strtotime(date("Y-m-d"))-15*24*3600);
    $condition .= " and send_date between '$date_start' and '$filter_date_start' ";
}elseif( $filter_date_end ){
	$date_start = date("Y-m-d",strtotime(date("Y-m-d"))-15*24*3600);
    $condition .= " and send_date between '$date_start' and '$filter_date_end' ";
}else{
	$filter_date_start = date("Y-m-d",strtotime(date("Y-m-d"))-15*24*3600);
	$filter_date_end = date("Y-m-d");
	$condition .= " and send_date between '$filter_date_start' and '$filter_date_end' ";
}

if($filter_mail_type){
	if( $filter_mail_type == 'webpower' ){
        $condition .= " and {$mail_filter_array[$mail_filter[$filter_mail_type]]} = '{$mail_filter[$filter_mail_type]}' and substr(task_id,5,10)!='webpowerqx' "; 
	}else{
	    $condition .= " and {$mail_filter_array[$mail_filter[$filter_mail_type]]} = '{$mail_filter[$filter_mail_type]}' ";
	}
}
if($filter_edm){
	$condition .=" and (";
	foreach($edm_filter_array[$filter_edm] as $key=>$mail){
		if($key ==0 ){
		   $condition .=" {$mail_filter_array[$mail]} = '$mail' "; 
		}
		$condition .=" or {$mail_filter_array[$mail]} = '$mail' ";
	}  
	$condition .= ") "; 
}
if($filter_task_id){
    $condition .= " and task_id like '%$filter_task_id%' ";
}

$sql_count .= $condition;
$sql .= $condition." group by task_id order by send_date desc,ip_sum desc";
$result_count = DB::GetQueryResult($sql_count,true);

$count = $result_count['count'];
$page_size = $_GET['pagesize']?intval($_GET['pagesize']):30;
list($pagesize, $offset, $pagestring) = pagestring($count, $page_size);
$sql .= " limit $offset,$pagesize";
$datas = DB::GetQueryResult($sql,false);

echo template ( 'manage_edm_send_task_data', 
                array ( 'datas'=>$datas,'pagestring'=>$pagestring,
                        'filter_date_start'=>$filter_date_start,
                        'filter_date_end'=>$filter_date_end,
                        'page_size'=>$page_size,
                        'filter_mail_type' => $filter_mail_type,
                        'filter_edm' => $filter_edm,
                        'sum_array' => $sum_array,
                        'mail_summary' => $mail_summary,
                        'filter_task_id' => $filter_task_id
                       )
              );
?>