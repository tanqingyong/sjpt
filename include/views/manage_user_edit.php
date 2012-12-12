<?php
$id = $arr_var ['id'];
$grade = $arr_var ['grade'];
$username = $arr_var ['username'];
$action = $arr_var ['action'];
$current_menu = $arr_var ['current_menu'];
$dept_id = $arr_var ['dept_id'];
$resource_id = $arr_var ['resource_id'];
$channel = $arr_var['channel'];
$mediaList = $arr_var['mediaList'];
if(empty($channel)) $channel = 1;
global  $deparment_user_grades;
global $arr_channel;
?>
<script type="text/javascript">
$(document).ready(function(){
	<?php
	if ($action != '编辑') {
		?>
	$("input[name='username']").val("");
	<?php
	}
	?>
	$("input[name='password1']").val("");
	$("input[name='password2']").val("");
	//	deptReload();
	$("#data_source").change(deptReload);
	$("#dept").change(gradeReload);
	$("#grade").change(menuReload);
	<?php if($resource_id==1&&$dept_id==1&&$grade==1){ ?>
		$("#channel_data").css("display","block");
	<?php }?>
	<?php if($resource_id==1&&$dept_id==3&&$grade==1){ ?>
		$("#media_data").css("display","block");
	<?php }?>	
	deptReload();
	setMediaNames();
});
function cha_xun(){
	getMediaNames();
	if(check_value()){
		$("form:first").submit();
	}
}
//设置之前选中的媒体
function setMediaNames(){
	 o = document.getElementById("media_name");
	 	//首先让所有选项都不选中
	 	for(i=0;i<o.length;i++){
		 	o.options[i].selected=false;
	 	}
	 	//设置选中的
	    for(i=0;i<o.length;i++){ 
		    <?php foreach ($mediaList as $k=>$v){?>
		    	var valu = <?php echo '"'.$v.'"';?>;
		    	  if(o.options[i].value == valu){
		    		  o.options[i].selected=true;
		    		  continue;
		    	  }
	    	<?php }?>
	    }
}
//获得选中媒体的名称
function getMediaNames()
{
    o = document.getElementById("media_name");
    var intvalue="";
    for(i=0;i<o.length;i++){   
        if(o.options[i].selected){
            intvalue+=o.options[i].value+",";
        }
    }
    intvalue = intvalue.substr(0,intvalue.length-1);
    document.getElementById("media_hidden").value = intvalue;
}
function menu_click(){
	var id = $(this).attr("id");
	var check =  $(this).attr("checked");
	

	//下级菜单
	$(this).parent("li").find("input[type='checkbox']").each(function(){
		if($(this).attr("id")!=id){
			if(check){
				$(this).attr("checked",true);
			}else{
				$(this).attr("checked",false);
			}
		}
	});

	//上级菜单
	$(this).parents("li").each(function(){
		var first = $(this).children("input");
		if(first.attr("id") != id && check){
			first.attr("checked",true);
		}
	});

	var check_state = false;
	
	$(this).parent().parent().find("input[type='checkbox']").each(function(){
//		alert($(this).attr("id") + "" + $(this).attr("checked"));
		if($(this).attr("id")!= id && $(this).attr("checked")){
			check_state = true;
			return;
		}
	});

	if(!check && !check_state){
		$(this).parents("li").each(function(){
			var first = $(this).children("input");
			if(first.attr("id") != id){
//				alert(first.attr("id"));
				first.attr("checked",false);
			}
		});
	}
}

function deptReload(){
	var r_id = $("#data_source").val();
	var d_id = $("#dept").val();
	if(!d_id){
		d_id = '<?php echo $dept_id; ?>';
	}
	$.get("<?php
			echo WEB_ROOT;
			?>/ajax/getdepartment.php", { user_id:'<?php echo $id; ?>',resource_id:r_id,dept_id : d_id},function(data){
					$("#dept").html(data);
					gradeReload();
	});
}

function gradeReload(){
	var d_id = $("#dept").val();
	var r_id = $("#data_source").val();
	var g_id = $("#grade").val();
	if(!g_id){
		g_id = '<?php echo $grade; ?>';
	}
	$.get("<?php
			echo WEB_ROOT;
			?>/ajax/getgrade.php", { user_id:'<?php echo $id; ?>',dept_id : d_id,resource_id:r_id,grade:g_id},function(data){
					$("#grade").html(data);
					menuReload();
	});
}

function menuReload(){
	var d_id = $("#dept").val();
	var r_id = $("#data_source").val();
	var g_id = $("#grade").val();
	if(d_id==1&&r_id==1&&g_id==1){
		$("#channel_data").css("display","block");
	}else{
		$("#channel_data").css("display","none");
	}
	if(d_id==3&&r_id==1&&g_id==1){
		$("#media_data").css("display","block");
	}else{
		$("#media_data").css("display","none");
	}
	$.get("<?php
	echo WEB_ROOT;
	?>/ajax/getmenutree.php", { user_id:'<?php echo $id; ?>',dept_id : d_id,resource_id:r_id,grade:g_id},function(data){
			$("#menu-tree-sub").html(data);
			$("input[type='checkbox']").click(menu_click);
		 });
}

function check_value(){
	if(!check_username()){
		$("#signup-username").focus();
		return false;
	}
	if(!check_password()){
		$("#signup-password").focus();
		return false;
	}
	if(!check_confirmpwd()){
		$("#signup-password-confirm").focus();
		return false;
	}
	menu_selected();
	return true;
}
function check_username(){
	var username = $("#signup-username").val();
	<?php
		if ($action == '编辑') {
			echo 'if(username=="'.$username.'") return true;';
		}
	?>

	if( username.length<4 || username.length>16 ){
		$("#username_msg").html("用户名长度必须在4-16位之间");
		$("#signup-username").attr('class','f-input errorInput')
        $("#signup-username").focus();
        return false;
    }
    $.post("<?php
				echo WEB_ROOT;
				?>/ajax/selfvalidator.php", {name:"username",value:username },function(data){
            if( data == 0 ){
                $("#username_msg").html("用户名已存在");
                $("#signup-username").attr('class','f-input errorInput')
                $("#signup-username").focus();
                return false;
            }
    });
	$("#username_msg").html("");
	$("#signup-username").attr('class','f-input')
	return true;
}
function check_password(){
	var re1= new RegExp("[a-zA-Z]");
    var re2= new RegExp("[0-9]");
    var password = $("#signup-password").val();
	<?php
		if ($action == '编辑') {
			echo 'if(!password) return true;';
		}
	?>
    
    if( password.length<8 ||!re1.test(password)||!re2.test(password)){
        if( password.length<8 ){
            $("#pwd_msg").html("密码必须大于8位");
        }
        if(!re1.test(password)||!re2.test(password)){
            $("#pwd_msg").html("密码必须包含一位数字和和一位字母");
        }
        $("#signup-password").attr('class','f-input errorInput')
        $("#signup-password").focus();
        return false;
    }else{
        $("#pwd_msg").html("");
        $("#signup-password").attr('class','f-input')
        return true;
    }
}
function check_confirmpwd(){
	var re1= new RegExp("[a-zA-Z]");
    var re2= new RegExp("[0-9]");
	var confirmpwd = $("#signup-password-confirm").val();
	var password = $("#signup-password").val();

	<?php
		if ($action == '编辑') {
			echo 'if(!confirmpwd && !password) return true;';
		}
	?>
	if( confirmpwd.length<8 || !re1.test(confirmpwd) || !re2.test(confirmpwd) || confirmpwd != password){
        if(confirmpwd.length<8){
            $("#cpwd_msg").html("密码必须大于8位");
        }
        if(!re1.test(confirmpwd) || !re2.test(confirmpwd)){
            $("#cpwd_msg").html("密码必须包含一位数字和和一位字母");
        }
        if(confirmpwd != password){
            $("#cpwd_msg").html("重复密码和密码不一致");
        }
        $("#signup-password-confirm").attr('class','f-input errorInput');
        $("#signup-password-confirm").focus();
        return false;
    }else{
        $("#cpwd_msg").html("");
        $("#signup-password-confirm").attr('class','f-input')
        return true;
    }
}

function menu_selected(){
	var a= $("input[type='checkbox']");
	var menu_arr = new Array();
	$.each(a,function(){
		if($(this).attr("checked")==true){
			menu_arr.push($(this).attr("id"));
		}
	});
	$("#menu_list").val(menu_arr.toString());
}

</script>

<div class="right-data">
<div class="content-info">
<form id="signup-user-form" method="post"
	onsubmit="return check_value();" class="validator"><input type="hidden"
	name='id' value='<?php
	echo $id;
	?>' />
<div class="field username"><label for="signup-username">用户名</label> <input
	type="text" size="30" name="username" id="signup-username"
	class="f-input"
	value="<?php
	echo $action == '编辑' ? $username : "";
	?>"
	onkeyup="check_username()" />
<span id="username_msg" style="color: red"></span>
<span class="hint">填写4-16个字符，一个汉字为两个字符</span>
<br/>
<br/>
<br/>
</div>
<div class="field password"><label for="signup-password">密码</label> <input
	type="password" size="30" name="password1" id="signup-password"
	class="f-input" onkeyup="check_password()" />
	<span id="pwd_msg" style="color: red"></span>
	<span class="hint">
    <?php
	if ($action == '编辑')
		echo '如果不想修改密码，请保持空白;';
	else
		echo '密码至少8位，且必须含有字母和数字';
	?>
    </span>
    <br/>
	<br/>
	<br/>
</div>
<div class="field password"><label for="signup-password-confirm">确认密码</label>
<input type="password" size="30" name="password2"
	id="signup-password-confirm" class="f-input"
	onkeyup="check_confirmpwd()" />
	<span id="cpwd_msg" style="color: red"></span>
	<br/>
	<br/>
</div>

<div class="field password"><label for="signup-password-confirm">数据源</label>
<select id="data_source" name="data_source" class="">
                            	<?php
									echo '' . Utility::Option ( get_data_resource_option () , $resource_id ) . '';
								?>
							</select></div>

<div class="field password"><label for="signup-password-confirm">部门</label>
<select id="dept" name="dept" class="">
<?php 
	 echo Utility::Option (get_dept_option_by_resuorce($resource_id),$dept_id);
?>
							</select></div>
<div class="field city"><label id="enter-address-city-label"
	for="signup-city">用户权限</label> <select id="grade" name="grade" class="">
	<?php echo  Utility::Option ( $deparment_user_grades[$resource_id][$dept_id] ,$grade);?>
							</select></div>

<div id="channel_data" class="field" style="display:none;">
	<label for="signup-password-confirm">频道数据</label>
	<select id="channel" name="channel" class="">
	    <?php echo Utility::Option ($arr_channel,$channel);?>
	</select>
</div>
<input type="hidden" id="media_hidden" name="media_hidden">
<div id="media_data" class="field" style="display:none;">
	<label for="signup-password-confirm">媒体</label>
	<select id="media_name" name="media_name" class="" size="10"  multiple="multiple">
	    <?php echo Utility::Option (get_platforms_option());?>
	</select>
	
</div>
<ul id="menu-tree-sub">

</ul>
<input type="hidden" id="menu_list" name="menu_list"
	value="" />

<div class="field city">
	<label > </label> 
	<a class="submit-btn" href="javascript:void(0);" onclick="cha_xun();"><?php
	echo $action;
	?></a>
</div>
</form>
</div>
</div>

<script type="text/javascript">

</script>


