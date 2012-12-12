<?php
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/app.php');
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/manage/export_excel.php');

need_login();

$filter_date_start = trim($_GET['startdate']);
$filter_date_end = trim($_GET['enddate']);

$filter_mail_type = trim($_GET['mail_type']);
$filter_edm = trim($_GET['edm']);

global $mail_array;;

$table_array = array( 'send_date'=>'发送日期', 
                      'task_id' => '任务ID',
                      'mail'=>'邮件类型',
                      'edm'=>'发送平台 ',
                      'ip_sum'=>'IP',
                      'uv_sum'=>'UV',
                      'pv_sum'=>'PV',
                      'register_sum'=>'注册用户数',
                      'order_sum'=>'订单量',
                      'money_sum'=>'订单额',
                      'suc_order_sum'=>'成单量',
                      'suc_money_sum'=>'成单额',
                      'suc_order_rate'=>'成单率',
                      'order_rate'=>'订单转化率',
                      'register_rate'=>'新用户转化率',
                      'cost'=>'发送成本',
                      'CPC'=>'CPC',
                      'CPA'=>'CPA',
                      'CPS'=>'CPS',
                      'ROI'=>'ROI');

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
$edm_filter_array = array( "huiyee"=>array( "gg",
                                            "hy",
                                            "tixing",
                                          ),
                           "webpower" => array( "webpower",
                                                "crm",
                                                "qingxi"
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
    $date_start = date("Y-m-d",strtotime(date("Y-m-d"))-15*24*3600);
    $date_end = date("Y-m-d");
    $condition .= " and send_date between '$date_start' and '$date_end' ";
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

$sql .= $condition." group by task_id order by send_date desc,ip_sum desc";
$datas = DB::GetQueryResult($sql,false);

foreach($datas as $key => $data){
	 $datas[$key]['mail'] = $mail_array[$data['mail']];
     $datas[$key]['money_sum'] = round($data['money_sum'],2);
     $datas[$key]['suc_money_sum'] = round($data['suc_money_sum'],2);
     $datas[$key]['suc_order_rate'] = round($data['suc_order_sum']*100/$data['order_sum'],2)."%";
     $datas[$key]['order_rate'] = round($data['order_sum']*100/$data['ip_sum'],2)."%";
     $datas[$key]['register_rate'] = round($data['register_sum']*100/$data['ip_sum'],2)."%";
     if($data['cost']){
         $datas[$key]['cost'] = round($data['cost'],2);
         $datas[$key]['CPC'] = round($data['cost']/$data['uv_sum'],2);
         $datas[$key]['CPA'] = round($data['cost']/$data['register_sum'],2);
         $datas[$key]['CPS'] = round($data['cost']/$data['order_sum'],2);
         $datas[$key]['ROI'] = round($data['suc_money_sum']/$data['cost'],2);
     }else{
         $datas[$key]['cost'] = "--";
         $datas[$key]['CPC'] = "--";
         $datas[$key]['CPA'] = "--";
         $datas[$key]['CPS'] = "--";
         $datas[$key]['ROI'] = $data['suc_money_sum']?">100":"--";
     }
}

$excel_name = "EDM发送任务数据";
export_excel($datas,$table_array,$data_field_array,$excel_name);
?>