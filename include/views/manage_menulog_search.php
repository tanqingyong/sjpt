<?php
$menu_logs = $arr_var ['menu_logs'];
$pagestring = $arr_var ['pagestring'];
$menu_grade1_array = $arr_var ['menu_grade1_array'];
$menu_array = $arr_var ['menu_array'];
$conditions = $arr_var ['conditions'];
?>
<script type="text/javascript">
function cha_xun(){
	$("form:first").submit();
}

var xmlHttp;
function selectsecondmenu()
{
	var parent_id = document.getElementById('menu_parent_id').value;
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null){
  		alert ("Browser does not support HTTP Request")
  		return;
  	} 
  	
	var url="<?php echo WEB_ROOT;?>/ajax/getsecondmenubyparent.php";
	url=url+"?parent_id="+parent_id;
	xmlHttp.onreadystatechange=stateChanged;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
} 

function stateChanged() 
{ 
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete"){ 
		var msg =xmlHttp.responseText;
		document.getElementById('menu_id').innerHTML=msg;
	} 
}

function GetXmlHttpObject()
{
	var xmlHttp=null;
	try{
	 	// Firefox, Opera 8.0+, Safari
	 	xmlHttp=new XMLHttpRequest();
	}catch (e){
	 // Internet Explorer
		 try{
		  	xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
		 }catch (e){
		  xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
		 }
	}
	return xmlHttp;
}

</script>
<div class="right-data">
<div class="search-box">
<form name='menu_log_form' method='get'>
<table>
<tr>
	<td>一级菜单：</td>
	<td><select id="menu_parent_id" name="menu_parent_id" class="f-city" onchange="javascript:selectsecondmenu()">
	<option></option>
		
                            	<?php
	echo '' . Utility::Option ($menu_grade1_array,$conditions['menu_parent_id']) . '';
	?>
				    	</select>&nbsp;</td>

	<td>二级菜单：</td>
	<td><div id="menu_id"><select name="menu_id" class="f-city">
	<option></option>
                            	<?php
	echo '' . Utility::Option (get_second_menu_by_parent_id($conditions['menu_parent_id']), $conditions['menu_id']) . '';
	?>
				    	</select>&nbsp;</div></td>

				
	<td>用户名：</td>
	<td><input type="text" name="user_name" class="h-input"
		value="<?php
		echo $conditions['user_name'];
		?>"></td>

			
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
			<td width="200">一级菜单</td>
			<td width="200">二级菜单</td>
			<td width="150">用户名</td>
			<td width="300">访问时间</td>
			<td width="150">来访IP</td>
		</tr>
	</thead>
	<?php
	$str = "";
	$index = 0;
	foreach ( $menu_logs as $one ) {
		$str .= "<tr ";
		$str .= $index % 2 ? "" : "class='alt'";
		$str .= "id='user-list-id-". $one ['parent_id']."'>";
		
		$str .="<td>".$menu_array[$one ['parent_id']]."</td>";
		$str .="<td>".$menu_array[$one ['menu_id']]."</td>";
		$str .="<td>".$one ['username']." </td>";
		$create_date = date ( 'Y-m-d H:i:s', $one ['action_time'] );
		$str .="<td>".$create_date."</td>";
		$str .="<td>".$one ['ip']." </td>";
		$str .="</tr>";
	}
	echo $str;
	?>


 	<?php 							
		if (!$menu_logs){
		echo " <tr><td colspan='999'>没有菜单访问日志记录</td></tr>"	;
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


