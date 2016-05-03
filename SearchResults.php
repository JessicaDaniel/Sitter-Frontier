<?php
    
 //includes the functions and the connection
    require_once('config.php');
    include_once('Functions.php');

    //starts the session
    session_start();

     //if the user is not logged in then they are redirected back to the login page
    if(!isset($_SESSION['username'])){
        header("Location:Login.php");
    }
    else{

        //intializes variables
        //escapes strings to prevent against injections 
        $postcode = mysqli_real_escape_string($conn,$_SESSION['postcode']);
        $gender = mysqli_real_escape_string($conn,$_SESSION['gender']);
        $minAge = mysqli_real_escape_string($conn,$_SESSION['minAge']);
        $maxAge = mysqli_real_escape_string($conn,$_SESSION['maxAge']);
        $distance =mysqli_real_escape_string($conn,$_SESSION['distance']);
    
        // gets the results of the query for the criteria gender and age 
        $result =get_data("SELECT * FROM babysitters WHERE gender = '$gender' and age BETWEEN '$minAge' and '$maxAge'");
        
        //if script fails echoes error message 
        if($result == FALSE){
            echo sql_error();
        }
        
        else{
           while ($row=mysqli_fetch_array($result)){
            //code for web API: http://stackoverflow.com/questions/14041227/distance-from-point-a-to-b-using-google-maps-php-and-mysql
            //modified the code to find out using the sitters postcode and the users postcode how long the journey takes
             $sitPost = $row["postcode"];
         
             $from = "$sitPost";
             $to = "$postcode";

             $from = urlencode($from);
             $to = urlencode($to);

             $data = file_get_contents("http://maps.googleapis.com/maps/api/distancematrix/json?origins=$from&destinations=$to&language=en-EN&sensor=false");
             $data = json_decode($data);

             $time = 0;

             foreach($data->rows[0]->elements as $road) {
               $time += $road->duration->value;
            
            }
            //time is given in seconds so divide by 60 to convert to minutes
            //if the row matches the distance criteria it is stored in an array.
            if($time/60 <= $distance){
               $new_array[] = $row; 
           
            }
         } 
        }
        //closes the connection
        mysqli_close($conn);
        
    }

    
    
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <!--This gets the style sheet and adds in to the page-->
        <link rel="StyleSheet" href="StyleSheet.css">
        <title>Search Results</title>
</head>
<body>
    <!--a div which contains the main elements of the page-->
    <div id="mainBody">
    <!--creates the header of the page -->
    <h1 id="mainH1"> Sitter Frontier</h1>
    <h2 id="mainH2">Leaders of the Sitter March</h2>
    <!--navigation bar so users can easily navigate throught the app-->
    <ul id="navBar">
        <li><a id="navLinks" href="Home.php.php">Home</a></li>
        <li><a id="navLinks" href="AboutUs.php">About Us</a></li>
        <li><a id="navLinks" href="ContactUs.php">Contact Us</a></li>
        <li><a id="navLinks" href="MyAccount.php">My Account</a></li>
        <li  style="float:right"><a id="navLinks" href="Logout.php">Log Out</a></li>
    </ul>
    <br>
    <h3 id="reH3">Results</h3>
    <hr>
    <!--creates button for user to start an new search-->
    <input type="button" onclick="location.href='Home.php';" value="New Search" />
    <!--if the array is empty there means there are no results so the message is displayed to the user-->
    <?php if(count($new_array) == 0): ?>
        <p>No results found</p>
        <?php endif; ?>
    <table>
        <!--prints the details of each babysitter who meets the users requirements.
        passes the sitter id as the id of the link used to go to the sitters own page-->
         <?php foreach($new_array as $value): ?>
        <tr>
            <td><?php echo "<a href='SitterInfoPage.php?sitter_ID=" . $value["sitter_ID"] . "'>".$value["first_name"]. " ".$value['surname']."</a>" ?>
                <p>Age: <?php echo $value['age'];?></p>
                <p><?php echo $value['Description'];?></p>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    </div>        
</body>
</html>
