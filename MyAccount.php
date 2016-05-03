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
         //if they are logged in checks whether the form has been submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

         //intializes variables
        $passwordErr = $password2Err = $oldPasswordErr = "";
        $pass1Problem = $pass2Problem = $pass3Problem = TRUE;
        //escapes strings to prevent against injections 
        $username = mysqli_real_escape_string($conn,$_SESSION['username']);
        $oldPassword = mysqli_real_escape_string($conn,$_POST['oldPassword']);
        $password = mysqli_real_escape_string($conn,$_POST['password']);
        $password2 = mysqli_real_escape_string($conn,$_POST['password2']);

         //checks for empty postcode if its trure an error message is created and the problem is set to true
        if(empty($oldPassword)) {
            $oldPasswordErr = "*Enter password"; 
            $pass1Problem = TRUE;
        }
        //checks whether the password and username is in the database. if not error message is set and problem is set to true 
        elseif($count = table_check("SELECT * FROM login WHERE username = '$username' and password ='".hash("sha256",$oldPassword)."'" )!= 1){
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


        //checks to see of variable is empty if it is then error message is set and error problem set to true 
        if (empty($password2)) {
            $password2Err = "* Re-Type password"; 
            $pass3Problem = TRUE;
        }
        //checks whether passwords match if they don't error message set and problem set to true
        elseif(password_match_check($password2,$password) == FALSE){
            $password2Err = "* Passwords do not match";
            $pass3Problem = TRUE; 
        }
        else{
             //if the variable is valid sanitzies it and sets the problem to false
            $password2 = test_input($_POST["password2"]);
            $pass3Problem = FALSE;
        }

        // if all the problems are false:
        if(!$pass1Problem && !$pass2Problem && !$pass3Problem){
            //hashes the password for security 
            $Epassword = hash("sha256",$password);
            //changes the password in the database
            $changedPass = table_insert("UPDATE login SET password = '$Epassword' where username = '$username'");

            //if the function to change the password returns true then the success message is set
            if($changedPass == TRUE){
                $changedMsg = "Success - Password changed";   
            }
            else{
                //if script fails sql error is echoed
                echo sql_error();
            } 
              
        }
        //if the password problems are true sets error message for if the passwor dwas not changed
        else{
            $changedMsg = "Error - Password was not changed. Please try again"; 
        }

    }

    // gets the username from the session 
   $username = mysqli_real_escape_string($conn,$_SESSION['username']);
   //gets babysitters who have beend added by the user
   $sql = "SELECT babysitters.sitter_ID, babysitters.first_name, babysitters.surname, babysitters.phone_number,babysitters.email
            FROM babysitters 
            INNER JOIN friends 
            ON babysitters.sitter_ID=friends.sitter_ID
            WHERE friends.username = '$username'";
        $result = mysqli_query($conn,$sql);
    //closes connection 
    mysqli_close($conn);
  }
   


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
            <li><a id="navLinks" href="Home.php">Home</a></li>
            <li><a id="navLinks" href="AboutUs.php">About Us</a></li>
            <li><a id="navLinks" href="ContactUs.php">Contact Us</a></li>
            <li><a id="navLinks" href="MyAccount.php">My Account</a></li>
            <li  style="float:right"><a id="navLinks" href="Logout.php">Log Out</a></li>
       </ul>
        <!--echoes out message custome to the user-->
        <h3 id ="aH3">Welcome <?php echo $username?> To Your Acount</h3>

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
             <!--creates more txt box inputs-->
            <span id="error"><?php echo $passwordErr;?></span><br>
            <input id ="signUpTextbox"  style="width: 200px;"type="password" name="password"  placeholder ="New Password"/>
            <br/>
             <!--creates more txt box inputs-->
            <span id="error"><?php echo $password2Err;?></span><br>
            <input id ="signUpTextbox" style="width: 200px;"type="password" name="password2"  placeholder="Re-Enter New Password"  /> 
            <br/>
             <!--creates submit button-->
            <input style="width: 150px;" type='submit' id='submit' value='Change'  />
        </form>
        </div>
        <br>
        <label id ="contactBookLabel">Babysitter Contact Book</label>
        
        <div>
             <!--creates table to display the babysitters that have been added by the user-->
            <table id="friendsBook">
            <tr>
            <th>Name</th>
            <th>Phone Number</th>	
            <th>Email</th>
            </tr>
            
        <?php 
            //while there are rows of results left it will echo out each babysitters details in the table 
            while ($row=mysqli_fetch_array($result)){
                echo "<tr>";
                echo"<td>". $row['first_name']." ".$row['surname']."</td>";
                echo"<td>". $row['phone_number']."</td>";
                echo"<td>". $row['email']."</td>";
                
                //creates the button that can be used to remove sitters 
                echo "<td><form method='GET' action='AddRemoveSitter.php'>";
                //passes the sitter id using a hidden input.
                echo "<input type='hidden' name='sitter_ID' value=".$row["sitter_ID"]." />";
                echo "<input style='width: 100px' type='submit' name='RemoveSitterAccount' value='Remove Sitter'  /></td>"; 
                echo "</form>";
                echo"</tr>";
            }
        ?>
        </table>
        </div>
    </div>
     
</body>
</html>
