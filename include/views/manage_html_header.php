<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" id="<?php echo $INI['sn']['sn']; ?>">
<head>
	<meta http-equiv=content-type content="text/html; charset=UTF-8">
	<title><?php echo $INI['system']['sitename']; ?> - 窝窝团数据平台</title>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
	<link rel="shortcut icon" href="/static/icon/favicon.ico" />
	<link rel="stylesheet" href="/static/css/index.css" type="text/css" media="screen" charset="utf-8" />
	<script type="text/javascript">var WEB_ROOT = '<?php echo WEB_ROOT; ?>';</script>
	<script src="/static/js/index.js" type="text/javascript"></script>
	<script src="/static/js/tag.js" type="text/javascript"></script>
	<script type="text/javascript" src="/static/js/datepicker/WdatePicker.js"></script>
<!--	<script type="text/javascript" src="/static/jssrc/jquery-1.7.2.min.js"></script>-->
	<script type="text/javascript" src="/static/js/highcharts.js"></script>
	<?php echo Session::Get('script',true);; ?>
</head>
<body class="<?php echo $request_uri=='index'?'bg-alt':'newbie'; ?>">
<div id="pagemasker"></div><div id="dialog"></div>
<div id="wrap"  class="data">
