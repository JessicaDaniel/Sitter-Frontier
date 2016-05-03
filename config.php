 <?php
 
//defines the connection information as constants so they can not be changed anywhere else throught the code
 define('servername', 'localhost');
 define('user', 'root');
 define('pass', 'kumasi123');
 define('dbname', 'Babysitters');
 


 // Creates a connection
 $conn = mysqli_connect(servername, user,pass, dbname);
 if ($conn->connect_error) {
     //kills the script if the connecton fails
     die("Connection failed: " . mysqli_connect_error());
 }


?>
