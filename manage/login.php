<?php
require_once (dirname ( dirname ( __FILE__ ) ) . '/app.php');

if ($_POST) {
	unset($_SESSION['error']);
	if (strtolower ( $_POST ['captcha'] ) == $_SESSION ['randcode']) {
		$login_admin = Users::GetLogin ( $_POST ['username'], $_POST ['password'] );
		if ($login_admin) {
				$cha = round((time()-$login_admin['create_time'])/24/3600);
				$state=$login_admin['state'];
				$yue=60-$cha;
				if($cha<60 && $state==1){
				Session::Set ( 'user_id', $login_admin ['id'] );
				Session::Set ( 'user_name', $login_admin ['username'] );
				Session::Set('surplus_time', $yue);
				//		error_log("-------------++++++++++++-----".var_export(Session,true));
				//		error_log("-------------++++++++++++-----".var_export($login_admin,true));
				DB::Update ( 'users', $login_admin ['id'], array ('login_time' => time (),'online'=>1 ) ); //账户登录成功，修改用户的在线状态为在线
				Session::Set ( 'user_auth', Users::GetAuthority($login_admin ['id']) );

				$url = get_curr_user_first_menu();
				if(!empty($url)){
					redirect ( WEB_ROOT .$url);
				}else{
					redirect ( WEB_ROOT .'/manage/user/update_password.php');
				}
				}else{
					Session::Set ( 'user_id', $login_admin ['id'] );
					redirect ( WEB_ROOT .'/manage/user/guoqi.php');					
				}
				
					
		} else {
			Session::Set ( 'error', '用户名密码不匹配！' );
		}
	} else {
		Session::Set ( 'error', '验证码错误！' );
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>窝窝团数据平台</title>
<link rel="stylesheet" href="/static/css/index.css" />
<script src="/static/js/index.js" type="text/javascript"></script>
<script type="text/javascript">
function login_sbm(){
	document.login.submit();
}
function change_captcha(){
	$("#captcha_img").attr("src","<?php
	echo WEB_ROOT;
	?>/manage/captcha.php?"+Math.random(1));
}
</script>
</head>

<body id="logo">
<div id="wrap"><!-- header -->
<div id="header"><a><cite>窝窝团数据平台</cite></a></div>
<?php if($session_notice=Session::Get('error',true)){?>
<div class="sysmsgw" id="sysmsg-error"><div class="sysmsg"><p><?php echo $session_notice; ?></p><span class="close">关闭</span></div></div> 
<?php }?>
<!-- header end --> <!-- content -->
<div id="content">

<div class="login-info"><!-- login -->
<form name="login" action="/manage/login.php" method="post">
<div class="login-content">
<p><span>用户名：</span> <input type="text" id="username" name="username" />
<span>密码：</span> <input type="password" id="password" name="password" /> <span>验证码：</span>
<input type="text" name="captcha" id="manage-captcha"
	style="width: 80px;" datatype="require" require="true" /> <img
	id="captcha_img" src="/manage/captcha.php" onclick="change_captcha()" />
</p>
<a class="login-btn" onclick="login_sbm()"><cite>登陆</cite></a></div>
</form>
<!-- login end --></div>
<div class="right-img"><a><cite>产品用户商家</cite></a></div>
</div>
<!-- content end --></div>
<div id='footer'>
	<div class='copyright'>
		<div>
		<p><span>Copyright © <?php echo "2010-".date("Y");?></span><span>&nbsp;窝窝团数据平台（www.55tuan.com）版权所有&nbsp;&nbsp;&nbsp;</p><p>Powered by 北京窝窝团信息技术有限公司企业信息部.</span></p>
		</div>
</div>
</div> 
</body>
</html>
