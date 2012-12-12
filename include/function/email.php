<?php
require_once(dirname(dirname(__FILE__)).'/library/phpemail/class.phpmailer.php');
require_once(dirname(dirname(__FILE__)).'/library/phpemail/class.smtp.php');
//include("class.smtp.php"); 
    /**
     * 发送邮件
     * @param $to_email 邮件接收人
     * @param $to_name 邮件接收人姓名
     * @param $to_title 主题
     * @param $to_content 内容
     * @param $accessories 附件位置
     */
  function send_email($to_email,$to_name='',$to_title='',$to_content='',$accessories=null)
  {
  	global $INI;
   error_reporting(E_STRICT);
   $mail = new PHPMailer(); //建立邮件发送类
   $mail->IsSMTP(); // 使用SMTP方式发送
   $mail->Host = $INI['email']['host']; // 您的企业邮局域名
   $mail->SMTPAuth = true; // 启用SMTP验证功能
   $mail->Username = $INI['email']['user']; // 邮局用户名(请填写完整的email地址)
   $mail->Password = $INI['email']['pass']; // 邮局密码
   $mail->From = $INI['email']['user']; //邮件发送者email地址
   $fromname = "会员爆料";
   $mail->FromName = "=?utf-8?B?".base64_encode($fromname)."?=";//为了163不乱码
   $mail->AddAddress($to_email, $to_name);//收件人地址，可以替换成任何想要接收邮件的email信箱,格式是AddAddress("收件人email","收件人姓名")
   $mail->IsHTML(true); // set email format to HTML //是否使用HTML格式
   $mail->CharSet = "utf-8"; //字符设置
   $mail->Encoding = "base64"; //编码方式题
   $subject = "$to_title";   //邮件标题
   $mail->Subject = "=?utf-8?B?".base64_encode($subject)."?=";
   $mail->Body = "$to_content"; //邮件内容
   $ROOT = dirname(__FILE__);
//   if(!$mail->AddAttachment($accessories[0],"hell.txt")){
//   		echo " add attachment error";
//   }
//	foreach($accessories as $v){
	if(!empty($accessories)){
		$mail->AddAttachment($accessories);
	}
//	}
//   var_dump($mail->Send());
   if(!$mail->Send())
   {
    echo '邮件发送失败！错误原因:'.$mail->ErrorInfo;
   }
  }
 ?>