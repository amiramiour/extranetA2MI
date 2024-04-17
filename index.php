Hello World
<?php
echo 'PHP !!!';

$servername = "localhost";
$username = "root";
$passeword = "";
$database = "0e5lu_a2mi_extranet";

try{
    $connection = new PDO("mysql:host=$servername;dbname=$database", $username, $passeword);
    echo "database connected !";

} catch (PDOException $exception) {

    echo "database not connected. " . $exception ;

}
