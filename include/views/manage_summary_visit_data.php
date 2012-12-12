<?php
require_once(dirname(dirname(dirname(__FILE__))). '/app.php');

$datas = $arr_var['datas'];
$pagestring = $arr_var['pagestring'];
$filter_date_start = $arr_var['filter_date_start'];
$filter_date_end = $arr_var['filter_date_end'];
$page_size = $arr_var['page_size'];
$rl_tpye=$arr_var['type'];

?>
<script type="text/javascript">
function cha_xun(){
	$("form:first").attr('action','<?php echo WEB_ROOT;?>/manage/operation/flow/summary_visit_data.php');
    $("form:first").submit();
}
function export_excel(){
	$("form:first").attr('action','<?php echo WEB_ROOT;?>/manage/operation/flow/export_summary_visit_data.php');
    $("form:first").submit();
}

</script>


<div
	class="right-data"><!-- search  -->
<div class="search-box">
<form action="" method="get">
<table>
	<tr>
		<td><label>频道：</label> <select id="type" name="type" class="se_ect1">
			<option></option>
			<?php  echo ''.Utility::Option(category_55tuan(),$rl_tpye).''; ?>
		</select></td>
		<td><label>查询从</label> <input type="text" width="10px"
			name="stratdate" id="stratdate" onfocus="WdatePicker()"
			value='<?php echo $filter_date_start;?>' /></td>
		<td><label>到</label> <input type="text" width="10px" name="enddate"
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
<table cellpadding="0" cellspacing="0" style="width: 1800px">
	<thead>
		<tr>
			<td>日期</td>
			<td>频道</td>
			<td>频道PV</td>
			<td>频道UV</td>
			<td>频道IP</td>
			<td>商品详情页PV</td>
			<td>商品详情页UV</td>
			<td>商品详情页IP</td>
			<td>在售商品数</td>
			<td>点击商品数</td>
			<td>上线商品数</td>
			<td>点击今日上线商品数</td>
			<td>下单用户数</td>
			<td>下单商品数</td>
			<td>下单数</td>
			<td>下单总额</td>
			<td>下单客单价</td>
			<td>成单用户数</td>
			<td>成单商品数</td>
			<td>成单数</td>
			<td>成单总额</td>
			<td>成单客单价</td>
		</tr>
	</thead>
	<tbody>
	<?php foreach($datas as $data){?>
		<tr>
			<td><?php echo $data['date'];?></td>
			<td><?php echo $data['pindao'];?></td>
			<td><?php echo $data['pv'];?></td>
			<td><?php echo $data['uv'];?></td>
			<td><?php echo $data['ip'];?></td>
			<td><?php echo $data['goods_pv'];?></td>
			<td><?php echo $data['goods_uv'];?></td>
			<td><?php echo $data['goods_ip'];?></td>
			<td><?php echo $data['ontheline_goods_num'];?></td>
			<td><?php echo $data['onthline_goods_click'];?></td>
			<td><?php echo $data['online_goods_num'];?></td>
			<td><?php echo $data['online_goods_click'];?></td>
			<td><?php echo $data['add_order_user_num'];?></td>
			<td><?php echo $data['add_order_goods_num'];?></td>
			<td><?php echo $data['add_order_num'];?></td>
			<td><?php echo $data['add_order_money'];?></td>
			<td><?php echo round($data['add_order_money']/$data['add_order_user_num'],2);?></td>
			<td><?php echo $data['pay_order_user_num'];?></td>
			<td><?php echo $data['pay_order_goods_num'];?></td>
			<td><?php echo $data['pay_order_num'];?></td>
			<td><?php echo $data['pay_order_money'];?></td>
			<td><?php echo round($data['pay_order_money']/$data['pay_order_user_num'],2);?></td>
		</tr>

		<?php }?>
		<?php if(!$datas){?>
		<tr>
			<td colspan='22'>没有数据</td>
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

