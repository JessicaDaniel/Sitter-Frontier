<?php
    
    //includes the functions and the connection 
    require_once('config.php');
    include_once('Functions.php');
    
    //starts the session
    session_start();
    
    //gets the token from the link sent to the users email 
    $token = mysqli_real_escape_string($conn,$_GET['token']);

    //checks whether the form has been submitted 
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      
      //intializes variables
       //escapes strings to prevent against injections
      $password = mysqli_real_escape_string($conn,$_POST['password']);
      $password2 = mysqli_real_escape_string($conn,$_POST['password2']);
      $token = mysqli_real_escape_string($conn,$_POST['token']);

      $passwordErr = $password2Err = "";
      $pass2Problem = $passProblem = TRUE;

      //checks to see of variable is empty if it is then error message is set and error problem set to true
      if (empty($password)) {
          $passwordErr = "* Password is required"; 
          $passProblem = TRUE;
      }
       // checks strength of password using function if it does not meet requirements the error msg...
               //...is returned which is set as the error message and the problem is set to true
      elseif($strength = pass_strength($password)){
           $passwordErr = $strength;
           $passProblem = TRUE;
      }
      //if the variable is valid sanitzies it and sets the problem to false
      else{
          $password = test_input($_POST["password"]);
          $passProblem = FALSE;
      }


      //checks to see of variable is empty if it is then error message is set and error problem set to true
      if (empty($password2)) {
         $password2Err = "* Re-Type password"; 
         $pass2Problem = TRUE;
     }
     //checks whether passwords match if they don't error message set and problem set to true
     elseif(password_match_check($password,$password2) == FALSE){
         $password2Err = "* Passwords do not match";
         $pass2Problem = TRUE; 
     }
      //if the variable is valid sanitzies it and sets the problem to false
     else{
         $password2 = test_input($_POST["password2"]);
         $pass2Problem = FALSE;
     }
     
     // if both passwords problems are false:
     if(!$pass2Problem && !$passProblem){
            
           //checks to see whether or not the token has been used already
            $result = get_data("SELECT email FROM token WHERE token = '$token' and used = 0");

            // if the token has been used then an error message is set 
            if(mysqli_num_rows($result) == 0){
                $errorMsg = "*Invalid link or Password already changed";
            }
            //get the email from the row 
            else{
                while ($row=mysqli_fetch_array($result)){
                     $email = $row['email'];
                }

                // get the username using the email 
                $result = get_data("SELECT username FROM users WHERE email = '$email'");
                while ($row=mysqli_fetch_array($result)){
                     $username = $row['username'];
                }

                //hash the password for security 
                 $Epassword = hash("sha256",$password);
                 //update password in database for the user 
                 $changedPass = table_insert("UPDATE login SET password = '$Epassword' where username = '$username'");

                 if($changedPass == TRUE){
                    //changes the used column to 1 so the same link cannot be used again by the user 
                    table_insert("UPDATE token SET  used = 1 WHERE token = '$token'");
                    //redirects to the login for the user to login 
                    header("location:Login.php");   
                 }
                 //if script fails error is echoed
                 else{
                    echo sql_error();
                 }
            }
            //closes the connection
            mysqli_close($conn);

   

    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!--This gets the style sheet and adds in to the page-->
    <link rel="StyleSheet" href="StyleSheet.css">
    <title>Reset Password</title>
</head>
<body>
<!--creates the header of the page -->
<div id="header">
    <h1>Sitter Frontier</h1>
    <h2>Leaders of the Sitter March</h2>
    <h3>Already registered?</h3>
    <h3><a href="Login.php">Log in</a></h3>
</div>
<!--creates form  and sends to itself using post as sensitive information being sent. the htmlspecial chars function used to 
    prevent $_SERVER["PHP_SELF"] exploits by converting special characters to html entites-->
<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
   <div>
    <div id ="signup">
        <fieldset>
            <!--echoes out the error message for the user-->
            <span id="error"><?php echo $errorMsg;?></span>
            <h4>Reset Password</h4>
            <span id="error"><?php echo $passwordErr;?></span>
            <!--creates text box with id so css style code will be applied. echoes the value entered as the value of the 
            txt box so the value entered remains when validating-->
            <input id ="signUpTextbox" type="password" name="password"  placeholder ="Password" value="<?php echo $password?>"/>
            <br/>
            <span id="error"> <?php echo $password2Err;?></span>
            <!--creates more txt box inputs-->
            <input id ="signUpTextbox" type="password" name="password2"  placeholder="Re-Enter Password" value="<?php echo $password2?>" /> 
            <input type="hidden" name="token" value="<?php echo $token; ?>" />
            <br/>
            <br/>
            <!--creates submit button-->
            <input type='submit' id='submit' value='Reset Password'  />
            
        </fieldset>
    </div>
    
</div>  
</form>  
</body>
</html>
