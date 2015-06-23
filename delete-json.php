<?php 
// connection to db
include ('config.php');

//  удаление новости
    $del = (int) $_GET['del'];
    if ( $db->query("DELETE FROM adverts WHERE id=?", $del ) ) {
	    $result['status'] = 'success';	
	    $result['message'] = 'Объявление №' . $del . ' успешно удалено.';
    } else {
	    $result['status'] = 'error';	
	    $result['message'] = 'Простите. Произошла ошибка. Пожалуйста, попробуйте ещё раз.';	
    }
    echo json_encode($result);
