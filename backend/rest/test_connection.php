<?php
require_once 'config.php'; 

$conn = Database::connect();

if ($conn) {
    echo "Uspješno spojeno na bazu pawpal_system!";
} else {
    echo "Neuspjela konekcija!";
}
?>
