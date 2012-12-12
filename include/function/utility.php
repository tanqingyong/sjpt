<?php
function get_city($ip = null) {
	$cities = option_category ( 'city', false, true );
	$ip = ($ip) ? $ip : Utility::GetRemoteIP ();
	$location = ip_location_youdao ( $ip );
	if ($location) {
		foreach ( $cities as $one ) {
			if (FALSE !== strpos ( $location, $one ['name'] )) {
				return $one;
			}
		}
	}
	return array ();
}

function ip_location_baidu($ip) {
	$u = "http://open.baidu.com/ipsearch/s?wd={$ip}&tn=baiduip";
	$r = mb_convert_encoding ( Utility::HttpRequest ( $u ), 'UTF-8', 'GBK' );
	preg_match ( '#来自：<b>(.+)</b>#Ui', $r, $m );
	return strval ( $m [1] );
}

function ip_location_youdao($ip) {
	$u = "http://www.youdao.com/smartresult-xml/search.s?type=ip&q={$ip}";
	$r = mb_convert_encoding ( Utility::HttpRequest ( $u ), 'UTF-8', 'GBK' );
	preg_match ( "#<location>(.+)</location>#Ui", $r, $m );
	return strval ( $m [1] );
}

/**
 * 取得订单相关状态的显示
 * @param string $type 状态类型,order_status,payment_status,shipping_status
 * @param int $value
 * @return string
 */
function get_status_display($type, $value) {
	$status_arr = array ();
	$status_arr ['order_status'] = array (0 => '未确认', 1 => '已确认', 2 => '已取消', 3 => '无效', 4 => '退货', 5 => '已分单', 6 => '部分分单' );
	$status_arr ['payment_status'] = array (0 => '未付款', 1 => '付款中', 2 => '已付款', 3 => '已退款', 4 => '已冻结(退款中)' );
	$status_arr ['shipping_status'] = array (0 => '未发货', 1 => '已发货', 2 => '已收货', 3 => '备货中', 4 => '已发货(部分商品)', 4 => '发货中(处理分单)' );
	return $status_arr [$type] [$value];
}

/**
 * 取得差异类型的显示
 * @param int $value
 * @return string
 */
function get_diff_type_display($value) {
	if ($_SESSION ["diff_type"]) {
		$diff_type = $_SESSION ["diff_type"];
	} else {
		$diff_type = data_row_to_array ( DB::GetTableRows ( "DIFFERENCE_TYPE" ), "type_id", "type_name" );
		Session::Set ( "diff_type", $diff_type );
	}
	return $diff_type [$value];
}
/**
 * 取得是否为实物的显示
 * @param int $value
 * @return string
 */
function get_real_display($value) {
	$is_real_arr = array (0 => '非实物', 1 => '实物' );
	return $is_real_arr [$value];
}

/**
 * 取得在线状态
 * @param int $value
 * @return string
 */
function get_online_status($value) {
	$is_real_arr = array (0 => '离线', 1 => '在线' );
	return $is_real_arr [$value];
}



/**
 * 取得日志类型的显示
 * @param int $value
 * @return string
 */
function get_log_type_display($value) {
	$is_real_arr = array (1 => '新建', 2 => '修改', 3 => '删除' );
	return $is_real_arr [$value];
}

function get_diff_mark_display($value) {
	$is_real_arr = array (1 => '有差异' );
	if ($is_real_arr [$value]) {
		return $is_real_arr [$value];
	}
	return "无差异";
}

/**
 * 取得配送显示
 * @param int $value
 * @return string
 */
function get_delive_type_display($value) {
	$delive_type = data_row_to_array ( DB::GetTableRows ( "SHIPPING" ), "shipping_id", "shipping_name" );
	return $delive_type [$value];
}

/**
 * 取得支付方式显示
 * @param int $value
 * @return string
 */
function get_pay_type_display($value) {
	$pay_type = data_row_to_array ( DB::GetTableRows ( "PAYMENT" ), "pay_id", "pay_desc" );
	return $pay_type [$value];
}

function mail_zd($email) {
	global $option_mail;
	if (! Utility::ValidEmail ( $email ))
	return false;
	preg_match ( '#@(.+)$#', $email, $m );
	$suffix = strtolower ( $m [1] );
	return $option_mail [$suffix];
}

function nanooption($string) {
	if (preg_match_all ( '#{(.+)}#U', $string, $m )) {
		return $m [1];
	}
	return array ();
}

global $option_year;
$option_year = array ('2010' => '2010', '2011' => '2011', '2012' => '2012', '2013' => '2013', '2014' => '2014', '2015' => '2015' );

global $option_month;
$option_month = array ('01' => '01', '02' => '02', '03' => '03', '04' => '04', '05' => '05', '06' => '06', '07' => '07', '08' => '08', '09' => '09', '10' => '10', '11' => '11', '12' => '12' );

global $option_week;
$option_week=array();
if (date('L',strtotime("-1 week"))){
$days=366;   
}else{
$days=365;
}
$weeks=round($days/7)+1;


for($i=0;$i<$weeks;$i++){
	if($i<10) $i="0".$i;
	array_push($option_week,$i);
	
}


global $option_index;
$option_index=array('1'=>'ip','2'=>'uv','3'=>'pv','4'=>'成单数','5'=>'成单金额','6'=>'订单转化率','7'=>'注册转化率','8'=>'成单率',
'9'=>'购买用户数','10'=>'购买转化率');

global $option_monitor;
$option_monitor=array('ip'=>'ip','uv'=>'uv','pv'=>'pv','order_num'=>'下单数','total_price'=>'下单金额','register_num'=>'注册用户数',
'suc_order_num'=>'成单数','suc_total_price'=>'成单金额');








global $pay_order_pos;
$pay_order_pos=array('up'=>'上方抢购填写订单','down'=>'下方抢购填写订单','ddfk'=>'订单生成付款页面','grddck'=>'个人中心-订单查看页面','qrgm'=>'确认购买');

function category_55tuan(){
	$sql="select  distinct pindao as 'type' from  product_summary_visit;";
	$aa = DB::GetQueryResult($sql,false);
	$res = array();
	foreach($aa as $key=>$val){
		$res[$val['type']]=$val['type'];
	}
	return  $res;
}


function meiti_fun(){
	$sql="SELECT  distinct platform_id from platform_update_hour where LENGTH(platform_id)>0 group by platform_id;";
	$aa = DB::GetQueryResult($sql,false);
	$res = array();
	foreach($aa as $key=>$val){
		$res[$val['platform_id']]=$val['platform_id'];
	}
	return  $res;
}

function get_last_month_and_year() {
	$current_month = idate ( 'm' );
	$current_year = idate ( 'Y' );
	$last_month = $current_month - 1;
	$last_month_of_year = $current_year;
	if ($last_month == 0) {
		$last_month = 12;
		$last_month_of_year = $current_year - 1;
	}
	return array ('year' => $last_month_of_year, 'month' => $last_month );
}

function generate_year_selector($element_name = 'select_year', $year = 2011) {
	global $option_year;
	$result = "<select name='" . $element_name . "' class='f-city'> ";
	$result .= Utility::Option ( $option_year, $year );
	$result .= "</select>";

	return $result;
}

function generate_month_selector($element_name = 'select_month', $month = 1) {
	global $option_month;
	$result = "<select name='" . $element_name . "' class='f-city'> ";
	$result .= Utility::Option ( $option_month, $month );
	$result .= "</select>";

	return $result;
}

function get_alipay_data_table_name($timestamp) {
	$year = idate ( "Y", $timestamp );
	$month = idate ( "m", $timestamp );
	$month = $month < 10 ? "0" . $month : $month;
	return "alipay_data_" . $year . $month;
}

function get_field_display($name, $value) {
	$field_array = array ("city_id" => get_city_display ( $value ), "pay_time" => date ( 'Y-m-d H:i:s', $value ), "difference_mark" => get_diff_mark_display ( $value ), "difference_type" => get_diff_type_display ( $value ) );

	if (array_key_exists ( $name, $field_array )) {
		return $field_array [$name];
	}
	return $value;
}
/**
 * 取得城市名称的显示
 * @param int $value
 * @return string
 */
function get_city_display($value) {
	if ($_SESSION ["city"]) {
		$city = $_SESSION ["city"];
	} else {
		$city = data_row_to_array ( DB::GetTableRows ( "CITY" ), "city_id", "city_name" );
		Session::Set ( "city", $city );
	}
	return $city [$value];
	return "";
}

/**
 * 取得部门名称的显示
 * @param int $value
 * @return string
 */
function get_dept_display($value) {
	$dept = get_department_option ();
	return $dept [$value];
}
/**
 * 取得所管理部门
 * @param int $user_id
 * @return int
 */
function get_manager_department($user_id) {
	$user_auth = Session::Get ( 'user_auth' );
	$manager_dept = array ();
	if ($user_auth) {
		foreach ( $user_auth as $permission ) {
			if ($permission ['grade'] == 4) {
				$manager_dept [] = $permission ['department_id'];
			}
		}
	}
	return array_unique($manager_dept);
}

/**
 * 取得所管理数据源
 * @param int $user_id
 * @return int
 */
function get_manager_resource($user_id) {
	$user_auth = Session::Get ( 'user_auth' );
	$manager_resource = array ();
	if ($user_auth) {
		foreach ( $user_auth as $permission ) {
			if ($permission ['grade'] == 4 && $permission ['department_id'] ==0) {
				$manager_resource [] = $permission ['data_resource_id'];
			}
		}
	}
	return array_unique($manager_resource);
}

/**
 * 取得部门的下拉列表
 * @param int $value
 * @return string
 */
function get_department_option() {
	if ($_SESSION ["department"]) {
		$dept = $_SESSION ["department"];
	} else {
		$dept = data_row_to_array ( DB::GetTableRows ( "department" ), "id", "department_name" );
		Session::Set ( "department", $dept );
	}
	return $dept;
}

/**
 * 取得部门的下拉列表
 * @param int $value
 * @return string
 */
function get_dept_option_by_resuorce($source_id) {
	$user_auth = Session::Get('user_auth');
	$auth_r = data_row_to_array ($user_auth,'department_id','grade');
	$data_r = data_row_to_array ( DB::GetTableRows ( "department", array ('data_resource_id' => $source_id ) ), "id", "department_name" );
	if(is_manager_resuorce($source_id)){
		return $data_r;
	}
	$dept = array();
	foreach ($data_r as $k=>$v){
		if(array_key_exists($k,$auth_r) && $auth_r[$k] == 4){
			$dept[$k] = $v;
		}
	}

	return $dept;
}

/**
 * 取得属性名显示
 * @param int $name
 * @return string
 */
function get_name_display($name) {
	$attr_name = array ("goods_number" => "商品数量", "goods_price" => "商品价格", "goods_amount" => "销售金额", "zhifubao_fee" => "支付宝付款", "yibao_fee" => "易宝付款", "caifutong_fee" => "财付通付款", "wangyinzaixian_fee" => "网银在线付款", "kuaiqian_fee" => "快钱付款", "shoujizhifu_fee" => "手机支付付款", "yue_fee" => "余额支付", "shipping_fee" => "运费", "cash_fee" => "现金支付", "difference_fee" => "差异金额", "difference_mark" => "差异标识", "difference_type" => "差异类型", "mark" => "备注" );
	if (array_key_exists ( $name, $attr_name )) {
		return $attr_name [$name];
	}
	return $name;
}

function data_row_to_array($array = array(), $c1, $c2) {
	$result = array ();
	foreach ( $array as $key => $row ) {
		$result [$row [$c1]] = $row [$c2];
	}
	return $result;
}

function get_six_indicator_month($string_date) {
	return substr ( $string_date, 0, 4 ) . "-" . substr ( $string_date, 4, 2 );
}

function get_data_resource_dept($department_id){
	return DB::GetFieldByKey('department','data_resource_id',$department_id);
}

/*
 * 判断指定用户数据源是最大权限值，0为最大权限值，可以访问所有数据源数据，
 * 同时判断用户权限等级是最大权限值，4为最大权限等级
 * @para $arr_auth 当前登录用户权限列表
 *
 */
function get_data_resource_role($arr_auth) {
	if (is_array ( $arr_auth )) {
		foreach ( $arr_auth as $auth ) {
			if (0 == $auth ['data_resource_id']) {
				if (4 == $auth ['grade'])
				return 'data_resource_owner';
				else
				return 'data_resource_admin';
			}
		}
	}
	return 'data_resource_member';
}

/*
 * 获取数据源下拉选项
 *
 */
function get_data_resource_option() {
	$user_auth = Session::Get('user_auth');
	$auth_r = data_row_to_array ($user_auth,'data_resource_id','grade');
	$data_r = data_row_to_array ( DB::GetTableRows ( "data_resource"), "id", "data_resource_name" );
	if(is_manager_sys()){
		return $data_r;
	}
	$resource = array();
	foreach ($data_r as $k=>$v){
		if(array_key_exists($k,$auth_r) && $auth_r[$k] == 4){
			$resource[$k] = $v;
		}
	}
	return $resource;
}

/*
 * 根据一级菜单获取二级菜单下拉选项
 *
 */
function get_second_menu_by_parent_id($parent_id) {
	$menu_array = array();
	$sql_menu = "SELECT id,menu_name FROM menu WHERE menu_grade = 2 AND parent_id = $parent_id";
	$menus = DB::GetQueryResult($sql_menu,false);
	foreach($menus as $menu){
		$menu_array[$menu['id']] = $menu['menu_name'];
	}
	return $menu_array;
}

/*
 * 判断指定用户部门是最大权限值，0为最大权限值，可以访问该数据源下所有数据
 * 同时判断用户权限等级是最大权限值，4为最大权限等级
 * @para $arr_auth 当前登录用户权限列表
 *
 */
function get_department_role($arr_auth) {
	if (is_array ( $arr_auth )) {
		foreach ( $arr_auth as $auth ) {
			if (0 == $auth ['department_id']) {
				if (4 == $auth ['grade'])
				return 'department_owner';
				else
				return 'department_admin';
			}
		}
	}
	return 'department_member';
}

/*
 * 获得指定用户数据源ID
 */
function get_data_resource_id($arr_auth) {
	$arr_data_resource = array ();
	if (is_array ( $arr_auth )) {
		foreach ( $arr_auth as $auth ) {
			$arr_data_resource [] = $auth ['data_resource_id'];
		}
	}
	return array_unique ( $arr_data_resource );
}

/*
 * 获得指定数据源下所有菜单
 * return  array('一级菜单ID'=>'二级菜单ID数组',...)
 */
function get_menu_by_data_resource_id($resource_ids) {
	if (is_array ( $resource_ids )) {
		$arr_tree = array ();
		$cache_menu = get_full_menus();

		foreach ( $resource_ids as $resource_id ) {
			foreach($cache_menu as $_row){
				if($resource_id==$_row['data_resource_id']&&2 == $_row ['menu_grade']){
					$arr_tree [$_row ['parent_id']] [] = $_row ['id'];
				}
			}
		}
		ksort($arr_tree);
		return $arr_tree;
	}

	return false;
}

/*
 * 获得指定用户的部门
 */
function get_department_id($arr_auth) {
	$arr_department = array ();
	if (is_array ( $arr_auth )) {
		foreach ( $arr_auth as $auth ) {
			$arr_department [] = $auth ['department_id'];
		}
	}
	return array_unique ( $arr_department );
}

/*
 * 获得指定部门下所有菜单
 * return  array('一级菜单ID'=>'二级菜单ID数组',...)
 */
function get_menu_by_department_id($department_ids) {
	if (is_array ( $department_ids )) {
		$arr_tree = array ();
		$cache_menu = get_full_menus();

		foreach ( $department_ids as $department_id ) {
			foreach($cache_menu as $_row){
				if($department_id==$_row['department_id']&&2 == $_row ['menu_grade']){
					$arr_tree [$_row ['parent_id']] [] = $_row ['id'];
				}
			}
		}
		ksort($arr_tree);
		return $arr_tree;
	}

	return false;
}

/*
 * 获得当前用户所有菜单
 * return  array('一级菜单ID'=>'二级菜单ID数组',...)
 */
function get_curr_user_menu($arr_auth){
	$curr_user_menu = array();
	$arr_tree = array ();
	if (is_array ( $arr_auth )) {
		foreach ( $arr_auth as $auth ) {
			$curr_user_menu [] = $auth ['menu_id'];
		}
		$curr_user_menu = array_unique ( $curr_user_menu );
		sort($curr_user_menu);
		$cache_menu = get_full_menus();
		foreach ( $curr_user_menu as $menu_id ) {
			foreach($cache_menu as $_row){
				if($menu_id==$_row['id']&&2 == $_row ['menu_grade']){
					$arr_tree [$_row ['parent_id']] [] = $_row ['id'];
				}
			}
		}
		ksort($arr_tree);
		return $arr_tree;
	}
	return false;
}

/*
 * 获得当前登录用户的首个二级菜单文件地址
 */
function get_curr_user_first_menu(){
	$arr_auth = Session::Get ( 'user_auth' );

	if('data_resource_member'!=get_data_resource_role($arr_auth)){
		redirect ( WEB_ROOT .'/manage/operation/day_report/day_sale_data.php');
	}
	if('department_member'!=get_department_role($arr_auth)){
		redirect ( WEB_ROOT .'/manage/operation/day_report/day_sale_data.php');
	}

	$arr_curr_user_menu = get_curr_user_menu($arr_auth);
	$menus = get_full_menus();

	foreach($arr_curr_user_menu as $menu){
		if(count($menu)>0){
			foreach($menus as $m){
				if($menu[0]==$m['id']){
					return $m['url'];
				}
			}
		}
	}

	return false;
}

/*
 * 获得指定数据源和部门下所有菜单
 * return  array('一级菜单ID'=>'二级菜单ID数组',...)
 */
function get_menu($department_id, $resouce_id) {
	if ($department_id !== null && $resouce_id !== null && $resouce_id !== "" && $department_id !== "") {
		$arr_tree = array ();
		$condition = array ('department_id' => $department_id, 'data_resource_id' => $resouce_id );
		$_rows = DB::GetTableRows ( 'menu', $condition );
		foreach ( $_rows as $menu ) {
			if (2 == $menu ['menu_grade'])
			$arr_tree [$menu ['parent_id']] [] = $menu ['id'];
		}
		return $arr_tree;
	}
	return false;
}

/*
 * 获得指定数据源和部门下所有菜单ID
 * return  array
 */
function get_menu_ids($department_id, $resouce_id,$is_admin, $user_id = null) {
	if ($department_id !== null && $resouce_id !== null && $resouce_id !== "" && $department_id !== "") {
		$arr_tree = array ();
		$condition = array ('department_id' => $department_id, 'data_resource_id' => $resouce_id);
		if(!empty($user_id)){
			$condition['user_id']=$user_id;
			$table = "permission";
			$col = "menu_id";
		}else{
			$table = "menu";
			if(!$is_admin){
				$condition['is_viewed_only_admin']="0";
			}
			$col = "id";
		}

		$_rows = DB::GetTableRows ( $table, $condition );
		foreach ( $_rows as $perms ) {
			$arr_tree [] = $perms [$col];
		}
		return $arr_tree;
	}
	return false;
}
/*
 * 获得当前用户下所有菜单显示HTML
 *
 */
function get_menu_tree($department_id, $resource_id) {
	$arr_menu_ids = get_menu ( $department_id, $resource_id );
	$tree_str .= TreeRender::tree_node_reader ( $arr_menu_ids, true );
	return $tree_str;
}
function get_filename_for_http_header($var){
	if(strpos($_SERVER['HTTP_USER_AGENT'],"MSIE")){
		return header('Content-Disposition: attachment; filename="'.urlencode($var).'"');
	}else{
		return header('Content-Disposition: attachment; filename="'.$var.'"');
	}
}

/*
 * 获得当前普通用户频道
 * return int  array('1'=>'旅游','2'=>'酒店','3'=>'生活馆','4'=>'化妆馆')
 */
function get_curr_user_channel(){
	$arr_auth = Session::Get ( 'user_auth' );

	if (is_array ( $arr_auth )) {
		foreach ( $arr_auth as $auth ) {
			$data_resource_id = $auth ['data_resource_id'];
			$department_id = $auth ['department_id'];
			$grade = $auth ['grade'];
				
			if($data_resource_id==1&&$department_id==1&&$grade==1){
				return $auth ['function_id'];
			}
		}
	}
	return 1;
}

