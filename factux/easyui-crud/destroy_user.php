<?php

$id = intval($_REQUEST['id']);

include '../include/config/common.php';

$sql = "update " . $tblpref ."article set actif='non' where num=$id";
@mysql_query($sql);

echo json_encode(array('success'=>true));
?>
