<!DOCTYPE HTML>
<html>
<head>
    <!--This gets the style sheet and adds in to the page-->
    <link rel="StyleSheet" href="StyleSheet.css">
    <title>Sign Up</title>
</head>
<body>
    <?php
        
         //gets the connection and functions and includes them so they can be called form the page
         require_once('config.php');
         include_once('Functions.php');

         //checks whether the form has been submitted
         if ($_SERVER["REQUEST_METHOD"] == "POST") {
             
             //intializes variables
              $loginProb = TRUE;
              //escapes strings to prevent against injections 
              $firstName =mysqli_real_escape_string ($conn,$_POST["firstName"]);
              $surname = mysqli_real_escape_string($conn,$_POST["surname"]);
              $username = mysqli_real_escape_string($conn,$_POST["username"]);
              $password = mysqli_real_escape_string($conn,$_POST["password"]);
              $password2 = mysqli_real_escape_string($conn,$_POST["password2"]);
              $email = mysqli_real_escape_string($conn,$_POST["email"]);

              $firstnameErr = $surnameErr = $usernameErr = $passwordErr = $password2Err = $emailErr = "";
              $nameProblem = $surProblem = $userProblem = $passProblem = $pass2Problem = $emailProblem = TRUE;

              
              //checks to see of variable is empty if it is then error message is set and error problem set to true 
             if (empty($firstName))
             {
                 $firstnameErr = "First name is required";
                 $nameProblem = TRUE;
             }
             //if the variable is valid sanitzies it and sets the problem to false 
             else{
                $firstName = test_input($_POST["firstName"]);
                $nameProblem = FALSE; 
             }


             //checks to see of variable is empty if it is then error message is set and error problem set to true 
             if (empty($surname))
             {
                 $surnameErr = "* Surname is required"; 
                 $surProblem = TRUE;
             }
             //if the variable is valid sanitzies it and sets the problem to false 
             else{
                $surname = test_input($_POST["surname"]);
                 $surProblem = FALSE; 
             }


                //checks to see of variable is empty if it is then error message is set and error problem set to true
              if (empty($username)) {
                 $usernameErr = "* Username is required"; 
                 $userProblem = TRUE;
              }
              //uses the function to check whether the username already exists if it does error message set and error problems set to true.
              elseif(table_check("SELECT * FROM login WHERE username = '$username'") != 0){
                  $usernameErr =  "* Username already exists";
                  $userProblem = TRUE;
              }
              //if the variable is valid sanitzies it and sets the problem to false
              else{
                  $username = test_input($_POST["username"]);
                  $userProblem = FALSE;
              }

              
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


              //checks to see of variable is empty if it is then error message is set and error problem set to true 
              if (empty($email)) {
                 $emailErr = "* Email is required"; 
                 $emailProblem = TRUE;
              }
              //checks to see whether the email format is valid if not then error message is set and the problem is set to true 
              elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                  $emailErr = "* Invalid Email";
                  $emailProblem = TRUE;
              }
              //checks to see whether the email is already registered if it is then error message set and problem set to true 
              elseif(table_check("SELECT * FROM users WHERE email = '$email'") != 0){
                  $emailErr =  "* This email is already registered";
                  $emailProblem = TRUE;
              }
              //if the variable is valid sanitzies it and sets the problem to false
              else{
                  $email = test_input($_POST["email"]);
                  $emailProblem = FALSE;
              }



              //if all the problems are false the login problem is set to false 
              if(!$nameProblem && !$surProblem && !$userProblem && !$passProblem && !$pass2Problem && !$emailProblem){
                  $loginProb = FALSE;
              }

              
              if($loginProb == FALSE){
                  //if the login problem is false then the password is hashed for security 
                  $Epassword = hash("sha256",$password);
                  //values are entered into database 
                  $logTable = table_insert("INSERT INTO login (username,password)VALUES('$username','$Epassword')");
                  $userTable = table_insert("INSERT INTO users (username,first_name,surname,email)VALUES('$username','$firstName','$surname','$email')");

                  //if the querys work and the functions return true the user is redirected to the sign up thank you page
                  if($logTable && $userTable == TRUE){
                        header('Location: SignUpThankYou.php');   
                  }
                  //if the query fails then the error message fails 
                  else{
                      echo sql_error();
                  }
              }
                
              //closes connection
              mysqli_close($conn);
         }

      
        ?>
<!--creates the header of the page -->
<div id="header">
    <h1>Sitter Frontier</h1>
    <h2>Leaders of the Sitter March</h2>
    <!--adds a link for the user to get to the login page-->
    <h3>Already registered?</h3>
    <h3><a href="Login.php">Log in</a></h3>
</div>

 <!--creates form  and sends to itself using post as sensitive information being sent. the htmlspecial chars function used to 
    prevent $_SERVER["PHP_SELF"] exploits by converting special characters to html entites-->
<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
   <div>
    <div id ="signup">
        <fieldset>
            <h4>Sign Up</h4>
            <!--echoes out the error message for the user-->
            <span id="error"> <?php echo $firstnameErr;?></span>
             <!--creates text box with id so css style code will be applied. echoes the value entered as the value of the 
            txt box so the value entered remains when validating-->
            <input id ="signUpTextbox" type="text" name="firstName" placeholder="First Name" value="<?php echo $firstName;?>"  />            
            <br/>
            <!--creates more text inputs -->
            <span id="error"> <?php echo $surnameErr;?></span>
            <input id="signUpTextbox" type="text" name="surname"  placeholder ="Surname" value="<?php echo $surname;?>" />
            <br/>
            <!--creates more text inputs -->
            <span id="error"> <?php echo $usernameErr;?></span>
            <input id ="signUpTextbox" type="text" name="username"  placeholder="Username" value="<?php echo $username;?>"  />
            <br/>
            <!--creates more text inputs -->
            <span id="error"><?php echo $passwordErr;?></span>
            <input id ="signUpTextbox" type="password" name="password"  placeholder ="Password" value="<?php echo $password?>"/>
            <br/>
            <!--creates more text inputs -->
            <span id="error"> <?php echo $password2Err;?></span>
            <input id ="signUpTextbox" type="password" name="password2"  placeholder="Re-Enter Password" value="<?php echo $password2?>" /> 
            <br/>
            <!--creates more text inputs -->
            <span id="error"> <?php echo $emailErr;?></span>
            <input id ="signUpTextbox" type="text" name="email" placeholder="Email" value="<?php echo $email?>"/>
            <br/>
            <br/>
            <!--creates submit buttn for form -->
            <input type='submit' id='submit' value='Submit'  />
            
        </fieldset>
    </div>
    
</div>  
</form>
</body>
</html>