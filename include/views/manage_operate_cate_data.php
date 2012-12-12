<?php
require_once(dirname(dirname(dirname(__FILE__))). '/app.php');
$datas = $arr_var['datas'];
$pagestring = $arr_var['pagestring'];
$filter_date_start = $arr_var['filter_date_start'];
$filter_date_end = $arr_var['filter_date_end'];
$page_size = $arr_var['page_size'];
$summary = $arr_var['sum_array'];
$media_name = $arr_var['media_name'];
$all_datas = $arr_var['all_datas'];
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
    $("form:first").attr('action','<?php echo WEB_ROOT;?>/manage/dailymedia/operate_cate_data.php');
    $("form:first").submit();
}
function export_excel(){
    $("form:first").attr('action','<?php echo WEB_ROOT;?>/manage/dailymedia/export_operate_cate_data.php');
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
<table cellpadding="0" cellspacing="0" style="width: 2100px">
	<thead>
		<tr>
			<td>日期</td>
			<td>媒体</td>
			<td>城市</td>
			<td>频道</td>
			<td>详情页访问用户数</td>
			<td>详情页老用户数</td>
			<td>详情页新用户数</td>
			<td>下单用户数</td>
			<td>下单老用户数</td>
			<td>下单新用户数</td>
			<td>成单用户数</td>
			<td>成单老用户数</td>
			<td>成单新用户数</td>
			<td>ARPU值</td>
			<td>老用户ARPU值</td>
			<td>新用户ARPU值</td>
			<td>用户下单率</td>
			<td>老用户下单率</td>
			<td>新用户下单率</td>
			<td>用户支付成功率</td>
			<td>老用户支付成功率</td>
			<td>新用户支付成功率</td>
			<td>下单数</td>
			<td>成单数</td>
			<td>销售量</td>
			<td>销售额</td>
			<td>商品下单率</td>
			<td>订单支付成功率</td>
			<td>客单价</td>
		</tr>
	</thead>
	<tbody>
	<?php foreach($datas as $data){?>
		<tr>
			<td><?php echo $data['date'];?></td>
			<td><?php echo $data['media'];?></td>
			<td><?php echo $data['city']?></td>
			<td><?php echo $data['category'];?></td>
			<td><?php echo $data['g_uv'];?></td>
			<td><?php echo $data['old_g_uv'];?></td>
			<td><?php echo $data['new_g_uv']?></td>
			<td><?php echo $data['add_user'];?></td>
			<td><?php echo $data['old_add_user'];?></td>
			<td><?php echo $data['new_add_user'];?></td>
			<td><?php echo $data['pay_user'];?></td>
			<td><?php echo $data['old_pay_user']?></td>
			<td><?php echo $data['new_pay_user'];?></td>
			<td><?php echo round($data['pay_money']/$data['pay_user'],1);?></td>
			<td><?php echo round($data['old_pay_money']/$data['old_pay_user'],1);?></td>
			<td><?php echo round($data['new_pay_money']/$data['new_pay_user'],1);?></td>
			<td><?php echo round($data['add_user']/$data['g_uv'],2)*100;?>%</td>
			<td><?php echo round($data['old_add_user']/$data['old_g_uv'],2)*100;?>%</td>
			<td><?php echo round($data['new_add_user']/$data['new_g_uv'],2)*100;?>%</td>
			<td><?php echo round($data['pay_user']/$data['add_user'],2)*100;?>%</td>
			<td><?php echo round($data['old_pay_user']/$data['old_add_user'],2)*100;?>%</td>
			<td><?php echo round($data['new_pay_user']/$data['new_add_user'],2)*100;?>%</td>
			<td><?php echo $data['add_order'];?></td>
			<td><?php echo $data['pay_order'];?></td>
			<td><?php echo $data['pay_goods']?></td>
			<td><?php echo $data['pay_money'];?></td>
			<td><?php echo round($data['add_order']/$data['g_pv'],2)*100;?>%</td>
			<td><?php echo round($data['pay_order']/$data['add_order'],2)*100;?>%</td>
			<td><?php echo round($data['pay_money']/$data['pay_order'],2);?></td>

		</tr>
		<?php } foreach($all_datas as $data){?>
		<tr>
			<td>其他汇总</td>
			<td>-</td>
			<td>-</td>
			<td>-</td>
			<td><?php echo $data['g_uv'];?></td>
			<td><?php echo $data['old_g_uv'];?></td>
			<td><?php echo $data['new_g_uv']?></td>
			<td><?php echo $data['add_user'];?></td>
			<td><?php echo $data['old_add_user'];?></td>
			<td><?php echo $data['new_add_user'];?></td>
			<td><?php echo $data['pay_user'];?></td>
			<td><?php echo $data['old_pay_user']?></td>
			<td><?php echo $data['new_pay_user'];?></td>
			<td><?php echo round($data['pay_money']/$data['pay_user'],1);?></td>
			<td><?php echo round($data['old_pay_money']/$data['old_pay_user'],1);?></td>
			<td><?php echo round($data['new_pay_money']/$data['new_pay_user'],1);?></td>
			<td><?php echo round($data['add_user']/$data['g_uv'],2)*100;?>%</td>
			<td><?php echo round($data['old_add_user']/$data['old_g_uv'],2)*100;?>%</td>
			<td><?php echo round($data['new_add_user']/$data['new_g_uv'],2)*100;?>%</td>
			<td><?php echo round($data['pay_user']/$data['add_user'],2)*100;?>%</td>
			<td><?php echo round($data['old_pay_user']/$data['old_add_user'],2)*100;?>%</td>
			<td><?php echo round($data['new_pay_user']/$data['new_add_user'],2)*100;?>%</td>
			<td><?php echo $data['add_order'];?></td>
			<td><?php echo $data['pay_order'];?></td>
			<td><?php echo $data['pay_goods']?></td>
			<td><?php echo $data['pay_money'];?></td>
			<td><?php echo round($data['add_order']/$data['g_pv'],2)*100;?>%</td>
			<td><?php echo round($data['pay_order']/$data['add_order'],2)*100;?>%</td>
			<td><?php echo round($data['pay_money']/$data['pay_order'],2);?></td>
		</tr>
		<?php }if(!$datas){?>
		<tr>
			<td colspan='29'>没有数据</td>
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

