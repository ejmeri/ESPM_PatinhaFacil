<?php



// echo $_SERVER['HTTP_HOST'];
session_start();

date_default_timezone_set('America/Sao_Paulo');



// if (isset($_POST)){
//     foreach ($_POST as $in => $va){
//         $_POST[$in] = str_replace("'", "\\'", $va);
//         echo $_POST[$in];
//     }
// }

define('APP_ROOT', 'http://' . $_SERVER['HTTP_HOST']);
define('NEW_FILE', '22-06-2017:22:33');

require_once 'helper/autoloader.php';

use lib\System;

$System = new System();
$System->Run();
