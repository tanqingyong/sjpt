<?php
require_once (dirname ( dirname ( dirname ( __FILE__ ) ) ) . '/app.php');

$datas = $arr_var ['datas'];
$pagestring = $arr_var ['pagestring'];
$filter_date_start = $arr_var ['filter_date_start'];
$filter_date_end = $arr_var ['filter_date_end'];
$page_size = $arr_var ['page_size'];
?>
<script type="text/javascript">
function cha_xun(){
	$("form:first").attr('action','<?php
	echo WEB_ROOT;
	?>/manage/sitevisit/outsite_page.php');
    $("form:first").submit();
}
function export_excel(){
	$("form:first").attr('action','<?php
	echo WEB_ROOT;
	?>/manage/sitevisit/export_outsite_page.php');
    $("form:first").submit();
}
</script>

<div class="right-data"><!-- search  -->
<div class="search-box">
<form action="" method="get">
<table>
	<tr>
		<td><label>查询从</label> <input type="text" 
			name="stratdate" id="stratdate" onfocus="WdatePicker()"
			value='<?php
			echo $filter_date_start;
			?>' /></td>
		<td><label>到</label> <input type="text" name="enddate"
			id="enddate" onfocus="WdatePicker()"
			value='<?php
			echo $filter_date_end;
			?>' /></td>
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
<table cellpadding="0" cellspacing="0" style="width: 900px">
	<thead>
		<tr>
			<td>日期</td>
			<td>首页</td>
			<td>商品页</td>
			<td>订单页</td>
			<td>支付页</td>
			<td>登陆页</td>
			<td>注册页</td>
		</tr>
	</thead>
	<tbody>
	<?php
		foreach ( $datas as $data ) {
	?>
		<tr>
			<td><?php echo $data ['date'];?></td>
			<td><?php echo $data ['indexuv'];?></td>
			<td><?php echo $data ['spuv'];?></td>
			<td><?php echo $data ['orderuv'];?></td>
			<td><?php echo $data ['payuv'];?></td>
			<td><?php echo $data ['loginuv'];?></td>
			<td><?php echo $data ['reguv'];?></td>
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

