<?php
require_once(dirname(dirname(dirname(__FILE__))). '/app.php');
$date = $arr_var['date'];
$date1_data = $arr_var['date1_data'];
$date2_data = $arr_var['date2_data'];
$date3_data = $arr_var['date3_data'];
$date4_data = $arr_var['date4_data'];
$date1 = $arr_var['date1'];
$date2 = $arr_var['date2'];
$date3 = $arr_var['date3'];
$date4 = $arr_var['date4'];
$index = $arr_var['index'];
$meiti = $arr_var['meiti'];
$datas_temp = $arr_var['datas_temp'];
$nowtime = $arr_var['nowtime'];
$all_meiti_data = $arr_var['all_meiti_data'];
$grade=$arr_var['grade'];
 global $option_monitor;
$filter_date_start = $arr_var['filter_date_start'];
if(strlen($meiti)){
	$meiti_name = $meiti."_";
}else{
	$meiti_name='整站_';
}

$ptitle = $meiti_name.$option_monitor[$index]."每半小时趋势";
$sub_title="显示查询时间以及前一天,上周同一天的对比结果";
$ytitle = $meiti_name.$option_monitor[$index]."监控";


?>
<script type="text/javascript" src="/static/jssrc/jquery-1.7.2.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	<?php if($grade==1){ ?>
	$(".media").css("display","none");
	<?php }?>
});
$(function () {
    var chart;
    $(document).ready(function() {
        chart = new Highcharts.Chart({
            chart: {
                renderTo: 'container',
                type: 'spline' //spline 平滑曲线 line 直线  pie饼图 areaspline 区域图 
            },
            title: {
                text: '<?php echo $ptitle;?>',
                algin:"left"
            },
            subtitle: {
                text: '<?php echo $sub_title;?>'
            },
            xAxis: {
            	labels:{
            	rotation:270,
            	y:30
            	},
            	
                categories: <?php echo $date;?>,
                minorTickInterval: 'yes'
            },
            yAxis: {
                title: {
                    text: '<?php echo $ytitle;?>'
                },
                min:0,
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
                        radius: 1,
                        lineColor: '#666666',
                        lineWidth: 1
                    }
                }
            },
            series: [{ 
                name: '<?php echo $date1;?>',
                marker: {
                    symbol: 'square'
                },
                data: $(<?php echo $date1_data;?>).toArray()
    
            },{
                name: '<?php echo $date2;?>',
                marker: {
                    symbol: 'square'
                },
                data: $(<?php echo $date2_data;?>).toArray()
    
            },{
                name: '<?php echo $date3;?>',
                marker: {
                    symbol: 'square'
                },
                data: $(<?php echo $date3_data;?>).toArray()
    
            },{
                name: '<?php echo $date4;?>',
                marker: {
                    symbol: 'square'
                },
                data: $(<?php echo $date4_data;?>).toArray()
    
            }],
            credits: //右下角签名
				{enabled:1,
				text:"55tuan",
				href:"http://www.55tuan.com"
				}
        });
    });
    
});
function cha_xun(){
	$("form:first").attr('action','<?php echo WEB_ROOT;?>/manage/operation/day_report/day_monitor_chart.php');
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
                        <label>媒体：</label>
                        <select id="index_name" name="meiti_name" class="se_ect1"> 
                        <option></option>
                             <?php  
                           
							echo Utility::Option ( get_platforms_for_search(), $meiti ); ?>
                       </select>
                    </td>
                    <td>
                        <label>指标：</label>
                        <select id="index_name" name="index_name" class="se_ect1">
                            <option></option>
                             <?php  
                            
							echo Utility::Option ( $option_monitor, $index ); ?>
                       </select>
                    </td>
                    <td><label>看</label> <input type="text" width="10px"
                        name="startdate" id="stratdate" onfocus="WdatePicker()"
                        value='<?php echo $filter_date_start;?>' /><label>的数据变化趋势</label></td>
                    <td><a href="javascript:void(0);" onclick="cha_xun();"
                        class="search-btn">查询</a></td>
                </tr>
            </table>
        </form>
        
    </div>
   <!-- search end  --> <!-- table min-width: 400px; -->
    <div class="table-data-box"> 

   <div id="container" style="min-width:800px; height: 400px; margin: 0 auto"></div>
  <div>*备注:00:00~30 表示0点0分到30分的数据;00:31~59表示0点31分到59分的数据*</div>

 <div class="media" ><?php 
 $len = count($all_meiti_data);
 $ttr = array();$ttd = array();
 
 foreach($all_meiti_data as $key=>$val){
	array_push($ttr, $key);
	array_push($ttd, $val);
}


if($len%7){
	$num=intval($len/7)+1;
}else{
	$num = $len/7;
}
 if(strlen($meiti)){ 
 $biaotou =  "<font color=red>".$meiti."</font>来源的<font color=red>".$option_monitor[$index]."</font>的总量是：";
 }else{
 $biaotou =  "<font color=red>整站</font>的<font color=red>".$option_monitor[$index]."</font>的总量";
 }
 echo "<table><tr><td colspan=\"3\">".$biaotou."</td></tr>";
 echo "<tr><td>日期</td><td>截止到".$nowtime['hm']."</td><td>整天总计</td></tr>";
 echo "<tr><td>".$date4."</td><td>".$datas_temp[3]['res']."</td><td>".array_sum(json_decode($date4_data))."</td></tr>";
 echo "<tr><td>".$date3."</td><td>".$datas_temp[2]['res']."</td><td>".array_sum(json_decode($date3_data))."</td></tr>"; 
  echo "<tr><td>".$date2."</td><td>".$datas_temp[1]['res']."</td><td>".array_sum(json_decode($date2_data))."</td></tr>";
  echo "<tr><td>".$date1."</td><td>".$datas_temp[0]['res']."</td><td>".array_sum(json_decode($date1_data))."</td></tr>";
 echo "</table>";
echo "<table>";
for($i=0;$i<$num;$i++){
	$a= $i*7;
	echo "<tr><td style=\"background-color:#CCCCCC;\">".$ttr[$a]."</td><td style=\"background-color:#CCCCCC;\">".$ttr[$a+1]."</td><td style=\"background-color:#CCCCCC;\">".$ttr[$a+2]."</td><td style=\"background-color:#CCCCCC;\">".$ttr[$a+3]."</td><td style=\"background-color:#CCCCCC;\">".$ttr[$a+4].
	"</td><td style=\"background-color:#CCCCCC;\">".$ttr[$a+5]."</td><td style=\"background-color:#CCCCCC;\">".$ttr[$a+6]."</td></tr>";
	
	echo "<tr><td>".$ttd[$a]."</td><td>".$ttd[$a+1]."</td><td>".$ttd[$a+2]."</td><td>".$ttd[$a+3]."</td><td>".$ttd[$a+4].
	"</td><td>".$ttd[$a+5]."</td><td>".$ttd[$a+6]."</td></tr>";
} 
echo "</table>";
 ?></div>
    </div>
<!-- table end -->
</div>

