<?php
require_once (dirname ( dirname ( __FILE__ ) ) . '/app.php');
need_manager ();
$dept_id = trim ( $_GET['dept_id'] );
$resource_id = trim ( $_GET['resource_id'] );
$grade = trim ( $_GET['grade'] );
$user_id =trim ( $_GET['user_id'] ); 

global  $deparment_user_grades;

echo  Utility::Option ( $deparment_user_grades[$resource_id][$dept_id] );
	if(is_numeric($user_id)){
		if(!is_numeric($grade)){
			$user_auth = Users::GetAuthority($user_id);
			if ($user_auth) {
				foreach ( $user_auth as $permission ) {
					if ($permission ['grade'] !== null && $permission ['grade'] !== '') {
						$grade = $permission ['grade'];
						break;
					}
				}
			}
		}
		echo '<script type="text/javascript">';
		echo '$("#grade").val("'.$grade.'");';
		echo '</script>';
	}


exit ();
?>