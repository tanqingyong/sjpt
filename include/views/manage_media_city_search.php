<?php
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . '/app.php');

$datas = $arr_var ['datas'];
$pagestring = $arr_var ['pagestring'];
$filter_date_start = $arr_var ['filter_date_start'];
$filter_date_end = $arr_var ['filter_date_end'];
$page_size = $arr_var ['page_size'];
$media_text = $arr_var ['media_text'];
$ad_text = $arr_var ['ad_text'];
$city_text = $arr_var ['city_text'];

?>
<script type="text/javascript">
function cha_xun(){
	$("form:first").attr('action','<?php
	echo WEB_ROOT;
	?>/manage/singlemedia/media_city_search.php');
    $("form:first").submit();
}
function export_excel(){
	$("form:first").attr('action','<?php
	echo WEB_ROOT;
	?>/manage/singlemedia/export_media_city_search.php');
    $("form:first").submit();
}
function order(order){
	$("form:first").append("<input type='hidden' name='order' value='"+order+"'>");
	$("form:first").attr('action','<?php echo WEB_ROOT;	?>/manage/singlemedia/media_city_search.php');
	$("form:first").submit();
}
</script>


<div class="right-data"><!-- search  -->
<div class="search-box" style="height:50px">
<form action="" method="get">
<table>
	<tr>
		<td><label>媒体：</label>
            <select id="media_text" name="media_text" style="width:160px">
              <option></option>
                <?php  echo ''.Utility::Option(get_platforms_for_search(),$media_text).''; ?>
            </select>
		</td>
		<td><label>广告位：</label>
			<input type="text" id="ad_text" name="ad_text" value="<?php echo $ad_text;?>" />
		</td>
		<td><label>城市：</label>
			<input type="text" id="city_text" name="city_text" value="<?php echo $city_text;?>" />
		</td><td></td><td></td>
	</tr>		
	<tr>
		<td><label>查询从</label> <input type="text" width="10px"
			name="stratdate" id="stratdate" onfocus="WdatePicker()"
			value='<?php
			echo $filter_date_start;
			?>' /></td>
		<td><label>到</label> <input type="text" width="10px" name="enddate"
			id="enddate" onfocus="WdatePicker()"
			value='<?php
			echo $filter_date_end;
			?>' /></td>
		<td><a href="javascript:void(0);" onclick="cha_xun();"
			class="search-btn">查询</a></td>
		<td><a href="javascript:void(0);" onclick="export_excel();"
			class="export-btn">导出数据</a></td>
		<td><a href="javascript:void(0)" onclick="order('asc')">↑升序</a>/
		<a href="javascript:void(0)" onclick="order('desc')">↓降序</a></td>
	</tr>
</table>

</form>
</div>
<!-- search end  --> <!-- table -->
<div class="table-data-box">
<table cellpadding="0" cellspacing="0" style="width: 1400px">
	<thead>
		<tr>
			<td>广告位</td>
			<td>日期</td>
			<td>城市</td>
			<td>IP</td>
			<td>UV</td>
			<td>PV</td>
			<td>订单数</td>
			<td>流水额</td>
			<td>注册量</td>
			<td>成单数</td>
			<td>成单金额</td>
			<td>订单转化率</td>
			<td>注册转化率</td>
			<td>成单率</td>
		</tr>
	</thead>
	<tbody>
	<?php
		foreach ( $datas as $data ) {
	?>
		<tr>
			<td><?php echo $data ['ad'];?></td>
			<td><?php echo $data ['date'];?></td>
			<td><?php echo $data ['city'];?></td>
			<td><?php echo $data ['uniq_ip'];?></td>
			<td><?php echo $data ['uv'];?></td>
			<td><?php echo $data ['pv'];?></td>
			<td><?php echo $data ['order_num'];?></td>
			<td><?php echo $data ['total_price'];?></td>
			<td><?php echo $data ['register_num'];?></td>
			<td><?php echo $data ['suc_order_num'];?></td>
			<td><?php echo $data ['suc_total_price'];?></td>
			<td><?php echo round($data ['order_num']*100/$data ['uniq_ip'],2); echo '%';?></td>
			<td><?php echo round($data ['register_num']*100/$data ['uniq_ip'],2); echo '%';?></td>
			<td><?php echo round($data ['suc_order_num']*100/$data ['order_num'],2); echo '%';?></td>
		</tr>
	<?php
	}
	?>
	<?php
	if (! $datas) {
	?>
		<tr>
			<td colspan='17'>没有数据</td>
		</tr>
	<?php
	}
	?>
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

