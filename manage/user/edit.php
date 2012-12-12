<?php
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . '/app.php');

need_manager ();

$id = abs ( intval ( $_GET ['user_id'] ) );
$dept_id = abs ( intval ( $_GET ['dept_id'] ) );
$resource_id = abs ( intval ( $_GET ['resource_id'] ) );
$channel = abs ( intval ( $_GET ['function_id'] ) );
$p_row = DB::GetTableRow ( "permission", array ('user_id' => $id, 'data_resource_id' => $resource_id, 'department_id' => $dept_id ) );
$grade = $p_row ['grade'];
$u_row = DB::GetTableRow ( "users", array ("id" => $id ) );
$username = $u_row ['username'];
$action = '编辑';
$current_menu = 'manage';
$mediaList=null;
//当用户是市场部的普通用户是，要显示媒体
if(is_user_exist($id)&&$resource_id==1){
	$mediaList = get_media_by_user($id,$resource_id,3,1);
}
if(!is_user_exist($id)){
	$url = get_curr_user_first_menu();
	if(!is_null($url))
		redirect ( WEB_ROOT .$url);
	else
		echo template('manage_no_right');
}

if(!is_manager_dept($dept_id)){
	echo template('manage_no_right');die();
}

if ($_POST) {
	// unique username per user
	$username = trim ( strval ( $_POST ['username'] ) );
	if (strlen ( $username ) < 4 || strlen ( $username ) > 16) {
		Session::Set ( 'error', '用户名长度应为4-16个字符！' );
		redirect ( WEB_ROOT . "/manage/user/edit.php?user_id=$id" );
	
	}
	
	$eu = Table::Fetch ( 'users', $username, 'username' );
	if ($eu && $eu ['id'] != $_POST ['id']) {
		Session::Set ( 'notice', '用户名已经存在,不能修改！' );
		redirect ( WEB_ROOT . "/manage/user/edit.php?user_id=$id" );
	}
	// end
	$arr_update = array ("username" => $username );
	
	// validation password
	$str_password1 = trim ( strval ( $_POST ['password1'] ) );
	$str_password2 = trim ( strval ( $_POST ['password2'] ) );
	
	if (strlen ( $str_password1 ) > 0) {
		if (strlen ( $str_password1 ) < 8) {
			Session::Set ( 'error', '密码最少设置为8个字符' );
			redirect ( WEB_ROOT . "/manage/user/edit.php?user_id=$id" );
		}
		if (! preg_match ( '/\d/', $str_password1 ) || ! preg_match ( '/[a-zA-Z]/', $str_password1 )) {
			Session::Set ( 'error', '密码必须要含有一个数字和一个字母' );
			redirect ( WEB_ROOT . "/manage/user/edit.php?user_id=$id" );
		}
		if ($str_password1 != $str_password2) {
			Session::Set ( 'error', '密码不一致，请重设！' );
			redirect ( WEB_ROOT . "/manage/user/edit.php?user_id=$id" );
		}
		$arr_update ['password'] = Users::GenPassword ( $str_password1 );
	}
	// validation password end
	

	$arr_update ['update_time'] = time ();
	$user_id = $_POST ['id'];
	//exit;
	$media = array();
	if (DB::Update ( "users", $user_id, $arr_update )) {
		$media_list = $_POST ['media_hidden'];
		if ($media_list) {
			//获得选中的媒体
			$media = explode ( ",", $media_list );
		}
		$grade = intval ( $_POST ['grade'] );
		$data_source = intval ( $_POST ['data_source'] );
		$dept = intval ( $_POST ['dept'] );
		$menu_list = $_POST ['menu_list'];
		$channel = intval ( $_POST ['channel'] );
		DB::Delete ( 'permission', array ("user_id" => $user_id, "data_resource_id" => $data_source, "department_id" => $dept) );
		
		if ($menu_list) {
			$menu_arr = split ( ",", $menu_list );
			foreach ( $menu_arr as $k => $v ) {
			//当选中媒体统计（日期）也就是$v为22
				//那么该用户data_resource_id为1，grade为1普通权限department_id为3市场部菜单为媒体统计日期
				if ($data_source == 1 && $dept == 3 && $grade == 1) {
					//首先查看fuction_city表的role_id=3并且function_name为媒体名称
					//					DB::Query();
					if(empty($media)){
						//未选中媒体那么默认插入0
						$arr_new_permission = array ("user_id" => $user_id, "data_resource_id" => $data_source, "department_id" => $dept, "grade" => $grade, "role_id" => 3, "function_id" => 0, "menu_id" => $v );
						DB::Insert ( "permission", $arr_new_permission );
					}
					else{
						foreach ( $media as $mk => $mv ) {
							//查询fuction_city表是否已经有记录
							$_rows = DB::GetTableRows ( "fuction_city", array ('role_id' => 3, 'function_name' => $mv ) );
							if ($_rows) {
								//有记录直接使用id
								$arr_new_permission = array ("user_id" => $user_id, "data_resource_id" => $data_source, "department_id" => $dept, "grade" => $grade, "role_id" => $_rows[0]['role_id'], "function_id" => $_rows[0]['id'], "menu_id" => $v );
								DB::Insert ( "permission", $arr_new_permission );
							} else {
								//fuction_city表没有媒体记录首先插入
								DB::Insert ( "fuction_city", array ("role_id" => 3, "function_name" => $mv ) );
								$function_id = DB::GetInsertId();
								$arr_new_permission = array ("user_id" => $user_id, "data_resource_id" => $data_source, "department_id" => $dept, "grade" => $grade, "role_id" => 3, "function_id" => $function_id, "menu_id" => $v );
								DB::Insert ( "permission", $arr_new_permission );
							}
						}
					}
					//					$arr_new_permission = array ("user_id" => $user_id, "data_resource_id" => $data_source, "department_id" => $dept, "grade" => $grade,"role_id"=>1,"function_id"=>$channel,"menu_id"=>$v);
				} else {
					$arr_new_permission = array ("user_id" => $user_id, "data_resource_id" => $data_source, "department_id" => $dept, "grade" => $grade, "role_id" => 1, "function_id" => $channel, "menu_id" => $v );
					DB::Insert ( "permission", $arr_new_permission );
				}
				//下两行为以前代码
//				$arr_new_permission = array ("user_id" => $user_id, "data_resource_id" => $data_source, "department_id" => $dept, "grade" => $grade, "role_id" => 1,"function_id"=>$channel, "menu_id" => $v );
//				DB::Insert ( "permission", $arr_new_permission );
			}
		} else {
			$arr_new_permission = array ("user_id" => $user_id, "data_resource_id" => $data_source, "department_id" => $dept, "grade" => $grade, "role_id" => 1,"function_id"=>$channel, "menu_id" => NULL );
			DB::Insert ( "permission", $arr_new_permission );
		}
		
		Session::Set ( 'notice', '修改用户信息成功' );
		redirect ( WEB_ROOT . "/manage/user/manage.php" );
	} else {
		Session::Set ( 'error', '修改用户信息失败' );
		redirect ( WEB_ROOT . "/manage/user/edit.php?user_id=$id?id={$id}" );
	}

}

echo template ( 'manage_user_edit', array ("id" => $id, 'dept_id' => $dept_id, 'resource_id' => $resource_id, "grade" => $grade,'channel' => $channel, 'username' => $username, "action" => $action, 'current_menu' => $current_menu,'mediaList'=>$mediaList ) );
?>
