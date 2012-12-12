<?php
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . '/app.php');

need_manager ();
$action = '创建';
$current_menu = 'create_user';
if ($_POST) {
	// unique username per user
	$str_username = strval ( $_POST ['username'] );
	if (strlen ( $str_username ) < 4 || strlen ( $str_username ) > 16) {
		Session::Set ( 'error', '用户名长度应为4-16个字符！' );
		redirect ( WEB_ROOT . "/manage/user/create_user.php" );
	
	}
	$arr_eu = Table::Fetch ( 'users', $str_username, 'username' );
	if ($arr_eu) {
		Session::Set ( 'notice', '用户名已经存在,请重设用户名！' );
		redirect ( WEB_ROOT . "/manage/user/create_user.php" );
	}
	
	// validation password
	$str_password1 = strval ( $_POST ['password1'] );
	$str_password2 = strval ( $_POST ['password2'] );
	
	if (strlen ( trim ( $str_password1 ) ) == 0) {
		Session::Set ( 'error', '密码不能为空' );
		redirect ( WEB_ROOT . "/manage/user/create_user.php" );
	}
	if (strlen ( $str_password1 ) < 8) {
		Session::Set ( 'error', '密码最少设置为8个字符' );
		redirect ( WEB_ROOT . "/manage/user/create_user.php" );
	}
	if (! preg_match ( '/\d/', $str_password1 ) || ! preg_match ( '/[a-zA-Z]/', $str_password1 )) {
		Session::Set ( 'error', '密码必须要含有一个数字和一个字母' );
		redirect ( WEB_ROOT . "/manage/user/create_user.php" );
	}
	if ($str_password1 != $str_password2) {
		Session::Set ( 'error', '密码不一致，请重设！' );
		redirect ( WEB_ROOT . "/manage/user/create_user.php" );
	}
	// validation password end
	

	$grade = intval ( $_POST ['grade'] );
	
	$dept = intval ( $_POST ['dept'] );
	if (! is_manager_dept ( $dept )) {
		echo template ( 'manage_no_right' );
		die ();
	}
	
	$time = time ();
	$arr_new_user = array ("username" => $str_username, "password" => Users::GenPassword ( $str_password1 ), "created_by_id" => Session::Get ( 'user_id' ), "create_time" => $time, 'update_time' => $time, 'state' => '1' );
	$user_id = DB::Insert ( "users", $arr_new_user );
	$media = array();
	if ($user_id) {
		$menu_list = $_POST ['menu_list'];
		$grade = intval ( $_POST ['grade'] );
		$data_source = intval ( $_POST ['data_source'] );
		$channel = intval ( $_POST ['channel'] );
		$media_list = $_POST ['media_hidden'];
		if ($media_list) {
			//获得选中的媒体
			$media = explode ( ",", $media_list );
		}
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
				//				DB::Insert ( "permission", $arr_new_permission );
			}
			//			"role_id"=>,"menu_id"=>
		} else {
			$arr_new_permission = array ("user_id" => $user_id, "data_resource_id" => $data_source, "department_id" => $dept, "grade" => $grade, "role_id" => 1, "function_id" => $channel, "menu_id" => NULL );
			DB::Insert ( "permission", $arr_new_permission );
		}
		
		redirect ( WEB_ROOT . "/manage/user/manage.php" );
	} else {
		Session::Set ( 'notice', '用户创建失败' );
		redirect ( WEB_ROOT . "/manage/user/create_user.php" );
	}

}

echo template ( 'manage_user_edit', array ("action" => $action, 'current_menu' => $current_menu ) );
?>
