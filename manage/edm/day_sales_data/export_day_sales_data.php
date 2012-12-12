<?php
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/app.php');
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/manage/export_excel.php');
need_login();

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
$table_array = array( 'date'=>'日期', 
                      'edm_id'=>'邮件类型',
					  'platform'=>'发送平台 ',
					  'ip'=>'IP',
					  'uv'=>'UV',
					  'pv'=>'PV',
					  'register_num'=>'注册用户数',
					  'order_num'=>'订单量',
					  'total_price'=>'订单额',
                      'suc_order_num'=>'成单量',
                      'suc_total_price'=>'成单额',
                      'suc_order_rate'=>'成单率',
					  'order_rate'=>'订单转化率',
					  'register_rate'=>'新用户转化率',
					  'sum_cost'=>'发送成本',
					  'CPC'=>'CPC',
					  'CPA'=>'CPA',
					  'CPS'=>'CPS',
					  'ROI'=>'ROI');
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
$sql .= $condition." order by edm.date desc,edm.ip desc";
$datas = DB::GetQueryResult($sql,false);
//格式化导出数据
foreach($datas as $key=>$data){
    $datas[$key]['edm_id'] = get_mail_name($data['edm_id']);
    $datas[$key]['order_rate'] = round($data['order_num']*100/$data['ip'],2)."%";
    $datas[$key]['suc_order_rate'] = round($data['suc_order_num']*100/$data['order_num'],2)."%";
    $datas[$key]['register_rate'] = round($data['register_num']*100/$data['ip'],2)."%";
    if($data['sum_cost']){
        $datas[$key]['sum_cost'] = round($data['sum_cost'],2);
        $datas[$key]['CPC'] = round($data['sum_cost']/$data['uv'],2);
        $datas[$key]['CPA'] = round($data['sum_cost']/$data['register_num'],2);
        $datas[$key]['CPS'] = round($data['sum_cost']/$data['order_num'],2);
        $datas[$key]['ROI'] = round($data['suc_total_price']/$data['sum_cost'],2);
    }else{
        $datas[$key]['sum_cost'] = "--";
        $datas[$key]['CPC'] = "--";
        $datas[$key]['CPA'] = "--";
        $datas[$key]['CPS'] = "--";
        $datas[$key]['ROI'] = $data['suc_total_price']?">100":"--"; 
    }
}

$excel_name = "EDM日报销售数据";

export_excel($datas,$table_array,$data_field_array,$excel_name);