<?php
require_once(dirname(dirname(dirname(__FILE__))). '/app.php');


$datas = $arr_var['datas'];
$pagestring = $arr_var['pagestring'];
$filter_date_start = $arr_var['filter_date_start'];
$filter_date_end = $arr_var['filter_date_end'];
$page_size = $arr_var['page_size'];

$city_name = $arr_var['city_name'];
$pindao = $arr_var['pindao'];
$cate_second = $arr_var['cate_second'];
$cate_third = $arr_var['cate_third'];

?>
<script type="text/javascript">
function cha_xun(){
	$("form:first").attr('action','<?php echo WEB_ROOT;?>/manage/operation/day_report/goods_sort_data.php');
    $("form:first").submit();
}
function export_excel(){
	$("form:first").attr('action','<?php echo WEB_ROOT;?>/manage/operation/day_report/export_goods_sort_data.php');
    $("form:first").submit();
}

</script>


<div
	class="right-data"><!-- search  -->
<div class="search-box">
<form action="" method="get">
<table >
	<tr>
		<td><label>查询从</label> <input type="text"  style="width: 80px"
			name="stratdate" id="stratdate" onfocus="WdatePicker()"
			value='<?php echo $filter_date_start;?>' /></td>
		<td><label>到</label> <input type="text"  style="width: 80px"
		 name="enddate"	id="enddate" onfocus="WdatePicker()"
			value='<?php echo $filter_date_end;?>' /></td>
		<td><label>上线城市</label> <input type="text"  style="width: 60px"
			name="city_name" id="city_name" value='<?php echo $city_name;?>' /></td>
		<td><label>所属频道</label> <input type="text"  name="pindao"  style="width: 60px"
			id="pindao" value='<?php echo $pindao;?>' /></td>
		<td><label>所属二级分类</label> <input type="text"  style="width: 60px"
			name="cate_second" id="cate_second" value='<?php echo $cate_second;?>' />
		</td>
		<td><label>所属三级分类</label> <input type="text"  style="width: 60px"
			name="cate_third" id="cate_third" value='<?php echo $cate_third;?>' />
		</td>
		<td><a href="javascript:void(0);" onclick="cha_xun();"  style="width: 40px"
			class="search-btn">查询</a></td>
		<td><a href="javascript:void(0);" onclick="export_excel();"  style="width: 70px"
			class="export-btn">导出数据</a></td>
	</tr>
</table>

</form>
</div>
<!-- search end  --> <!-- table -->
<div class="table-data-box">
<table cellpadding="0" cellspacing="0" style="width: 2400px">
	<thead>
		<tr>
			<td>日期</td>
			<td>上线城市</td>
			<td>所属频道</td>
			<td>商品ID</td>
			<td>商品名称</td>
			<td>商品详情页UV</td>
<!--			<td>频道UV</td>-->
			<td>二级分类</td>
			<td>三级分类</td>
			<td>当天下单数</td>
			<td>当天下单商品数</td>
			<td>当天支付订单数</td>
			<td>当天支付商品数</td>
			<td>当天独立下单用户数</td>
			<td>当天独立购买用户数</td>
			<td>当天销售额</td>
			<td>当天毛利额</td>
			<td>累计下单数</td>
			<td>累计下单商品数</td>
			<td>累计支付订单数</td>
			<td>累计支付商品数</td>
			<td>累计独立下单用户数</td>
			<td>累计独立购买用户数</td>
			<td>累计销售额</td>
			<td>累计毛利额</td>
			<td>日平均下单数</td>
			<td>日平均下单商品数</td>
			<td>日平均支付订单数</td>
			<td>日平均支付商品数</td>
			<td>日平均独立下单用户数</td>
			<td>日平均独立购买用户数</td>
			<td>日平均销售额</td>
			<td>日平均毛利额</td>
			<td>商品销售单价</td>
			<td>商品结算单价</td>
			<td>商品毛利</td>
			<td>上线日期</td>
			<td>省份</td>
			<td>商品URL</td>
<!--			<td>排序值</td>-->
		</tr>
	</thead>
	<tbody>
	<?php foreach($datas as $data){?>
		<tr>
			<td><?php echo $data['date'];?></td>
			<td><?php echo $data['city'];?></td>
			<td><?php echo $data['type1_name'];?></td>
			<td><?php echo $data['goods_id'];?></td>
			<td><?php echo $data['goods_name'];?></td>
			<td><?php echo $data['uv'];?></td>
			<!--<td><?php echo "0";?></td>
			--><td><?php echo $data['type2_name'];?></td>
			<td><?php echo $data['type3_name'];?></td>
			<td><?php echo $data['order_num'];?></td>
			<td><?php echo $data['goods_num'];?></td>
			<td><?php echo $data['suc_order_num'];?></td>
			<td><?php echo $data['suc_goods_num'];?></td>
			<td><?php echo $data['user_num'];?></td>
			<td><?php echo $data['suc_user_num'];?></td>
			<td><?php echo $data['suc_total_price'];?></td>
			<td><?php echo $data['maoli'];?></td>
			<td><?php echo $data['all_order_num'];?></td>
			<td><?php echo $data['all_goods_num'];?></td>
			<td><?php echo $data['all_suc_order_num'];?></td>
			<td><?php echo $data['all_suc_goods_num'];?></td>
			<td><?php echo $data['all_user_num'];?></td>
			<td><?php echo $data['all_suc_user_num'];?></td>
			<td><?php echo $data['all_suc_total_price'];?></td>
			<td><?php echo $data['all_maoli'];?></td>
			<td><?php echo $data['avg_order_num'];?></td>
			<td><?php echo $data['avg_goods_num'];?></td>
			<td><?php echo $data['avg_suc_order_num'];?></td>
			<td><?php echo $data['avg_suc_goods_num'];?></td>
			<td><?php echo $data['avg_user_num'];?></td>
			<td><?php echo $data['avg_suc_user_num'];?></td>
			<td><?php echo $data['avg_suc_total_price'];?></td>
			<td><?php echo $data['avg_maoli'];?></td>
			<td><?php echo $data['price'];?></td>
			<td><?php echo $data['jiesuan_price'];?></td>
			<td><?php echo $data['goods_maoli'];?></td>
			<td><?php echo $data['start_time'];?></td>
			<td><?php echo $data['shengfen'];?></td>
			<td><?php echo $data['url'];?></td>
			<td></td>
		</tr>
		<?php }?>
		<?php if(!$datas){?>
		<tr>
			<td colspan='38'>没有数据</td>
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

