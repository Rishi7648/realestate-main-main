<?php
$host = 'localhost'; //it defines  the database server's hostname.
$dbname = 'realestatelogin'; //it is databse name
$username = 'root'; //it defines the username for the database connection
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    //PDO is a secure way to interact with databases in PHP
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //setAttribut Sets an attribute on the database connection to handle errors.
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>