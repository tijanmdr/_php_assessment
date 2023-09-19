<?php

$options = getopt("h:u:p:", ["file:", "create_table", "dry_run", "help"]);

// trigger if there is --help arguement 
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
$db = 'test'; // replace your database name

// connect to the database
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $uname, $pwd);
    
    // set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connection successful\n";
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage()."\n";
    exit(1);
}

// trigger if there is --create_table arguement 
if (isset($options['create_table'])) {
    try {
        $create_table = "CREATE TABLE IF NOT EXISTS users(
            id INT AUTO_INCREMENT PRIMARY KEY, 
            name VARCHAR(255) NOT NULL, 
            surname VARCHAR(255) NOT NULL, 
            email VARCHAR(255) NOT NULL UNIQUE 
        )";
        $pdo->exec($create_table);
        echo("Table 'users' created!\n");
    } catch(PDOException $e) {
        echo("Error: ".$e->getMessage()."\n");
        exit(1);
    } 
    exit(0);
} else if (!isset($options['file'])) { // Don't need to trigger unless there is no --create_table arguement
    echo "Please specify a CSV file path with --file option. For more help: php foobar.php --help\n";
    exit(1);
}

// open and read the csv file
if (($handle = fopen($options['file'], 'r')) !== false) {
    $errors = 0; // to count the sql errors 
    while (($data = fgetcsv($handle, 1000, ",")) !== false) {
        $name = ucfirst(strtolower(trim($data[0])));
        $surname = ucfirst(strtolower(trim($data[1])));
        $email = strtolower(trim($data[2]));

        // to validate the email address
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            if ($email === 'email') {continue;}
            echo "Invalid email found: $email\n";
            $errors++;
            continue;
        }
        try {
            // insert data to the table
            $insert = "INSERT INTO users 
                (name, surname, email) VALUES (:n, :s, :e)
            ";
            $statement = $pdo->prepare($insert);
            $statement->bindParam(':n', $name);
            $statement->bindParam(':s', $surname);
            $statement->bindParam(':e', $email);
            $statement->execute();
        } catch (PDOException $e) {
            echo "Error Message: ".$e->getMessage()."\n";
            $errors++;
        }
        
        }
        fclose($handle);
        
        if ($errors > 0) {
            echo "Successfully inserted data with $errors errors.\n";
        } else {
            echo "Successfully inserted all the data.\n";
        }
} else {
    echo $options['file']. ' could not be opened. Please try again!\n';
    exit(1);
}