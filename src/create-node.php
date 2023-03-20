<?php
require_once('db.php');
require_once('response.php');


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $title = htmlspecialchars($_POST['title']) ;

    $parent_id = $_POST['parent_id']  ? intval($_POST['parent_id'])  : null;

    $db =  new DB();

    response($db->createNode($parent_id,$title));

}




