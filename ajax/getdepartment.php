<?php
require_once (dirname ( dirname ( __FILE__ ) ) . '/app.php');
need_manager ();
$resource_id = trim ( $_GET['resource_id'] );
$user_id =trim ( $_GET['user_id'] ); 
$dept_id = trim ( $_GET['dept_id'] ); 

	echo Utility::Option (get_dept_option_by_resuorce($resource_id));
	if(is_numeric($user_id)){
		if(!is_numeric($dept_id)){
			$user_auth = Users::GetAuthority($user_id);
			if ($user_auth) {
				foreach ( $user_auth as $permission ) {
					if ($permission ['department_id'] !== null && $permission ['department_id'] !== '') {
						$dept = $permission ['department_id'];
						break;
					}
				}
			}
		}else{
			$dept = $dept_id;
		}
		echo '<script type="text/javascript">';
		echo '$("#dept").val("'.$dept.'");';
		echo '</script>';
	}
exit ();
?>