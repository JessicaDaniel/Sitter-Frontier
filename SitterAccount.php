<?php
    
    //includes the functions and the connection
    require_once('config.php');
    include_once('Functions.php');
    //starts session 
    session_start();
    
     //if the user is not logged in then they are redirected back to the login page
    if(!isset($_SESSION['username'])){
        header("Location:Login.php");
    }
    else{
        //if they are logged in checks whether the form has been submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
         //checks whether the user has submitted the password form 
         if(isset($_POST['submitPass'])){
             
             //intializes variables
            $passwordErr = $password2Err = $oldPasswordErr = "";
            $pass1Problem = $pass2Problem = $pass3Problem = TRUE;

             //escapes strings to prevent against injections 
            $sitterID = mysqli_real_escape_string($conn,$_SESSION['username']);
            $oldPassword = mysqli_real_escape_string($conn,$_POST['oldPassword']);
            $password = mysqli_real_escape_string($conn,$_POST['password']);
            $password2 = mysqli_real_escape_string($conn,$_POST['password2']);

            //checks for empty postcode if its trure an error message is created and the problem is set to true
            if(empty($oldPassword)) {
            $oldPasswordErr = "*Enter password"; 
            $pass1Problem = TRUE;
            }
            //checks whether the password and username is in the database. if not error message is set and problem is set to true
            elseif($count = table_check("SELECT * FROM babysitter_login WHERE sitter_ID = '$sitterID' and password ='".hash("sha256",$oldPassword)."'" )!= 1){
                $oldPasswordErr = "*Password entered is incorrect";
                $pass1Problem = TRUE;
            }
            //if the value is valid sanitizes the value and sets problem to false
            else{
                $oldPassword = test_input($_POST["oldPassword"]);
                $pass1Problem = FALSE;
            }

             //checks for empty postcode if its trure an error message is created and the problem is set to true
            if (empty($password)) {
                $passwordErr = "* Password is required"; 
                $pass2Problem = TRUE;
            }
            // checks strength of password using function if it does not meet requirements the error msg...
               //...is returned which is set as the error message and the problem is set to true 
            elseif($strength = pass_strength($password)){
                  $passwordErr = $strength;
                  $passProblem = TRUE;
            }
            //if the value is valid sanitizes the value and sets problem to false
            else{
                $password = test_input($_POST["password"]);
                $pass2Problem = FALSE;
            }

            //checks for empty postcode if its trure an error message is created and the problem is set to true
            if (empty($password2)) {
                $password2Err = "* Re-Type password"; 
                $pass3Problem = TRUE;
            }
            //checks whether passwords match if they don't error message set and problem set to true
            elseif(password_match_check($password2,$password) == FALSE){
                $password2Err = "* Passwords do not match";
                $pass3Problem = TRUE; 
            }
            //if the variable is valid sanitzies it and sets the problem to false
            else{
                $password2 = test_input($_POST["password2"]);
                $pass3Problem = FALSE;
            }

            //if all problems are false:
            if(!$pass1Problem && !$pass2Problem && !$pass3Problem){
                //hashes the password for security
                $Epassword = hash("sha256",$password);

                //changes the password in database
                $changedPass = table_insert("UPDATE babysitter_login SET password = '$Epassword' where sitter_ID = '$sitterID'");

                // if query is successful sets success message 
                if($changedPass == TRUE){
                    $changedMsg = "Success - Password changed";   
                }
                //if query fails echoes out error message
                elseif($changedPass == FALSE){
                    echo sql_error();
                } 
            }
            //if problems are still stries sets message to be error 
            else{
                $changedMsg = "Error - Password was not changed. Please try again"; 
            }
         
         }

         //if the user has submitted the change username form 
         if(isset($_POST['submitUser'])){

             //intializes variables
             //escapes strings to prevent against injections 
              $sitterUsername = mysqli_real_escape_string($conn,$_POST["sitterID"]);
              $sitterID = mysqli_real_escape_string($conn,$_SESSION["username"]);
              $nameErr = "";
              $nameProb = TRUE;

              //checks for empty postcode if its trure an error message is created and the problem is set to true
               if (empty($sitterUsername)) {
                 $nameErr = "* Username is required"; 
                 $nameProb = TRUE;
              }
              //checks databse to see if the username is already in use, if it is then the error message is set and the problem is set to true
              elseif(table_check("SELECT * FROM babysitter_login WHERE sitter_ID = '$sitterUsername'") != 0){
                  $nameErr =  "* Username already exists";
                  $nameProb = TRUE;
              }
               //if the value is valid sanitizes the value and sets problem to false
              else{
                  $sitterUsername = test_input($_POST["sitterID"]);
                  $nameProb = FALSE;
              }

              //if username entered is valid:
              if(!$nameProb){
                //updates username where the username is the same as the original username 
                $changedID = table_insert("UPDATE babysitter_login SET sitter_ID = '$sitterUsername' where sitter_ID = '$sitterID'");

                //if query is successful the success message is set and the session is repopulated with the new username 
                if($changedID == TRUE){
                    $changedMsg = "Success - Username changed"; 
                    $_SESSION['username'] = $sitterUsername;  
                }
                //if query fails error message is echoed 
                elseif($changedID == FALSE){
                    echo sql_error();
                } 
            }
            //if username problem is true then error message is set
            else{
                $changedMsg = "Error - Username was not changed. Please try again"; 
            }
         
         } 
     }
     //closes connection 
    mysqli_close($conn); 
    }

    //gets the sitter id from the session
    $sitterID =  mysqli_real_escape_string($conn,$_SESSION["username"]);
    
    //gets all the reviews for the babysitter
    $result = get_data("SELECT username,rating,description FROM sitter_review where sitter_ID = '$sitterID'");
    //counts number of rows 
    $count = mysqli_num_rows($result);
    //closes connection 
    mysqli_close($conn);


?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <!--This gets the style sheet and adds in to the page-->
        <link rel="StyleSheet" href="StyleSheet.css">
        <title>My Account</title>
    </head>
    <body>
         <!--a div which contains the main elements of the page-->
       <div id="mainBody">
           <!--creates the header of the page -->
            <h1 id="mainH1"> Sitter Frontier</h1>
            <h2 id="mainH2">Leaders of the Sitter March</h2>
            <!--navigation bar so users can easily navigate throught the app-->
            <ul id="navBar">
                <li><a id="navLinks" href="SitterAccount.php">Home</a></li>
                <li><a id="navLinks" href="AboutUs.php">About Us</a></li>
                <li><a id="navLinks" href="ContactUs.php">Contact Us</a></li>
                <li  style="float:right"><a id="navLinks" href="Logout.php">Log Out</a></li>
            </ul>

           <!--embedded php to show the user whether their password has been changed or not-->
            <span id="error"><?php echo $changedMsg;?></span><br>
            <label> Click the button to change your password</label>
           <!-- button which shows the form when it is clicked and hides it when double clicked by using javascript functions-->
            <button id ="showHideButton" type="button" onclick="show('changePass')" ondblclick="hide('changePass')">Change Password</button>
           
           <!-- sets the intial display of the div to none so the form does not show when page is loaded -->
            <div style="display: none;" id="changePass"  >
                 <!--creates form  and sends to itself using post as sensitive information being sent. the htmlspecial chars function used to 
            prevent $_SERVER["PHP_SELF"] exploits by converting special characters to html entites-->
                <form  method="Post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                      <!--echoes out the error message for the user-->
                    <span id="error"><?php echo $oldPasswordErr;?></span><br>
                    <!--creates text box with id so css style code will be applied. echoes the value entered as the value of the 
                    txt box so the value entered remains when validating-->
                    <input id ="signUpTextbox" style="width: 200px;"type="password" name="oldPassword"  placeholder="Old Password"  /> 
                    <br/>
                    <!--creates more txt inputs-->
                    <span id="error"><?php echo $passwordErr;?></span><br>
                    <input id ="signUpTextbox"  style="width: 200px;"type="password" name="password"  placeholder ="New Password"/>
                    <br/>
                    <!--creates more txt inputs-->
                    <span id="error"><?php echo $password2Err;?></span><br>
                    <input id ="signUpTextbox" style="width: 200px;"type="password" name="password2"  placeholder="Re-Enter New Password"  /> 
                    <br/>
                    <!--creates submit button-->
                    <input style="width: 150px;" type='submit' name='submitPass' value='Change'  />
                </form>
            </div>
           <br>
           <br>
           
            <label> Click the button to change your Username</label>
            <!--another button to show/hide form-->
            <button id ="showHideButton" type="button" onclick="show('changeUser')" ondblclick="hide('changeUser')">Change Username</button>
            
           <div style="display: none;" id="changeUser"  >
               <!-- creates another form with the method post to change the username -->
                 <form  method="Post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                    <span id="error"><?php echo $nameErr;?></span><br>
                    <input id ="signUpTextbox" style="width: 200px;"type="text" name="sitterID"  placeholder="Username" value="<?php echo $sitterUsername?>" /> 
                    <br/>
                    <input style="width: 150px;" type='submit' name='submitUser' value='Change'  />
                </form>
            </div>


        <label id ="contactBookLabel">My Reviews</label>
        <div>
            <!--creates table to display the user of the users-->
            <table id="friendsBook">
            <tr>
            <th>Username</th>
            <th>Rating</th>	
            <th>Comment</th>
            </tr>
            
        <?php 
            while ($row=mysqli_fetch_array($result)){
                //echoes out the information as a table 
                echo "<tr>";
                echo"<td>". $row['username']."</td>";
                echo"<td>". $row['rating']."</td>";
                echo"<td>". $row['description']."</td>";
                echo"</tr>";
            }
        ?>
        </table>
        </div>
      </div>  
    </body>
</html>
