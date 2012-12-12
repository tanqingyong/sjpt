<?php
require_once(dirname(dirname(dirname(__FILE__))) . '/app.php');
require_once(dirname(dirname(dirname(__FILE__))) . '/manage/export_excel.php');
need_login();

$table_array = array( 
                      'date'=>'日期',
						'media'=>'媒体',
						'city'=>'城市',
						'category'=>'频道',
						'g_uv'=>'详情页访问用户数',
						'old_g_uv'=>'详情页老用户数',
						'new_g_uv'=>'详情页新用户数',
						'add_user'=>'下单用户数',
						'old_add_user'=>'下单老用户数',
						'new_add_user'=>'下单新用户数',
						'pay_user'=>'成单用户数',
						'old_pay_user'=>'成单老用户数',
						'new_pay_user'=>'成单新用户数',
						'arpu'=>'ARPU值',
						'old_arpu'=>'老用户ARPU值',
						'new_arpu'=>'新用户ARPU值',
						'add_user_rate'=>'用户下单率',
						'old_add_user_rate'=>'老用户下单率',
						'new_add_user_rate'=>'新用户下单率',
						'pay_user_rate'=>'用户支付成功率',
						'old_pay_user_rate'=>'老用户支付成功率',
						'new_pay_user_rate'=>'新用户支付成功率',
						'add_order'=>'下单数',
						'pay_order'=>'成单数',
						'pay_goods'=>'销售量',
						'pay_money'=>'销售额',
						'goods_add_rate'=>'商品下单率',
						'order_pay_rate'=>'订单支付成功率',
						'kedanjia'=>'客单价'

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

$sql = "select * from operate_cate_data o where 1=1 $condition  ";

$datas = DB::GetQueryResult($sql,false);


foreach($datas as $key => $data){

    $datas[$key]['arpu']=round($data['pay_money']/$data['pay_user'],1);
    $datas[$key]['old_arpu']=round($data['old_pay_money']/$data['old_pay_user'],1);
    $datas[$key]['new_arpu']=round($data['new_pay_money']/$data['new_pay_user'],1);
    $datas[$key]['add_user_rate']=round($data['add_user']/$data['g_uv']*100,2)."%";
    $datas[$key]['old_add_user_rate']=round($data['old_add_user']/$data['old_g_uv']*100,2)."%";
    $datas[$key]['new_add_user_rate']=round($data['new_add_user']/$data['new_g_uv']*100,2)."%";
    $datas[$key]['pay_user_rate']=round($data['pay_user']/$data['add_user']*100,2)."%";
    $datas[$key]['old_pay_user_rate']=round($data['old_pay_user']/$data['old_add_user']*100,2)."%";
    $datas[$key]['new_pay_user_rate']=round($data['new_pay_user']/$data['new_add_user']*100,2)."%";
    $datas[$key]['goods_add_rate']=round($data['add_order']/$data['g_pv']*100,2)."%";
    $datas[$key]['order_pay_rate']=round($data['pay_order']/$data['add_order']*100,2)."%";
    $datas[$key]['kedanjia']=round($data['pay_money']/$data['pay_order'],2);
    
}
$all_datas=array();

$excel_name = "媒体数据(商品销售)";
export_excel($datas,$table_array,$all_datas,$excel_name);
?>