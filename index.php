Hello World
<?php
echo 'PHP !!!';

$servername = "localhost";
$username = "root";
$passeword = "root";
$database = "myDB";

try{
    $connection = new PDO("mysql:host=$servername;dbname=$database", $username, $passeword);
    echo "database connected !";

} catch (PDOException $exception) {

    echo "database not connected. " . $exception ;

}
