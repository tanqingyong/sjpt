<?php 
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/app.php');
need_login();
need_page(); 
echo template ( 'manage_edm_import_cost');
?>