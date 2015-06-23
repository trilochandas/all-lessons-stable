<?php 
// mysql

$config = array(
        'DB_HOST'            =>  'localhost',
        'DB_USERNAME'   =>  'root',
        'DB_PASSWORD'  =>  '123'
);

function connect($host = 'localhost', $username, $password, $db = '')
{
    $conn = mysql_connect($host, $username, $password);

    if (!empty($db)){
        mysql_select_db($db, $conn);
    }

    return $conn;
}

function query($query, $conn)
{
    mysql_query('SET NAMES utf8');
    $results = mysql_query($query, $conn);

    if ( $results ) {
        $rows = array();
        while($row = mysql_fetch_object($results)) {
            $rows[] = $row;
        }
        return $rows;
    }

    return false;
}

function output_select($select_name)
{
    echo "<select>";
    foreach ($select_name as $key) {
        echo  "<option value='{$key->city_index}'>" . $key->city . "</option>" ;
    }
    echo "</select>";
}

$conn = connect($config['DB_HOST'], $config['DB_USERNAME'], $config['DB_PASSWORD'], 'xaver');
$results = query('SELECT * FROM citys', $conn);

$my_text = 'Tri';
$try_put_content = query("INSERT INTO adverts (seller_name, phone) VALUES ('$my_text', '380663680615')", $conn);


if ( $results) {
    output_select($results);
} else {
    echo 'No rows return<br>';
}


?>

