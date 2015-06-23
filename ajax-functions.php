<?php 
// connection to db
include ('config.php');

switch ($_GET['action']) {
	//  удаление новости
	case 'delete':
    $del = (int) $_GET['del'];
    if ( $db->query("DELETE FROM adverts WHERE id=?", $del ) ) {
	    echo 'Должно быть удалено объявление с номером ' . $del ;	
    } else {
    	echo 'Простите. Произошла ошибка. Повторите ещё раз.';
    }
		break;
	case 'insert':
		array_pop($_POST);
		$id=$db->query('INSERT INTO adverts(?#) VALUES(?a)',  array_keys($_POST), array_values($_POST));
		if ($id) {
			// $q_adv=$db->selectRow("SELECT * FROM adverts WHERE id=?d", $id)
	    echo 'Должно быть добавленно объявление с номером ' . $id ;	
		} else {
    	echo 'Простите. Произошла ошибка. Повторите ещё раз.';
		}

		break;
	
	default:
		break;
}

