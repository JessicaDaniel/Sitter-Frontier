<!DOCTYPE html>
<html lang="en">
    <head>
        <!--This gets the style sheet and adds in to the page-->
        <link rel="StyleSheet" href="StyleSheet.css">
        <title>Log In</title>
    </head>
    <body>
    
    <?php
    //includes the functions and the connection
    require_once('config.php');
    include_once('Functions.php');
    //starts session 
    session_start();

    //checks whether the form has been submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
        //intializes variables 
        $usernameErr = $passwordErr = $existErr = "";
        $userProblem = $passProblem = TRUE;
     
         //escapes strings to prevent against injections 
        $username = mysqli_real_escape_string($conn,$_POST["username"]);
        $password = mysqli_real_escape_string($conn,$_POST["password"]);

        //checks for empty postcode if its trure an error message is created and the problem is set to true 
        if (empty($username)) {
            $usernameErr = "* Username is required"; 
            $userProblem = TRUE;
        }
          //if the value is valid sanitizes the value and sets problem to false
        else{
            $username = test_input($_POST["username"]);
            $userProblem = FALSE;
        }
          //checks for empty postcode if its trure an error message is created and the problem is set to true 
        if (empty($password)) {
            $passwordErr = "* Password is required"; 
            $userProblem = TRUE;
        }
        //if the value is valid sanitizes the value and sets problem to false
        else{
            $password = test_input($_POST["password"]);
            $passProblem = FALSE;
        }

        // if the username and password problem:
        if(!$passProblem && !$userProblem){
            //hashes the password
            $Epassword = hash("sha256",$password);
            //checks to see whether user exists in database
            if($count = table_check("SELECT * FROM login WHERE username = '$username' and password = '$Epassword'") == 1){
                //if they do exist the username is stored in a session and they are redirected to the home page 
                $_SESSION['username'] = $username;
                header("location:Home.php");
             }
             //if they are not in the login table checks to see if they are a babysitter in the babysitter login 
             elseif($count = table_check("SELECT * FROM babysitter_login WHERE sitter_ID = '$username' and password = '$Epassword'") == 1){
                //stores username in session and redirects to the sitter account page 
                $_SESSION['username']=$username;
                 header("location:SitterAccount.php");
             }
             //if the user is not found in the database shows error message is set 
             if($count == 0){
                 $existErr= "*The username or password entered is incorrect";
             }
             else{
                 //if the statements above failed means there was a script error so the sql error function is used
                echo sql_error(); 
             }  
         }
         //closes connection 
         mysqli_close($conn);

    }


    ?>
<!--creates the header of the page -->
    <div id="header" >
    <h1>Sitter Frontier</h1>
    <h2>Leaders of the Sitter March</h2>
    <!--link for the user to go to the sign up page if they are not registered-->
    <h3>Not registered?</h3>
    <h3><a href="index.php">Sign Up</a></h3>
    </div>

   
    <div id ="signup" >
    
        <fieldset>
            <h4>Log In</h4>
            <!--creates form  and sends to itself using post as sensitive information being sent. the htmlspecial chars function used to 
              prevent $_SERVER["PHP_SELF"] exploits by converting special characters to html entites-->
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <!--echoes out the error message for the user-->
            <span id="error"> <?php echo $existErr;?></span> 
            <br/>
             <!--creates text box with id so css style code will be applied. echoes the value entered as the value of the 
            txt box so the value entered remains when validating-->
            <input id ="signUpTextbox" type="text" name="username" placeholder="Username" value= "<?php echo $username;?>"/>
            <br/>
            <span id="error"> <?php echo $usernameErr;?></span>           
            <br/>
            <!--creates more txt box inputs-->
            <input id="signUpTextbox" type="password" name="password"  placeholder ="Password" value= "<?php echo $password;?>" />
            <br/>
            <span id="error"> <?php echo $passwordErr;?></span>
            <br/>
            <br/>
            <!--creates submit button-->
            <input type='submit' id='submit' value='Submit'  />
            <br>
            <!--creates link for user to go to forgotten password page  if they have forgotten their password -->
            <h3 style="text-align: center;"><a href="ForgotPassword.php">Forgotten Password?</a></h3>
            </form>
        </fieldset>
        
    </div>
    


       
            
        




  




    </body>
</html>
