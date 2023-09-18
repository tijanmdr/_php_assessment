<?php

$options = getopt("h:u:p:");

// Replace all the configuration with your local configuration
$host = $options['h'] ?? 'localhost';
$uname = $options['u'] ?? 'root';
$pwd = $options['p'] ?? '';
$db = 'test'; 

// connect to the database
try {
    $conn = new PDO("mysql:host=$host;dbname=$db", $uname, $pwd);
    
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connection successful";
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit(1);
}