<?php  

require_once('Facade.php');

use Routing\Facade as Route;

$specific_en = Route::url('specific_page');
$specific_de = Route::url('specific_page','de');
$specific_fr = Route::url('specific_page','fr');