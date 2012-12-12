<?php
require_once(dirname(dirname(dirname(__FILE__))). '/app.php');
$date = $arr_var['date'];
$ip = $arr_var['ip'];
$uv = $arr_var['uv'];
$pv = $arr_var['pv'];
$suc_order_num = $arr_var['suc_order_num'];
$suc_goods_num = $arr_var['suc_goods_num'];
$suc_total_price = $arr_var['suc_total_price'];
$act_num = $arr_var['act_num'];
$user_num = $arr_var['user_num'];

$filter_date_start = $arr_var['filter_date_start'];
$media_name = $arr_var['media_name'];
$ptitle = "每日平台绩效";
$sub_title="只显示每周环比数据";
$ytitle = $media_name."媒体数据";

/**       		
 INSERT INTO `sjpt`.`menu` (
`id` ,
`data_resource_id` ,
`department_id` ,
`is_viewed_only_admin` ,
`menu_grade` ,
`parent_id` ,
`menu_name` ,
`url`
)
VALUES (
NULL , '1', '3', '0', '2', '20', '每日平台绩效图表', '/manage/dailymedia/date_search_chart.php'
);

 * 
 */
?>
<script type="text/javascript">
$(function () {
    var chart;
    $(document).ready(function() {
        chart = new Highcharts.Chart({
            chart: {
                renderTo: 'container',
                type: 'spline' //spline 平滑曲线 line 直线  pie饼图 areaspline 区域图 
            },
            title: {
                text: '<?php echo $ptitle;?>'
            },
            subtitle: {
                text: '<?php echo '';//$sub_title;?>'
            },
            xAxis: {
                categories: <?php echo $date;?>
            },
            yAxis: {
                title: {
                    text: '<?php echo $ytitle;?>'
                },
                labels: {
                    formatter: function() {
                        return this.value
                    }
                }
                
            },
            tooltip: {
                crosshairs: true,
                shared: true //同一坐标上的数据都显示
            },
            plotOptions: {
                spline: {
                    marker: {
                        radius: 4,
                        lineColor: '#666666',
                        lineWidth: 1
                    }
                }
            },
            series: [{ //折线数据
                name: 'ip',
                marker: {
                    symbol: 'square'
                },
                data: $(<?php echo $ip;?>).toArray()
    
            },{
                name: 'uv',
                marker: {
                    symbol: 'square'
                },
                data: $(<?php echo $uv;?>).toArray()
    
            },{
                name: 'pv',
                marker: {
                    symbol: 'square'
                },
                data: $(<?php echo $pv;?>).toArray()
    
            },{
                name: '成单数',
                marker: {
                    symbol: 'square'
                },
                data: $(<?php echo $suc_order_num;?>).toArray()
    
            },{
                name: '成单金额',
                marker: {
                    symbol: 'square'
                },
                data: $(<?php echo $suc_total_price;?>).toArray()
    
            }],
            credits: //右下角签名
				{enabled:1,
				text:"baidu",
				href:"http://baidu.com"
				}
        });
    });
    
});
function cha_xun(){
	$("form:first").attr('action','<?php echo WEB_ROOT;?>/manage/dailymedia/date_search_chart.php');
    $("form:first").submit();
}
		</script>

<div
    class="right-data"><!-- search  -->
    <div class="search-box">
        <form action="" method="post">
            <table>
                <tr>
                    <td>
                        <label>媒体名称：</label>
                        <select id="media_name" name="media_name" class="se_ect1">
                            <option></option>
                             <?php  echo ''.Utility::Option(get_platforms_for_search(),$media_name).''; ?>
                       </select>
                    </td>
                    <td>
                        <label>指标：</label>
                        <select id="index_name" name="index_name" class="se_ect1">
                            <option></option>
                             <?php  
                             global $option_index;
							echo Utility::Option ( $option_index, $index ); ?>
                       </select>
                    </td>
                    <td><label>显示</label> <input type="text" width="10px"
                        name="startdate" id="stratdate" onfocus="WdatePicker()"
                        value='<?php echo $filter_date_start;?>' /><label>之前的数据</label></td>
                    <td><a href="javascript:void(0);" onclick="cha_xun();"
                        class="search-btn">查询</a></td>
                </tr>
            </table>
        </form>
        
    </div>
    <!-- search end  --> <!-- table -->
    <div class="table-data-box">
   <div id="container" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
    </div>
<!-- table end -->
</div>

