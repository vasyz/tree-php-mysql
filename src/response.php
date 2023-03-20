<?php

function response ($results)
{
    header('Content-Type: application/json');


    if (is_array($results))
    {
        echo json_encode($results);
    }
    else
    {
        echo json_encode(["status" => false]);
    }
}
