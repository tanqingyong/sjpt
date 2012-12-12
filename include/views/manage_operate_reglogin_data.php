<?php
require_once(dirname(dirname(dirname(__FILE__))). '/app.php');
$datas = $arr_var['datas'];
$pagestring = $arr_var['pagestring'];
$filter_date_start = $arr_var['filter_date_start'];
$filter_date_end = $arr_var['filter_date_end'];
$page_size = $arr_var['page_size'];
$summary = $arr_var['sum_array'];
$media_name = $arr_var['media_name'];

?>
<script type="text/javascript">
function GetParams(type) {
    var url = location.search; 
    var theRequest = new Array();
    var return_str = "";
    var order_type = 0;
    if (url.indexOf("?") != -1) {
       var str = url.substr(1);
       strs = str.split("&");
       for(var i = 0; i < strs.length; i ++) {
          theRequest[strs[i].split("=")[0]]=unescape(strs[i].split("=")[1]);
          if(strs[i].split("=")[0]=='order_type'){
              theRequest[strs[i].split("=")[0]]=type; 
              order_type=1;
          }
          if(i==strs.length-1){
              return_str +=strs[i].split("=")[0]+'='+theRequest[strs[i].split("=")[0]];
          }else{
              return_str +=strs[i].split("=")[0]+'='+theRequest[strs[i].split("=")[0]]+'&';
          }
       }
    }
    if(order_type==0){
        if(return_str)
            return_str +="&order_type="+type; 
        else 
            return_str +="order_type="+type; 
    }
    return return_str;
 }

function change_order_type(type){
    var url_arr = window.location.href.split('?');
    var url = url_arr[0];
    window.location.href=url+'?'+GetParams(type);
 
}
function change_data_type(){
    var url_arr = window.location.href.split('?');
    var url = url_arr[0];
    window.location.href=url+'?'+GetParams(type);
 
}

function cha_xun(){
    $("form:first").attr('action','<?php echo WEB_ROOT;?>/manage/dailymedia/operate_reglogin_data.php');
    $("form:first").submit();
}
function export_excel(){
    $("form:first").attr('action','<?php echo WEB_ROOT;?>/manage/dailymedia/export_operate_reglogin_data.php');
    $("form:first").submit();
}

</script>

<div
	class="right-data"><!-- search  -->
<div class="search-box">
<form action="" method="get">
<table>
	<tr>
		<!--<td><label>媒体名称</label> <select id="media_name" name="media_name"
			class="se_ect1">
			<option></option>
			<?php // echo ''.Utility::Option(get_platforms_for_search(),$media_name).''; ?>
		</select></td>
		<td><label>城市</label> <select id="city_name" name="city_name"
			class="se_ect1">
			<option></option>
			<?php  //echo ''.Utility::Option(get_paytype_option('operate_cate_data','city'),$city_name).''; ?>
		</select></td>
		<td><label>频道</label> <select id="cate_name" name="cate_name"
			class="se_ect1">
			<option></option>
			<?php // echo ''.Utility::Option(get_paytype_option('operate_cate_data','category'),$cate_name).''; ?>
		</select></td>
		-->
		<td><label>查询</label> <input type="text" width="10px" name="startdate"
			id="stratdate" onfocus="WdatePicker()"
			value='<?php echo $filter_date_start;?>' /></td>
		<!--		<td><label>到</label> <input type="text" width="10px" name="enddate"-->
		<!--			id="enddate" onfocus="WdatePicker()"-->
		<!--			value='<?php //echo $filter_date_end;?>' /></td>-->
		<td><a href="javascript:void(0);" onclick="cha_xun();"
			class="search-btn">查询</a></td>
		<td><a href="javascript:void(0);" onclick="export_excel();"
			class="export-btn">导出数据</a></td>
	</tr>
</table>
</form>

</div>
<!-- search end  --> <!-- table -->
<div class="table-data-box">
<table cellpadding="0" cellspacing="0" style="width: 1000px">
	<thead>
		<tr>
			<td>日期</td>
			<td>媒体</td>
			<td>注册用户</td>
			<td>注册用户(老)</td>
			<td>注册用户(新)</td>
			<td>登陆用户</td>
			<td>登陆用户(老)</td>
			<td>登录用户(新)</td>
		</tr>
	</thead>
	<tbody>
	<?php foreach($datas as $data){?>
		<tr>
			<td><?php echo $data['date'];?></td>
			<td><?php echo $data['media'];?></td>
			<td><?php echo $data['reg_user'];?></td>
			<td><?php echo $data['old_reg_user'];?></td>
			<td><?php echo $data['new_reg_user']?></td>
			<td><?php echo $data['login_user'];?></td>
			<td><?php echo $data['old_login_user'];?></td>
			<td><?php echo $data['login_user']-$data['old_login_user'];?></td>


		</tr>
		
		<?php }if(!$datas){?>
		<tr>
			<td colspan='8'>没有数据</td>
		</tr>
		<?php }?>
	</tbody>
</table>
</div>
<div class="sect">
<table width="100%" id="orders-list" cellspacing="0" cellpadding="0"
	border="0" class="coupons-table">
	<tr>
		<td><?php
		echo $pagestring;
		?></td>
	</tr>
</table>
</div>
<!-- table end --></div>

