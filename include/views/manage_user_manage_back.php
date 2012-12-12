<?php
$users = $arr_var ['users'];
$pagestring = $arr_var ['pagestring'];
$filter_name = $arr_var ['filter_name'];
$filter_resource = $arr_var ['filter_resource'];
$filter_dept = $arr_var ['filter_dept'];
global  $deparment_user_grades;
?>
<script type="text/javascript">
function cha_xun(){
	$("form:first").submit();
}

</script>
<div class="right-data">
<div class="search-box">
<form name='user_manage_form' method='get'>
<table>
<tr>
	<td>用户名：</td>
	<td><input type="text" name="filter_name" class="h-input"
		value="<?php
		echo htmlspecialchars ( $filter_name );
		?>">&nbsp;</td>

	<td>数据源：</td>
	<td><select name="filter_resource" class="f-city">
                            	<?php
	echo '' . Utility::Option ( get_data_resource_option () , $filter_resource ) . '';
	?>
				    	</select>&nbsp;</td>

				
	<td>部门</td>
	<td><select name="filter_dept" class="f-city">
		<option></option>
				            <?php
									echo '' . Utility::Option ( get_department_option (), $filter_dept ) . '';
									?>
				            </select></td>

								
								<td>
								<a class="search-btn" onclick="cha_xun();" href="javascript:void(0);">查询</a>
								</td>
								</tr>
				  </table>
</form>
</div>
<div class="table-data-box">
<table  cellspacing="0" cellpadding="0" style="width:800px;">
	<thead>
	<tr>
			<td width="50">ID</td>
			<td width="150">用户名</td>
			<td width="200">注册时间</td>
			<td width="150">数据源</td>
			<td width="150">部门</td>
			<td width="160">权限</td>
			<td width="120">操作</td>
		</tr>
	</thead>
	<?php
	$str = "";
	$index = 0;
	foreach ( $users as $one ) {
		$str .= "<tr ";
		$str .= $index % 2 ? "" : "class='alt'";
		$str .= "id='user-list-id-". $one ['id']."'>";
		
		$str .="<td>".$one ['id']."</td>";
		$str .="<td>".$one ['username']."</td>";
		$create_date = date ( 'Y-m-d H:i', $one ['create_time'] );
		$str .="<td>".$create_date."</td>";
		$str .="<td>".$one ['data_resource_name']." </td>";
		$str .="<td>".$one ['department_name']." </td>";
		$str .="<td>";
		if($one ['data_resource_id']== 0 && $one ['department_id'] ==0){
			$str .="系统管理员";
		}else{
			$str .=$deparment_user_grades[$one ['data_resource_id']][$one ['department_id']][$one ['grade']];
		}
		$str .="</td>";
		$str .="<td class='op'>";
		$str .="<a href='". WEB_ROOT."/manage/user/edit.php?user_id=".$one ['id']."&dept_id=".$one['department_id']."&resource_id=".$one['data_resource_id']."&function_id=".$one['function_id']."'>编辑</a>&nbsp;&nbsp";
		$str .="<a href='". WEB_ROOT."/manage/user/delete_user.php?user_id=".$one ['id']."&dept_id=".$one['department_id']."&resource_id=".$one['data_resource_id']."&function_id=".$one['function_id']."'>删除</a>";
		$str .="</td>";
		$str .="</tr>";
	}
	echo $str;
	?>


 	<?php 							
		if (!$users){
		echo " <tr><td colspan='999'>没有用户</td></tr>"	;
		}
	?>
												         	
</table>
</div>
<div class="sect">
<table   width="100%" id="orders-list" cellspacing="0" cellpadding="0"
	border="0" class="coupons-table">

	<tr>
		<td><?php
		echo $pagestring;
		?>
	
	</tr>
</table>
</div>
</div>


