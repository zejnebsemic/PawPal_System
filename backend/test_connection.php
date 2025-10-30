<?php
require_once(__DIR__ . "/config.php");

$connection = Database::connect();

if ($connection) {
    echo "Connection successful!";
} else {
    echo "Connection failed!";
}
?>
