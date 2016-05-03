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
    
     //intializes variables
      //escapes strings to prevent against injections 
      //gets the sitter id that was passed throught the link
    $sitterID = mysqli_real_escape_string($conn,$_GET['sitter_ID']);
    $username = $_SESSION['username'];

    //checks if the babysitter has been added to the users account 
    $addedCheck = table_check("SELECT * FROM friends WHERE username = '$username' and sitter_ID = '$sitterID'");

    //sets added variable to true depending on whether they are added or not 
    if($addedCheck == 1){
        $added = TRUE;
    }
    else{
        $added = FALSE;
    }

    //if they are logged in checks whether the form has been submitted
    if ($_SERVER["REQUEST_METHOD"] == "GET"){
         
         //checks whether the user has submitted the review form  
        if(isset($_GET['addReview'])){
            
            //gets values stores in variables 
            $rating = $_GET['rating'];
            $comment = $_GET['comment'];

            //checks to count the amount of reviews 
            $reviewID = table_check("SELECT * FROM sitter_review");
            //if their are no reviews then the review id is set to be 1  
            if($reviewID == 0){
                $reviewID = 1;
            }
            else{
                //if their are reviews then te review id is the number of reviews incremented by 1 
                $reviewID = $reviewID + 1;
            }
            //inserts the review into the database 
            $reviewTable = table_insert("INSERT INTO sitter_review (sitter_ID,username,review_ID,rating,description)VALUES('$sitterID','$username','$reviewID','$rating','$comment')");
            
            //if the script fails the error message is echoed     
            if ($reviewTable == FALSE ){
                echo sql_error();  
            }   
        } 
    }
   
    //gets the information of the babysitter 
    $result = get_data("SELECT * FROM babysitters WHERE sitter_ID = '$sitterID'");
    
    //if the script fails the error message is echoed
    if ($result == FALSE){
        echo sql_error();
    }
    else{
        //if query is successful then the values are retreived and strored in variables 
        while ($row=mysqli_fetch_array($result)){
        
            $name = $row["first_name"]. " " . $row["surname"];
            $age = $row["age"];
            $image = $row['photo_path'];
            $phone =$row['phone_number'];
            $email = $row['email'];
            $description = $row['Description'];
            $gender = $row['gender'];
        }
    }
    //gets the reviews for the babysitter and oreders them in descending order so the newest review is shown at the top 
    $result = get_data( "SELECT * FROM sitter_review WHERE sitter_ID = '$sitterID'order by review_ID desc");
    
    //if query fails error message echoed 
    if ($result == FALSE){
        echo sql_error();
    }
    else{
        //puts rows into the reviews array
        while ($row=mysqli_fetch_array($result)){
        $reviews[]=$row;
        } 
    }
   //closes connection 
    mysqli_close($conn);
    

  
    

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!--This gets the style sheet and adds in to the page-->
    <link rel="StyleSheet" href="StyleSheet.css">
    <!--title is the name of the babysitter-->
    <title><?php echo $name;?></title>
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
      <!--button for user to start new search-->
      <input type="button" onclick="location.href='Home.php';" value="New Search" />
      <br> 
      <!--echoes out the babysitters name--> 
      <h3 id="reH3"><?php echo $name;?></h3>
      <br>
       <div id="sitterInfoBox">
           <!--depending on whether the babysitter is added the added or remove sitter button will be shown
           this is a form which when pressed submits and goes to the php code to remove/add the sitter to your account
           the sitter id is stored as the value of the the hidden input-->
           <?php if($added == FALSE):?>
           <form method="GET" action="AddRemoveSitter.php">
           <input type="hidden" name="sitter_ID" value="<?php echo $_GET['sitter_ID']; ?>" />
           <input style="width: 100px"type='submit' name='addSitter' value='Add Sitter'  />
           </form>
           <?php else:?>
           <form method="GET" action="AddRemoveSitter.php">
           <input type="hidden" name="sitter_ID" value="<?php echo $_GET['sitter_ID']; ?>" />
           <input style="width: 100px" type='submit' name='RemoveSitter' value='Remove Sitter'  />
           </form>
           <?php endif;?>

           <!--displays babysitter information-->
           <p>Age: <?php echo $age;?> </p> 
           <p>Gender: <?php echo $gender;?> </p> 
           <p>Phone Number: <?php echo $phone;?> </p> 
           <p>Email: <?php echo $email;?> </p>
           <p>About Me: <?php echo $description;?> </p>  
       </div>
        <!--shows image-->
      <img src="<?php echo $image;?>" width="300px" height="400px" alt="image"/>

      <div id="enterReview">
          <!--form to enter a review-->
          <!--creates form  and sends to itself using get. the htmlspecial chars function used to 
            prevent $_SERVER["PHP_SELF"] exploits by converting special characters to html entites-->
          <form method="GET" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
          <label>Select Rating: </label><br>
        <!--drop down box used so user can select rating from a controlled list-->
           <select name="rating">
              <option value="1">1</option>
              <option value="2">2</option>
              <option value="3">3</option>
              <option value="4">4</option>
              <option value="5">5</option>
              <option value="6">6</option>
              <option value="7">7</option>
              <option value="8">8</option>
              <option value="9">9</option>
              <option value="10">10</option>
           </select>
           <br>
           <br>
            <!--text area to allow user to enter comment -->
           <textarea maxlength ="255"  name="comment" placeholder="Enter text here (max characters 255)..."></textarea><br>
           <input type="hidden" name="sitter_ID" value="<?php echo $_GET['sitter_ID']; ?>" />
            <!--creates submit button-->
           <input type='submit' name='addReview' value='Submit'  />
           
          </form>
      </div>
      
      <div id ="reviewBox">
          <h4>Reviews </h4>
          <hr>
          <!--if there are no reviews it lets the user know-->
          <?php if(count($reviews) == 0): ?>
            <p>No Reviews</p>
          <?php endif; ?>
            <table>
            <!-- prints all the reviews from the review value in a table-->
            <?php foreach($reviews as $value): ?>
            <tr>
            <td><p style="font-weight: 600">username: <?php echo  $value["username"]; ?></p>
            <p>Rating: <?php echo $value['rating'];?></p>
            <p>Comment: <?php echo $value['description'];?></p>
            </td>
            </tr>
            <?php endforeach; ?>
            </table>
        </div>

     </div>       
</body>
</html>
