<?php
//  2011-8-11  pm 02:40:23  writen by sun-zhenchao
require_once(dirname(dirname(dirname(__FILE__))) . '/app.php');

need_manager();

$user_id = $_GET['user_id'];
$dept_id = abs(intval($_GET['dept_id']));
$resource_id = abs(intval($_GET['resource_id']));

if (!$user_id) return false;

if(!is_manager_dept($dept_id)){
	echo template('manage_no_right');die();
}

if(DB::Delete("permission",array("user_id"=>$user_id,"data_resource_id" => $resource_id, "department_id" => $dept_id))){
	if(!DB::Exist("permission" ,array("user_id"=>$user_id))){
		if(DB::Delete("users",array("id"=>$user_id))){
			Session::Set('notice', '用户删除成功');
			redirect(WEB_ROOT.'/manage/user/manage.php');
		}
	}else{
		Session::Set('notice', '用户删除成功');
		redirect(WEB_ROOT.'/manage/user/manage.php');
	}
}

Session::Set('notice', '用户删除失败');
redirect(WEB_ROOT.'/manage/user/manage.php');
?>