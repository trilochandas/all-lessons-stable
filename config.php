<?php 
# include dbsimple
$project_root = $_SERVER['DOCUMENT_ROOT'];
require_once $project_root."/dbsimple/config.php";
require_once $project_root."/dbsimple/DbSimple/Generic.php";

# include FirePHP
require_once $project_root . ('/FirePHPCore/FirePHP.class.php');
$firephp = FirePHP::getInstance(true); 
$firephp->setEnabled(true); 

// config
$config = array(
        'DB_HOST'            =>  'localhost',
        'DB_USERNAME'   =>  'root',
        'DB_PASSWORD'  =>  '123'
);

// connect
$db = DbSimple_Generic::connect('mysqli://' . $config['DB_USERNAME'] . ':' . $config['DB_PASSWORD'] . '@' . $config['DB_HOST'] . '/xaver');
// setErrorHandler
$db->setErrorHandler('databaseErrorHandler');

// databaseErrorHandler
function databaseErrorHandler($message, $info)
{
    // Если использовалась @, ничего не делать.
    if (!error_reporting()) return;
    // Выводим подробную информацию об ошибке.
    echo "SQL Error: $message<br><pre>"; 
    print_r($info);
    echo "</pre>";
    exit();
}

$db->setLogger('myLogger');
function myLogger($db, $sql, $caller)
{
    global $firephp;
    global $advert_output_table;
    $firephp->group("at ".@$caller['file'].' line '.@$caller['line']);
    $firephp->log($sql);
    $firephp->groupEnd();
    $firephp->table('all adverts', $advert_output_table);
}