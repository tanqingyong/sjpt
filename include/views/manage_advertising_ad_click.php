<?php
require_once(dirname(dirname(dirname(__FILE__))). '/app.php');


$datas = $arr_var['datas'];
$pagestring = $arr_var['pagestring'];
$filter_date_start = $arr_var['filter_date_start'];
$filter_date_end = $arr_var['filter_date_end'];
$ad_type = $arr_var["ad_type"];
$ad_id = $arr_var["ad_id"];
$city = $arr_var["city"];
$page_size = $arr_var['page_size'];
?>
<script type="text/javascript">
function cha_xun(){
    $("form:first").attr('action','<?php echo WEB_ROOT;?>/manage/advertising/ad_click.php');
    $("form:first").submit();
}
function export_excel(){
    $("form:first").attr('action','<?php echo WEB_ROOT;?>/manage/advertising/export_ad_click.php');
    $("form:first").submit();
}

</script>


<div
	class="right-data"><!-- search  -->
<div class="search-box">
<form action="" method="get">
<table>
	<tr>
	    <td><label>广告ID</label>
	        <input type="text" style="width:80px" name="ad_id" id="ad_id" value="<?php echo $ad_id;?>"></input>
	    </td>
	    <td><label>类型</label>
            <input type="text" style="width:80px" name="ad_type" id="ad_type" value="<?php echo $ad_type;?>"></input>
        </td>
        <td><label>城市</label>
            <input type="text" style="width:80px" name="city" id="city" value="<?php echo $city;?>"></input>
        </td>
		<td><label>查询从</label> <input type="text" style="width:80px"
			name="startdate" id="startdate" onfocus="WdatePicker()"
			value='<?php echo $filter_date_start;?>' /></td>
		<td><label>到</label> <input type="text" style="width:80px" name="enddate"
			id="enddate" onfocus="WdatePicker()"
			value='<?php echo $filter_date_end;?>' /></td>
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
<table cellpadding="0" cellspacing="0">
	<thead>
		<tr>
			<td width="90px">日期</td>
			<td width="50px">城市</td>
			<td width="90px">广告名称</td>
			<td width="80px">类型</td>
			<td width="100px">广告ID</td>
			<td>广告URL</td>
			<td width="20px">PV</td>
			<td width="20px">UV</td>
			<td width="20px">IP</td>
		</tr>
	</thead>
	<tbody>
	<?php foreach($datas as $data){?>
		<tr>
			<td><?php echo $data['date'];?></td>
			<td><?php echo $data['city_name'];?></td>
            <td><?php echo $data['ads_name'];?></td>
            <td><?php echo $data['ads_type'];?></td>
            <td><?php echo $data['ads_id'];?></td>
            <td><?php echo $data['ads_url'];?></td>
            <td><?php echo $data['pv_sum'];?></td>
            <td><?php echo $data['uv_sum'];?></td>
            <td><?php echo $data['ip_sum'];?></td>
		</tr>
		<?php }?>
		<?php if(!$datas){?>
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
		?>
	
	</tr>
</table>
</div>
<!-- table end --></div>

