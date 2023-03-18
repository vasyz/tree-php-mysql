<?php
require_once('db.php');

$db =  new DB();

$results = $db->getAllNodes();


header('Content-Type: application/json');

echo json_encode($results);


