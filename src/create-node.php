<?php
require_once('db.php');


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $title = $_POST['title'];
    $parent_id = $_POST['parent_id']  ? $_POST['parent_id'] : null;
    $db =  new DB();
    $db->createNode($parent_id,$title);

}

header('Content-Type: application/json');
echo json_encode(["status" => true]);


