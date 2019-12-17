<?php

require "../bootstrap.php";

$weather = ( new Weather( $configs ) )
->cardinal()
->get();

header('Content-type: application/json');
echo json_encode($weather);

?>
