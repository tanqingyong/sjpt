<?php
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/app.php');

need_login();
need_page(); 

function get_real_mailtype($type){
	$mail_map = array( "crm"=>"jihuo",
                   "hypd"=>"hyhy"
                  );
	if(array_key_exists($type,$mail_map)){
	    return $mail_map[$type];
	}
	return $type;
}
function get_mail_name($type){
    global $mail_array;
    if( $type=="jihuo"){
        $type="crm";
    }
    if($type=="hyhy"){
        $type="hypd";
    }
    if(array_key_exists($type,$mail_array))
        return $mail_array[$type];
    return  $type;
}
$filter_date_start = trim($_GET['startdate']);
$filter_date_end = trim($_GET['enddate']);

$filter_mail_type = trim($_GET['mail_type']);
$filter_edm = trim($_GET['edm']);
$edm_str = array();
global $edms_mail;
foreach ( $edms_mail as $edm => $mail_types){
	foreach($mail_types as $key=>$mail_type){
        $mail_types[$key] = get_real_mailtype($mail_type);		    
	}
	$edm_str[$edm] = "('" .implode("','",$mail_types). "')";
}

$sql_count = "select count(1) as count from edm where 1=1 ";
$cost_condition = "";
$condition = " ";
if( $filter_date_start && $filter_date_end ){
	$cost_condition = " and send_date between '$filter_date_start' and '$filter_date_end'";
	$condition .= " and date  between '$filter_date_start' and '$filter_date_end' ";
}elseif( $filter_date_start ){
	$cost_condition .= " and send_date = '$filter_date_start' ";
    $condition .= " and date = '$filter_date_start' ";
}elseif( $filter_date_end ){
	$cost_condition .= " and send_date = '$filter_date_end' ";
	$condition .= " and date = '$filter_date_end'";
}else{
    $filter_date_start = date("Y-m")."-01";
    $filter_date_end = date("Y-m-d");
    $condition .= " and date  between '$filter_date_start' and '$filter_date_end' ";
}

if($filter_mail_type){
	//edm 表中crm 邮件类型为jihuo
    $sql_mail_type = get_real_mailtype($filter_mail_type);
	$condition .= " and edm_id = '$sql_mail_type' "; 
}
if($filter_edm){
	$condition .= " and edm_id in {$edm_str[$filter_edm]} ";	
}
$sql = "select edm_id,
        case 
            when edm_id in {$edm_str['huiyee']} then 'huiyee' 
            when edm_id in {$edm_str['webpower']} then 'webpower' 
        end as platform,date,ip,uv,pv,register_num,order_num,total_price,suc_order_num,suc_total_price,c.sum_cost from edm 
		left join ( SELECT send_date,
		                case when substr(task_id,8,2)='gg' then 'hygg'
		                 when substr(task_id,8,2)='hy' then 'hyhy'
		                 when substr(task_id,5,10)='webpowerqx' then 'qingxi' 
		                 when substr(task_id,5,8)='webpower' then 'webpower'
		                 when substr(task_id,5,3)='crm' then 'jihuo'
		                 when substr(task_id,5,6)='tixing' then 'tixing' 
		                end AS mail_type,
		                sum(cost) as sum_cost 
		            FROM cost
		            where substr(task_id,1,3) ='edm' $cost_condition
		            group by send_date,
		                case when substr(task_id,8,2)='gg' then 'hygg'
		                 when substr(task_id,8,2)='hy' then 'hyhy'
		                 when substr(task_id,5,10)='webpowerqx' then 'qingxi'
		                 when substr(task_id,5,8)='webpower' then 'webpower'
		                 when substr(task_id,5,3)='crm' then 'jihuo'
		                 when substr(task_id,5,6)='tixing' then 'tixing'
		                end
		            ) as c on c.mail_type = edm_id and c.send_date = date 
		where 1=1 ";
$sql_count .= $condition;
$sql .= $condition." order by edm.date desc,edm.ip desc";
$result_count = DB::GetQueryResult($sql_count,true);

$count = $result_count['count'];
$page_size = $_GET['pagesize']?intval($_GET['pagesize']):30;
list($pagesize, $offset, $pagestring) = pagestring($count, $page_size);
$sql .= " limit $offset,$pagesize";

$datas = DB::GetQueryResult($sql,false);

//汇总信息
$sql_summary = "select edm_id,
		        case 
		            when edm_id in {$edm_str['huiyee']} then 'huiyee' 
		            when edm_id in {$edm_str['webpower']} then 'webpower' 
		        end as platform,sum(ip) as ip_sum,sum(uv) as uv_sum,sum(pv) as pv_sum,sum(register_num) as register_sum,
		        sum(order_num) as order_sum,sum(total_price) as money_sum,sum(c.sum_cost) as cost_sum,
		        sum(suc_order_num) as suc_order_sum,sum(suc_total_price) as suc_money_sum from edm 
		        left join ( SELECT send_date,
		                        case when substr(task_id,8,2)='gg' then 'hygg'
		                         when substr(task_id,8,2)='hy' then 'hyhy'
		                         when substr(task_id,5,10)='webpowerqx' then 'qingxi'
		                         when substr(task_id,5,8)='webpower' then 'webpower'
		                         when substr(task_id,5,3)='crm' then 'jihuo'
		                         when substr(task_id,5,6)='tixing' then 'tixing'
		                        end AS mail_type,
		                        sum(cost) as sum_cost 
		                    FROM cost
		                    where substr(task_id,1,3) ='edm' $cost_condition
		                    group by send_date,
		                        case when substr(task_id,8,2)='gg' then 'hygg'
		                         when substr(task_id,8,2)='hy' then 'hyhy'
		                         when substr(task_id,5,10)='webpowerqx' then 'qingxi'
		                         when substr(task_id,5,8)='webpower' then 'webpower'
		                         when substr(task_id,5,3)='crm' then 'jihuo'
		                         when substr(task_id,5,6)='tixing' then 'tixing'
		                        end
		                    ) as c on c.mail_type = edm_id and c.send_date = date 
		        where 1=1 ";
$sql_summary .= $condition." group by edm_id";
//获得每个邮件类型的汇总数据
$mail_sum = DB::GetQueryResult($sql_summary, false);
$mail_summary = array();
$sum_array = array();
foreach( $mail_sum as $key => $data ){
	//计算每个邮件类型的汇总信息
	$mail_summary[$data['edm_id']] = $data;
	$mail_summary[$data['edm_id']]['money_sum'] = round($mail_summary[$data['edm_id']]['money_sum'],2);
	$mail_summary[$data['edm_id']]['suc_money_sum'] = round($mail_summary[$data['edm_id']]['suc_money_sum'],2);
	$mail_summary[$data['edm_id']]['suc_order_rate'] = round($data['suc_order_sum']*100/$data['order_sum'],2)."%";
	$mail_summary[$data['edm_id']]['order_rate'] = round($data['order_sum']*100/$data['ip_sum'],2)."%";
	$mail_summary[$data['edm_id']]['register_rate'] = round($data['register_sum']*100/$data['ip_sum'],2)."%";
	if($data['cost_sum']){
	    $mail_summary[$data['edm_id']]['CPC'] = round($data['cost_sum']/$data['uv_sum'],2);
	    $mail_summary[$data['edm_id']]['CPA'] = round($data['cost_sum']/$data['register_sum'],2);
	    $mail_summary[$data['edm_id']]['CPS'] = round($data['cost_sum']/$data['order_sum'],2);
	    $mail_summary[$data['edm_id']]['ROI'] = round($data['suc_money_sum']/$data['cost_sum'],2);
	}else{
		$mail_summary[$data['edm_id']]['cost_sum'] = "--";
	    $mail_summary[$data['edm_id']]['CPC'] = "--";
        $mail_summary[$data['edm_id']]['CPA'] = "--";
        $mail_summary[$data['edm_id']]['CPS'] = "--";
        $mail_summary[$data['edm_id']]['ROI'] = $data['suc_money_sum']?">100":"--";
	}
	//获得每个平台的汇总数据
    foreach($edms_mail as $edm => $value){
        if($data['platform'] == $edm){
            $sum_array[$edm]['ip_sum'] += $data['ip_sum'];
		    $sum_array[$edm]['uv_sum'] += $data['uv_sum'];
		    $sum_array[$edm]['pv_sum'] += $data['pv_sum'];
		    $sum_array[$edm]['register_sum'] += $data['register_sum'];
		    $sum_array[$edm]['order_sum'] += $data['order_sum'];
		    $sum_array[$edm]['money_sum'] += $data['money_sum'];
		    $sum_array[$edm]['suc_order_sum'] += $data['suc_order_sum'];
            $sum_array[$edm]['suc_money_sum'] += $data['suc_money_sum'];
		    $sum_array[$edm]['cost_sum'] += $data['cost_sum']; 
        }
    }
    //获得总的汇总数据
	$sum_array['summary']['ip_sum'] += $data['ip_sum'];
	$sum_array['summary']['uv_sum'] += $data['uv_sum'];
	$sum_array['summary']['pv_sum'] += $data['pv_sum'];
	$sum_array['summary']['register_sum'] += $data['register_sum'];
	$sum_array['summary']['order_sum'] += $data['order_sum'];
    $sum_array['summary']['money_sum'] += $data['money_sum'];
	$sum_array['summary']['suc_order_sum'] += $data['suc_order_sum'];
	$sum_array['summary']['suc_money_sum'] += $data['suc_money_sum'];
	$sum_array['summary']['cost_sum'] += $data['cost_sum'];	
}
//计算每个平台汇总
foreach($edms_mail as $edm => $value){
	$sum_array[$edm]['order_rate'] = round($sum_array[$edm]['order_sum']*100/$sum_array[$edm]['ip_sum'],2)."%";
	$sum_array[$edm]['register_rate'] = round($sum_array[$edm]['register_sum']*100/$sum_array[$edm]['ip_sum'],2)."%";
	$sum_array[$edm]['money_sum'] = round($sum_array[$edm]['money_sum'],2);
	$sum_array[$edm]['suc_money_sum'] = round($sum_array[$edm]['suc_money_sum'],2);
	$sum_array[$edm]['suc_order_rate'] = round($sum_array[$edm]['suc_order_sum']*100/$sum_array[$edm]['order_sum'],2)."%";
	if($sum_array[$edm]['cost_sum']){
	    $sum_array[$edm]['CPC'] = round($sum_array[$edm]['cost_sum']/$sum_array[$edm]['uv_sum'],2);
	    $sum_array[$edm]['CPA'] = round($sum_array[$edm]['cost_sum']/$sum_array[$edm]['register_sum'],2);
	    $sum_array[$edm]['CPS'] = round($sum_array[$edm]['cost_sum']/$sum_array[$edm]['order_sum'],2);
	    $sum_array[$edm]['ROI'] = round($sum_array[$edm]['suc_money_sum']/$sum_array[$edm]['cost_sum'],2);
	}else{
		$sum_array[$edm]['cost_sum'] = "--";
	    $sum_array[$edm]['CPC'] = "--";
	    $sum_array[$edm]['CPA'] = "--";
	    $sum_array[$edm]['CPS'] = "--";
	    $sum_array[$edm]['ROI'] = $sum_array[$edm]['suc_money_sum']?">100":"--";
	}
}
//计算总的汇总数据
$sum_array['summary']['money_sum'] = round($sum_array['summary']['money_sum'],2);
$sum_array['summary']['order_rate'] = round($sum_array['summary']['order_sum']*100/$sum_array['summary']['ip_sum'],2)."%";
$sum_array['summary']['register_rate'] = round($sum_array['summary']['register_sum']*100/$sum_array['summary']['ip_sum'],2)."%";
$sum_array['summary']['suc_money_sum'] = round($sum_array['summary']['suc_money_sum'],2);
$sum_array['summary']['suc_order_rate'] = round($sum_array['summary']['suc_order_sum']*100/$sum_array['summary']['order_sum'],2)."%";
if($sum_array['summary']['cost_sum']){
    $sum_array['summary']['CPC'] = round($sum_array['summary']['cost_sum']/$sum_array['summary']['uv_sum'],2);
    $sum_array['summary']['CPA'] = round($sum_array['summary']['cost_sum']/$sum_array['summary']['register_sum'],2);
    $sum_array['summary']['CPS'] = round($sum_array['summary']['cost_sum']/$sum_array['summary']['order_sum'],2);
    $sum_array['summary']['ROI'] = round($sum_array['summary']['suc_money_sum']/$sum_array['summary']['cost_sum'],2);
}else{
	$sum_array['summary']['cost_sum'] = "--";
    $sum_array['summary']['CPC'] = "--";
    $sum_array['summary']['CPA'] = "--";
    $sum_array['summary']['CPS'] = "--";
    $sum_array['summary']['ROI'] = $sum_array['summary']['suc_money_sum']?">100":"--";
}
echo template ( 'manage_edm_day_sales_data', 
                array ( 'datas'=>$datas,'pagestring'=>$pagestring,
                        'filter_date_start'=>$filter_date_start,
                        'filter_date_end'=>$filter_date_end,
                        'page_size'=>$page_size,
                        'filter_mail_type' => $filter_mail_type,
                        'filter_edm' => $filter_edm,
                        'sum_array' => $sum_array,
                        'mail_summary' => $mail_summary
                       )
              );
?>