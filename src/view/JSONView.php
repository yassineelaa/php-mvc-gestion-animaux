<?php

class JSONView
{
    public function renderJSON($data)
    {
        header('Content-Type: application/json');
        echo json_encode($data, JSON_PRETTY_PRINT);
    }
}
?>
