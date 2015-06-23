<!-- <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script> -->
<script type="text/javascript" src="jquery.min.js"></script>
<?php
header('Content-Type: text/html; charset=utf-8');

ini_set('display_errors', '1');
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);

// connection to DB and including FirePHP
include ('config.php'); 

# include smarty
require('Smarty/libs/Smarty.class.php');
$smarty = new Smarty();

$smarty->compile_check = true;
$smarty->debugging = false;

$projectroot = $_SERVER['DOCUMENT_ROOT'];
$smarty_dir = $projectroot . '/smarty/' ;

$smarty->template_dir = $smarty_dir . 'templates';
$smarty->compile_dir = $smarty_dir . 'templates_c';
$smarty->cache_dir = $smarty_dir . 'cache';
$smarty->config_dir = $smarty_dir . 'configs';

// variables 
$categorys['selected'] = 'Выберите категорию';
$categorys['Транспорт'] = array(
    9 => 'Автомобили с пробегом',
    109 => 'Новые автомобили'
);
$categorys['Недвижимость'] = array(
    23 => 'Комнаты',
    24 => 'Квартиры',
);

$smarty->assign('error', '');

// output table with adverts
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

// output select_meta
$select_meta = $db->query('SELECT * FROM select_meta');
$citys = json_decode($select_meta[0]['options'], true);
$metro = json_decode($select_meta[1]['options'], true);
$smarty->assign('citys' ,$citys);
$smarty->assign('metro1' ,$metro);

// обработка формы. запись перезапись
if (isset($_POST['main_form_submit'])){
    // проверка на наличие знаков у параметров формы
    if (empty($_POST['title']) || empty($_POST['price']) ||  empty($_POST['seller_name']) || empty($_POST['phone'])) {
        $smarty->assign('error', 'Введите все данные');
        } else {
            // перезапись объявления
            if (isset($_GET['id'])){
                $id = $_GET['id'];
                // var_dump($_POST);
                $res = $_POST;
                array_pop($res);
                if (isset($res['allow_mails'])){
                    $res['allow_mails'] = 1;
                } else{
                    $res['allow_mails'] = 0;
                }
                // $res['allow_mails'] = isset($res['$allow_mails']) ? 1 : 0;
                // var_dump($res);
                $db->query("UPDATE adverts SET ?a WHERE id=?", $res, $id);
                header('Location:' . $_SERVER['PHP_SELF']);
           } else {
            //  добавление объявления
                array_pop($_POST);
                $db->query('INSERT INTO adverts SET ?a', $_POST);
        }
    }
}



// заполнение формы
if (isset($_GET['id'])){
    $id = (int) $_GET['id'];
    $data = $db->query("SELECT * FROM adverts WHERE id=?", $id);
    foreach ($data[0] as $key => $value) { 
        $$key = $value;
}
    $allow_mails = ( $allow_mails == 1) ? 'checked' : '';
    // пустые переменные для пустой формы
    } else {
        $title='';
        $price='';
        $seller_name='';
        $description='';
        $phone='';
        $email='';
        $allow_mails='';
        $private='';
        $city='';
        $metro1='';
        $category_id='';
}
$smarty->assign('private', $private);
$smarty->assign('seller_name', $seller_name);
$smarty->assign('email', $email);
$smarty->assign('allow_mails', $allow_mails);
$smarty->assign('phone', $phone);
$smarty->assign('city', $city);
$smarty->assign('metro', $metro);
$smarty->assign('title', $title);
$smarty->assign('description', $description);
$smarty->assign('price', $price);

    


// вывод всех объявлений
$advert_output_table = $db->query('SELECT * FROM adverts');
$smarty->assign('advert_output_table', $advert_output_table);



$smarty->display('lesson15.tpl');


?>
<script>
$(document).ready(function(){

    function showResponse(response){
        $('#adverts>tbody').append(response.advert);
        
         if(response.status=='success'){
                    $('.ajax').html(response);
                    console.log(response);
                } else if(response=='error'){
                    $('.ajax').html(response);
                    console.log(response);
                }
    }; // end showResponse()
    
     var options = { 
        target:        '#ajax-info',   // target element(s) to be updated with server response 
        //beforeSubmit:  showRequest,  // pre-submit callback 
        success:       showResponse,  // post-submit callback 
 
        // other available options: 
        url:       'ajax-functions.php?action=insert',         // override for form's 'action' attribute 
        //type:      type        // 'get' or 'post', override for form's 'method' attribute 
        // dataType:  'xml',        // 'xml', 'script', or 'json' (expected server response type) 
        clearForm: true,     // clear all form fields after successful submit 
        resetForm: true      // reset the form after successful submit 
 
        // $.ajax options can be used here too, for example: 
        //timeout:   3000 
    }; // edn var option

    // bind form using 'ajaxForm' 
    $('#ajax-form').ajaxForm(options); 

    $('a.delete').on('click', function(evt) {
        evt.preventDefault();
        var del = $(this).attr('data-del');
        var tr = $(this).closest('tr');
        var title = $('.delete')[0].closest('tr').children[0].children[0].innerHTML;
        console.log(title);
        $('.ajax').load('ajax-functions.php?action=delete&del='+del, function(){
            tr.fadeOut('slow', function(){tr.remove()});
        }); // end load()
    }); // end a.delete on click
}); //end ready

</script>