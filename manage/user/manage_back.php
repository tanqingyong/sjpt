<?php
require_once(dirname(dirname(dirname(__FILE__))) . '/app.php');

need_manager();

/* build condition */
$condition = array();


$sql = "select u.`id`,u.`username`,u.`create_time`,r.data_resource_name,d.`department_name`,p.`grade`,p.department_id,p.data_resource_id,p.function_id 
		from users u left join permission p on u.`id` = p.`user_id` left join department d on  p.`department_id` = d.`id` 
		left join data_resource r on r.id = p.data_resource_id
		where 1=1 and p.data_resource_id != 0 ";

$sql_count = "select count(distinct p.user_id,p.data_resource_id,p.department_id) as count 
		from users u left join permission p on u.`id` = p.`user_id` left join  department d on  p.`department_id` = d.`id`
		left join data_resource r on r.id = p.data_resource_id
		where 1=1  and p.data_resource_id != 0 ";


if(!is_manager_sys()){
	if(is_manager_resuorce()){
		$manager_resource = get_manager_resource(Session::Get("user_id"));
		$resources = implode(",", $manager_resource);
	//	print_r($manager_dept);
		$sql .= " and r.`id` in ($resources) ";
		$sql_count .= " and r.`id` in ($resources) ";
	}else{
		$manager_dept = get_manager_department(Session::Get("user_id"));
		$depts = implode(",", $manager_dept);
	//	print_r($manager_dept);
		$sql .= " and d.`id` in ($depts) ";
		$sql_count .= " and d.`id` in ($depts) ";
	}
}


//name query condition
$filter_name = trim($_GET['filter_name']);
if(strlen($filter_name)){
	$sql .=" and u.username='$filter_name' ";
	$sql_count .=" and u.username='$filter_name' ";
}

//grade query condition
$filter_resource = (int)$_GET['filter_resource'];
if($filter_resource){
	$sql .= " and p.data_resource_id = $filter_resource ";
	$sql_count .= " and p.data_resource_id = $filter_resource ";
}
$filter_dept = intval($_GET['filter_dept']);
if($filter_dept){
	$sql .= " and d.id = $filter_dept";
	$sql_count .= " and d.id = $filter_dept";
}

$sql .=" group by p.user_id,p.data_resource_id,p.department_id";


$result = DB::GetQueryResult($sql_count,true);
$count = $result['count'];
list($pagesize, $offset, $pagestring) = pagestring($count, 10);

$sql .=" limit $offset,$pagesize";
//print_r($sql);
$users = DB::GetQueryResult($sql,false);
//print_r($users);
//var_dump($users);
echo  template('manage_user_manage',array('users'=>$users,'pagestring'=>$pagestring,'filter_resource'=>$filter_resource,'filter_name'=>$filter_name, 'filter_dept' => $filter_dept,));
?>