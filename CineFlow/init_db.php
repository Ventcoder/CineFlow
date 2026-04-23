<?php
require 'db.php';

$sql = file_get_contents('cineflow_php/database.sql');

$result = pg_query($db, $sql);

if ($result) {
    echo "Successfully initialized database tables from database.sql.<br>";
} else {
    echo "Error creating table: " . pg_last_error($db);
}
?>
