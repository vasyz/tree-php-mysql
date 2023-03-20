<?php
require_once('db.php');
require_once('response.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $node_id = intval($_POST['node_id']);

    $db =  new DB();

    response($db->deleteNode($node_id));
}



