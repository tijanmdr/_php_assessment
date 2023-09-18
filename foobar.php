<?php

$options = getopt("h:u:p:", ["file", "create_table", "dry_run", "help"]);

if(isset($options['help'])) {
    echo "Commands: php foobar.php -u username -p password -h hostname --file csv_filepath [--create_table] [--dry_run]\n";
    echo "--file: Specify the CSV file name\n";
    exit(0);
}

if (!isset($options['file'])) {
    echo "Please specify a CSV file path with --file option";
    exit(1);
}

if (isset($options['create_table'])) {
    try {
        $create_table = "create table if not exists users(
            id int auto_increment primary key, 
            name varchar(255) not null, 
            surname varchar(255) not null, 
            email varchar(255) not null unique, 
        )";
        $pdo->exec($create_table);
        echo("Table 'users' created!");
    } catch(PDOException $e) {
        echo("Error: ".$e->getMessage());
        exit(1);
    } 
    exit(0);
}

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