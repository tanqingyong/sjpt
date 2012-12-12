<?php
require_once(dirname(dirname(dirname(__FILE__))). '/app.php');
$temp = $arr_var['temp'];

?>


<div
    class="right-data"><!-- search  -->
    <div class="search-box">

            <table>
                <tr><td><label>各项指标定义</label></td>

                </tr>
                 <tr><td>*特别说明：不同部门定义相同指标可能有根据自己需求设置的差异,请根据实际情况参考*
                </td></tr>
            </table>

        
    </div>
    <!-- search end  --> <!-- table min-width: 400px; -->
    <div > 
   <div id="container" style="min-width: 100px; height: 10px; margin: 0 auto"><?php echo $temp;?></div>
    </div>
<!-- table end -->
</div>

