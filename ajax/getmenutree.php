<?php
require_once (dirname ( dirname ( __FILE__ ) ) . '/app.php');
need_manager ();
$dept_id = trim ( $_GET['dept_id'] );
$resource_id = trim ( $_GET['resource_id'] );
$grade = trim ( $_GET['grade'] );
$user_id =trim ( $_GET['user_id'] );

echo get_menu_tree ($dept_id,$resource_id);
echo '<script type="text/javascript">';

if($user_id===null || $user_id===''){
	if($grade == '4'||($dept_id==3&&$resource_id==1&&$grade==1)){
		echo  '$("input[type=\'checkbox\']").attr("checked",true);';
	}else{
		$menu_list = get_menu_ids($dept_id,$resource_id,false);
		foreach ($menu_list as $k=>$v){
			//echo  '$("input[id=\''.$v.'\'][type=\'checkbox\']").attr("checked",true);';
		}
	}
}else{
	$menu_list = get_menu_ids($dept_id,$resource_id,false,$user_id);
	foreach ($menu_list as $k=>$v){
		echo  '$("input[id=\''.$v.'\'][type=\'checkbox\']").attr("checked",true);';
	}
}
//一下代码实现当权限为推广部并且选中媒体统计时才显示媒体
/*echo '$(document).ready(function(){ 
	$("input[id=22]").change(function(){
		if(this.checked) {';
if($resource_id==1&&$dept_id==3&&$grade==1){
echo		'$("#media_data").css("display","block");';
}
echo		'}';
echo		'else {';
if($resource_id==1&&$dept_id==3&&$grade==1){
echo		'$("#media_data").css("display","none");';
}
echo	'}});
	});';*/
echo '</script>';
exit ();
?>