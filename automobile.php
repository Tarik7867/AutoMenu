<?

function db_connection($query){
    $dbc = mysqli_connect("localhost", "root", "", "ShopAuto") or fail("Cloud not connected to database");
    return mysqli_query($dbc, $query);

}

function success($message){
    die(json_encode(array('status'=> 'success', 'message' => $message)));
} 

function fail($message){
    die(json_encode(array('status'=> 'fail', 'message' => $message)));
}


if(isset($_GET['mode']) && !empty($_GET['mode']) && $_GET['mode'] == "out_marka"){
    $query = "select id, name from marka";
    $result = db_connection($query);
    if($result){
        $marka = array();
        while($next = mysqli_fetch_array($result, MYSQLI_ASSOC)){
            $marka[] = array(
                "id" => $next['id'],
                "name" => $next['name']
            );

        }
        echo json_encode(array('marka' => $marka));
        exit;
    }
}

if(isset($_POST['model_auto'], $_POST['select_marka'], $_POST['price_auto'], $_POST['odometr'], $_POST['description'], $_POST['count'])){
   // $query = "insert into autophoto (id_marka, model, odometr, full_description, count, price) values ('{$_POST['select_marka']}', '{$_POST['model_auto']}', '{$_POST['odometr']}', '{$_POST['description']}', '{$_POST['count']}' ,'{$_POST['price_auto']}' )";
   // $result = db_connection($query);

  /*  if($result){
        success("Все успішно додано!");
        exit;
    } else {
        fail("Не додано!");
    } */


/*
    коли файл попадає на сервер кожного елемента інпут <input type = "file" name = "photo" > створюється суперглобальний масив $_FILES який має назву ['photo'] таке ж як і елемент інпута 
    масив має 5 станів
    1. $_FILES['photo']['size'] - повератє розмір загруженого файлу на сервер
    2. $_FILES['photo']['type'] - повертає тип загружаємого файлу наприклад: image/jpg, text/doc
    3. $_FILES[photo']['tmp_name'] - повертає часове розположення і часову назву файлу на сервері
    4. $_FILES['photo']['name'] - повертає назву файлу як він називався на пк клієнта
    5. $_FILES['photo']['error'] - повертає код помилки якщо файл на сервер загружений не успішно або повертає код ошибки 0 якщо файл успішно загружений
    */

if($_FILES['file_inp']['error'] == 0){ /// перевіряємо що файл загружений без помилок
    $fileName_TMP = $_FILES['file_inp']['tmp_name']; /// отримуємо часове розположення та назву
    $fileName = time().$_FILES['file_inp']['name']; /// створюємо назву файлу функцію тайм яка повертає мілісекунди даного часу
    move_uploaded_file($fileName_TMP, "images/$fileName"); /// функція має параметри: 1. де файл временно лежить і як він називається 2. куда файл положить і як назвать 
    /// запрос має інфу про файл 
    $query = "insert into autophoto (id_marka, model, odometr, full_description, count, photo, price) values ('{$_POST['select_marka']}', '{$_POST['model_auto']}', '{$_POST['odometr']}', '{$_POST['description']}', '{$_POST['count']}' , '$fileName', '{$_POST['price_auto']}')";

} else { //// запрос без назви файлу
    $query = "insert into autophoto (id_marka, model, odometr, full_description, count, price) values  ('{$_POST['select_marka']}', '{$_POST['model_auto']}', '{$_POST['odometr']}', '{$_POST['description']}', '{$_POST['count']}' ,'{$_POST['price_auto']}')";
}
///fail($query);
    $result = db_connection($query);
    if($result){
        success("Все відправлено");
        exit;
    } else {
        fail("не додано");
    }

}


if(isset($_GET['mode']) && !empty($_GET['mode']) && $_GET['mode'] == "out_cars"){
    $query = "select id, id_marka, model, odometr, full_description, count, photo, price from autophoto";
    $result = db_connection($query);
    if($result){
        $automobiles = array();
        while($next = mysqli_fetch_array($result, MYSQLI_ASSOC)){
            if(empty($next['photo'])){
                $next['photo'] = "nophoto.jpg";
            }
            $automobiles[] = array(
                "id" => $next['id'],
                "id_marka" => $next['id_marka'],
                "model" => $next['model'],
                "odometr" => $next['odometr'],
                "full_description" => $next['full_description'],
                "count" => $next['count'],
                "photo" => $next['photo'],
                "price" => $next['price']
            );
        }
        echo json_encode(array('automobiles' => $automobiles));
        exit;
    }
}

if (isset($_GET['mode']) && $_GET['mode'] == "del_car" && !empty($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = "DELETE FROM autophoto WHERE id = $id";
    $result = db_connection($query);

    if ($result) {
        success("Авто успішно видалено!");
    } else {
        fail("Авто не видалено");
    }
}

    if(isset($_GET['mode'], $_GET['id']) && $_GET['mode'] == "edit_cars" && !empty($_GET['id'])){
        $id = intval($_GET['id']);
        $query = "select id, model, id_marka, odometr, full_description, count, photo, price from autophoto where id= $id";
        $result = db_connection($query);
        $next = mysqli_fetch_array($result, MYSQLI_ASSOC);
        if(empty($next['photo'])){ //// фнкц емпті повертає тру якщо масив некст фото пустий
            $next['photo'] = "nophoto.jpg"; ///// тоді в нього зберігаєм чергове фото коли зообр немає
        }
        $car = array(
            "id" => $next['id'],
            "model" => $next['model'],
            "id_marka" => $next['id_marka'],
            "odometr" => $next['odometr'],
            "full_description" => $next['full_description'],
            "count" => $next['count'],
            "photo" => $next['photo'],
            "price" => $next['price']
        );
        if(count($car) > 0){
            echo json_encode(array("car" => $car));
        } else {
            fail("fail edit cars");
        }
        
    }

   /* if(isset($_['mode'], $_GET['id_marka'], $_GET['model'], $_GET['odometr'], $_GET['full_description'], $_GET['count'], $_GET['photo'], $_GET['price']) && !empty($_GET['id_marka']) && !empty($_GET['model']) && !empty($_GET['odometr']) && !empty($_GET['full_description']) && !empty($_GET['count']) && !empty($_GET['photo']) && !empty($_GET['price']) && $_GET['mode'] == "result_edit"){
        $carId = $_GET['id'];
        $id_marka = $_GET['id_marka'];
        $model = $_GET['model'];
        $odometr = $_GET['odometr'];
        $full_description = $_GET['full_description'];
        $count = $_GET['count'];
        $photo = $_GET['photo'];
        $price = $_GET['price'];

        $query = "update autophoto set id_marka='$id_marka', model='$model', odometr='$odometr', full_description='$full_description', count='$count', photo='$photo', price='$price' where id ='$carId'  ";
        $result = db_connection($query);
        if($result == true){
            success("Дані оновлено");
        } else {
            fail("Дані не оновлено!");
        }
    }

    if (isset($_FILES['file_inp_img']) && $_FILES['file_inp_img']['error'] == 0) {
    $fileName_TMP = $_FILES['file_inp']['tmp_name']; 
    $fileName = time().$_FILES['file_inp']['name']; 
    move_uploaded_file($fileName_TMP, "images/$fileName"); 
        $query = "update autophoto set id_marka = '$id_marka', model = '$model', odometr = '$odometr', full_description = '$full_description', count = '$count', photo = '$fileName', price = '$price' WHERE id = '$carId'";
    } else {
        $query = "update autophoto set id_marka = '$id_marka', model = '$model', odometr = '$odometr', full_description = '$full_description', count = '$count', price = '$price' WHERE id = '$carId'";
    }


    $result = db_connection($query);
    if ($result) {
        success("Фото оновлено");
    } else {
        fail("фото не оновлено");
    }
    */
    if(isset( $_POST['change_car_id'], $_POST['change_select_model'], $_POST['change_model'], $_POST['change_odometr'], $_POST['change_description'], $_POST['change_count'], $_POST['change_price']) && !empty($_POST['change_select_model']) && !empty($_POST['change_car_id']) && !empty($_POST['change_model']) && !empty($_POST['change_odometr']) && !empty($_POST['change_description']) && !empty($_POST['change_count']) && !empty($_POST['change_price'])){
        $car_id = $_POST['change_car_id'];
       // fail($car_id);
        $id_marka = $_POST['change_select_model'];
        $model = $_POST['change_model'];
        $odometr = $_POST['change_odometr'];
        $full_description = $_POST['change_description'];
        $count = $_POST['change_count'];
        $price = $_POST['change_price'];
        if (isset($_FILES['file_inp_img']) && $_FILES['file_inp_img']['error'] == 0) {
            $fileName_TMP = $_FILES['file_inp_img']['tmp_name']; 
            $fileName = time().$_FILES['file_inp_img']['name']; 
            move_uploaded_file($fileName_TMP, "images/$fileName"); 
                $query = "update autophoto set id_marka = '$id_marka', model = '$model', odometr = '$odometr', full_description = '$full_description', count = '$count', photo = '$fileName', price = '$price' WHERE id = '$car_id'";
            } else {
                $query = "update autophoto set id_marka = '$id_marka', model = '$model', odometr = '$odometr', full_description = '$full_description', count = '$count', price = '$price' WHERE id = '$car_id'";
            }   
            
            //fail($query);
            $result = db_connection($query);
            if ($result) {
                success("Запис відредагований");
            } else {
                fail("Запис не відредагований");
            }
    }

    /////////////////////// видалення фізичного

?>