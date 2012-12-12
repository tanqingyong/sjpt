<?php
require_once (dirname ( dirname ( __FILE__ ) ) . '/app.php');
need_manager ();
$dept_id = trim ( $_GET['dept_id'] );

echo get_dept_menu_tree ($dept_id);

error_log(get_dept_menu_tree ($dept_id));

exit ();
?>