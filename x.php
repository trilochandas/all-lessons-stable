<?php
// mysql

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
    $results = mysql_query($query, $conn);

    if ( $results ) {
        $rows - array();
        while($row = mysql_fetch_object($results)) {
            $rows[] = $row;
        }
        return $rows;
    }

    return false;
}

$conn = connect($config['DB_HOST'], $config['DB_USERNAME'], $config['DB_PASSWORD'], 'Citys');
$results = query('SELECT * FROM Citys', $conn);
$smarty->assign('results' ,$results);