<?php
require 'db.php';
$res = pg_query($db, 'SELECT * FROM users');
echo "<pre>";
while($r = pg_fetch_assoc($res)) {
    print_r($r);
}
echo "</pre>";
?>
