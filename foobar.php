<?php

$options = getopt("h:u:p:", ["file", "create_table", "dry_run", "help"]);

if(isset($options['help'])) {
    echo "Commands: php foobar.php -u username -p password -h hostname --file csv_filepath [--create_table] [--dry_run]\n";
    echo "--file: Specify the CSV file name\n";
    exit(0);
}

// if (!isset($options['u']) || !isset($options['p'])) {
//     echo "MySQL username and password is required. For more help: php foobar.php --help";
//     exit(1);
// }

// Replace all the configuration with your local configuration
$host = $options['h'] ?? 'localhost';
$uname = $options['u'] ?? 'root';
$pwd = $options['p'] ?? '';
$db = 'test'; 

// connect to the database
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $uname, $pwd);
    
    // set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connection successful";
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit(1);
}

if (isset($options['create_table'])) {
    try {
        $create_table = "CREATE TABLE IF NOT EXISTS users(
            id INT AUTO_INCREMENT PRIMARY KEY, 
            name VARCHAR(255) NOT NULL, 
            surname VARCHAR(255) NOT NULL, 
            email VARCHAR(255) NOT NULL UNIQUE 
        )";
        $pdo->exec($create_table);
        echo("Table 'users' created!");
    } catch(PDOException $e) {
        echo("Error: ".$e->getMessage());
        exit(1);
    } 
    exit(0);
} else if (!isset($options['file'])) {
    echo "Please specify a CSV file path with --file option. For more help: php foobar.php --help";
    exit(1);
}

if (($handle = fopen($options['file'], 'r')) !== false) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $name = ucfirst(strtolower(trim($data[0])));
        $surname = ucfirst(strtolower(trim($data[1])));
        $email = strtolower(trim($data[2]));

        try {
            $insert = "insert into users (name, surname, email) values (:n, :s, :e)";
            $statement = $pdo->prepare($insert);
            $statement->bindParam(':n', name);
            $statement->bindParam(':s', surname);
            $statement->bindParam(':e', email);
            $statement->execute();
        } catch (PDOException $e) {
            echo "Error Message: ".$e->getMessage();
        }
        fclose($handle);
    }
} else {
    echo $options['file']. ' could not be opened. Please try again!';
    exit(1);
}