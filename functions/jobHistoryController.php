<?php

$userId = $_SESSION['user_id'];
$db_controller = new DBController();
$sql = "SELECT * FROM job WHERE user_id =  '" . $userId . "' ORDER BY submission_date DESC LIMIT 200";
$result = $db_controller->runQuery($sql);
$records_per_page = 10;
if(!empty($result)){
    $rowcount = count( $result );

    $total_pages = ceil($rowcount/$records_per_page);


    // Retrieve the current page number
    if (isset($_GET["page"])) {
        $current_page = $_GET["page"];
    } else {
        $current_page = 1;
    }

    // Calculate the offset for the displayed page
    $offset = ($current_page - 1) * $records_per_page;
    $result = array_slice($result, $offset, $records_per_page);


    // Retrieve the job records for the user, sorted by submission date
    $query = "SELECT * FROM preset_global ORDER BY name";
    $global = $db_controller->runQuery($query);
    $query = "SELECT * FROM preset_user WHERE user_id='" . $userId . "' ORDER BY name";
    $user = $db_controller->runQuery($query);

    if(!empty($user)){
        $result_options=array_merge($user, $global);
    }else{
        $result_options=$global;
    }
    //var_dump($result_options);
}

?>