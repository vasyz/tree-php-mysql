<?php
require_once('db.php');
require_once('response.php');

$db =  new DB();
response($db->getAllNodes());



