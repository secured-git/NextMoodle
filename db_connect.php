<?php

$host = 'localhost';
$dbname = '#####';
$user = '####'; 
$pass = '#####'; 

# Always Create a .env file for storing sensitive information


try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>

