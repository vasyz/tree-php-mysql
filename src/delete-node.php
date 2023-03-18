<?php
require_once('db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $node_id = $_POST['node_id'];
    $db =  new DB();
    $db->deleteNode($node_id);


}



header('Content-Type: application/json');
echo json_encode(["status" => true]);


