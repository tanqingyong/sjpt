<!--<script type="text/javascript" src="/static/js/xheditor/xheditor.js"></script>-->
    	<!-- header -->
        <div id="header">
        	<a><cite>窝窝团数据平台</cite></a>
        	<?php if(is_login()){?><a href="/manage/logout.php" class="logout" style="" onclick="">退出登录</a><span class="uname"><?php echo $_SESSION['user_name']."您好";?>,</span><?php }

        	?>
        	
        </div>
<?php
if(Session::Get('surplus_time')<=10 && Session::Get('surplus_time')>0){
	Session::Set('notice', "你账号还有".Session::Get('surplus_time')."天将被系统自动停用,为了避免到时登录不了系统，建议你现在就去OA填写<font color=\"red\">数据系统权限审批单</font>提交申请");	
}
if($session_notice=Session::Get('notice',true)){?>		
<div class="sysmsgw" id="sysmsg-success"><div class="sysmsg"><p><?php echo $session_notice; ?></p><span class="close">关闭</span></div></div> 
<?php Session::Set('surplus_time','');
}

?>
<?php if($session_notice=Session::Get('error',true)){?>
<div class="sysmsgw" id="sysmsg-error"><div class="sysmsg"><p><?php echo $session_notice; ?></p><span class="close">关闭</span></div></div> 
<?php }?>
