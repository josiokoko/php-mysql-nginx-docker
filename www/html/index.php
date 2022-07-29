<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nginx Server</title>
</head>
<body>
   <div style="box-sizing: border-box; width: 90%; margin: 0 auto;">
    <h1 style="text-align:center;">Hello nginx server!</h1>  
    <h2 style="text-align:center;"><?php echo "Nginx proxy server is working?" ?></h2>
    
    
    <div style="margin-top: 50px; text-align:center;">
        <h3>Container hostname and localhost ip address</h3>
        <p>
            <?php 
                $hostname = gethostname();
                echo "Container hostname is $hostname"; 
            ?>
        </p>
        <p>
            <?php 
                $localhostname = gethostbyname($hostname);
                echo "IP address is $localhostname";
            ?>
        </p>
    </div>
    
  
    <h3 style="margin-top: 50px;">Database configuration and data display</h3>
    <div>
        <?php  
                    $database = "mydatabase";
                    $user = "jideuser";
                    $password = "password";
                    $host = "mysqldb";


            echo "<table style='border: solid 1px black;'>";
            echo "<tr><th>Id</th><th>Firstname</th><th>Lastname</th></tr>";

                    class TableRows extends RecursiveIteratorIterator {
                        function __construct($it) {
                            parent::__construct($it, self::LEAVES_ONLY);
                        }

                        function current() {
                            return "<td style='width:150px;border:1px solid black;'>" . parent::current(). "</td>";
                        }

                        function beginChildren() {
                            echo "<tr>";
                        }

                        function endChildren() {
                            echo "</tr>" . "\n";
                        }
                    } 

                    try{
                        $connection = new PDO("mysql:host={$host};dbname={$database};charset=utf8", $user, $password);
                        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        
                        // $sql = "CREATE TABLE employees (
                        //     id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                        //     firstname VARCHAR(30) NOT NULL,
                        //     lastname VARCHAR(30) NOT NULL,
                        //     email VARCHAR(50),
                        //     reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                        //     )";
                        
                        // $connection->exec($sql);
                        // echo "<p>Table employees created successfully!</p>";

                        // // begin the transaction
                        // $connection->beginTransaction();
                        // // our SQL statements
                        // $connection->exec("INSERT INTO employees (firstname, lastname, email) VALUES ('John', 'Doe', 'john@example.com')");
                        // $connection->exec("INSERT INTO employees (firstname, lastname, email) VALUES ('Mary', 'Moe', 'mary@example.com')");
                        // $connection->exec("INSERT INTO employees (firstname, lastname, email) VALUES ('Julie', 'Dooley', 'julie@example.com')");

                        // // commit the transaction
                        // $connection->commit();
                        // echo "<p>New records created successfully</p>";
                        
                       $stmt = $connection->prepare("SELECT id, firstname, lastname FROM employees");
                        $stmt->execute();

                        // set the resulting array to associative
                        $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
                        foreach(new TableRows(new RecursiveArrayIterator($stmt->fetchAll())) as $k=>$v) {
                            echo $v;
                        } 
                    } catch(PDOException $e) {
                        echo "Error: " . $e->getMessage();
                    }
                    

                    $query = $connection->query("SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_TYPE='BASE TABLE'");
                    $tables = $query->fetchAll(PDO::FETCH_COLUMN);

                    if (empty($tables)) {
                        echo "<p>There are no tables in database \"{$database}\"</p>";
                    } else {
                        echo "<p>Database \"{$database}\" has the following tables: </p>";
                        echo "<ul>";
                            foreach ($tables as $table) {
                                echo "<li>{$table}</li>";
                            }
                        echo "</ul>";
                    }
                $connection = null;
            echo "</table>"
        ?>
    </div>
    </div>
</body>
</html>