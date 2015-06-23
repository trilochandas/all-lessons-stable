<?php 
ini_set('display_errors', '1');
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);

## Подключение к БД.
$project_root = $_SERVER['DOCUMENT_ROOT'];
require_once $project_root."/dbsimple/config.php";
require_once $project_root."/dbsimple/DbSimple/Generic.php";

// Подключаемся к БД.
$db = DbSimple_Generic::connect('mysqli://root:123@localhost/xaver');

// Устанавливаем обработчик ошибок.
$db->setErrorHandler('databaseErrorHandler');

// Код обработчика ошибок SQL.
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
$query = "SELECT * FROM adverts";
// $advert_output_table = $db->select($query);

function all_query($query){
	global $db;
	$result = $db->select($query);
	if (!$result){
		echo 'query error';
	} else
	return $result;
}

$advert_output_table = all_query('SELECT * FROM adverts');

print_r($advert_output_table);
var_dump($advert_output_table);

function output_adverts_table(){
    $out_text =  '<table>';
    $out_text .= '<tr>';
    $out_text .= '<td>Название объявления</td>';
    $out_text .= '<td>Цена</td>';
    $out_text .= '<td>Имя продавца</td>';
    $out_text .= '<td>Удалить</td>';
    $out_text .= '</tr>';

    global $advert_output_table;

    foreach ($advert_output_table as $key => $value) {
       $out_text .= "<tr>";
       $out_text .= "<td><a href='{$_SERVER['SCRIPT_NAME']}?id={$value['id']}'>" . $value['title'] . "</a></td>";
       $out_text .= "<td>{$value['price']}</td>";
       $out_text .= "<td>{$value['seller_name']}</td>";
       $out_text .= "<td><a href='{$_SERVER["SCRIPT_NAME"]}?del={$value['id']}'>Удалить</a></td>";
       $out_text .= "</tr>";
    }

    $out_text .= "</table>";

    echo $out_text;
}

output_adverts_table();

?>
<style>
table{
    border: 1px;
    border-collapse: collapse;
    text-align: center;
}
td{
    border: 1px solid;
    padding: 10px;
}
form{width: 500px;}
input:not([type="radio"]), select, textarea {
    float: right;
}
input{
    margin: 3px 0;
}
.form-row {
    margin: 10px 0;
    clear: both;
}

</style>