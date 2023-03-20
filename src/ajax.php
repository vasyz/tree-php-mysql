<?php
require_once('db.php');
require_once('response.php');


$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($action) {
    case "create_node":

        $title = htmlspecialchars($_POST['title']) ;

        $parent_id = $_POST['parent_id']  ? intval($_POST['parent_id'])  : null;

        $db =  new DB();

        response($db->createNode($parent_id,$title));
        break;
    case "get_tree":

        $db =  new DB();

        response($db->getAllNodes());

        break;
    case "delete_node":

        $node_id = intval($_POST['node_id']);

        $db =  new DB();

        response($db->deleteNode($node_id));

        break;
    default:

        response(false);

        break;
}
