<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once('Facade.php');

use Routing\Facade as Route;

Route::locales(['de','fr']);

// Sets route for general page for all locales ('/my_general_page', '/de/my_general_page', '/fr/my_general_page')
Route::set('my_general_page','controller_name/general_function','general_page');

// Sets one default route and one specific route for German, using the same alias ('/my_specific_page','/de/my_specific_page','fr/my_specific_page')
Route::set('my_specific_page','controller_name/specific_function','specific_page');
Route::set('my_specific_page_de','controller_name/specific_function','specific_page','de');

$route = Route::generate();