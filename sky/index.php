<?php

require "../bootstrap.php";

$weather = ( new Weather( $configs ) )
->set()
->current("sky")
->get();

header('Content-type: application/json');
echo json_encode($weather);

?>
