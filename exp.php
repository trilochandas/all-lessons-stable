<?php
$a= array();
$a[]= "Pervii";
$a[]= "Vtoroi";
$b= array();
$b[]= "bElem1";
$b[]= "bElem2";
$a[]= $b;
print_r($a);
print_r($b);

?>

Было так:

<select title="Выберите Ваш город" name="location_id" id="region" class="form-input-select">
            <option selected value="">-- Выберите город --</option>
            <option class="opt-group" disabled="disabled">-- Города --</option>
            <option  <?php if ($location_id == 641780 ) echo 'selected'; ?> value="641780">Новосибирск</option>   
            <option  <?php if ($location_id == 641490 ) echo 'selected'; ?> value="641490">Барабинск</option>   
            <option  <?php if ($location_id == 641510 ) echo 'selected'; ?> value="641510">Бердск</option>
    </select> 

 Сделал так:
   <?php
    $citys = array();
    $citys = array(
        1 => array ( 'city' => 'Новосибирск', 'indx' => '641780' ),
        2 => array( 'city' => 'Барабинск', 'indx' => '641490' ),
        3 => array( 'city' => 'Бердск', 'indx' => '641510' )
    );
    echo '<select name="location_id" >';
    foreach ($citys as $numb => $one_city) {
            echo '<option' if ($location_id == 641780 ) echo 'selected'; '  value="' . $one_city['indx'] . '">' . $one_city['city'] . '</option>';
    }
    echo '</select>';
    ?>

    Не получается добавить вот эту проверку:
    <?php if ($location_id == 641780 ) echo 'selected'; ?>