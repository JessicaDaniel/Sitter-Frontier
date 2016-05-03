<!DOCTYPE html>
<html lang="en">
<head>
     <!--This gets the style sheet and adds in to the page-->
    <link rel="StyleSheet" href="StyleSheet.css">
    <title>Forgotten Password</title>
</head>
<body>
    <?php
        
   //gets the connection and functions and includes them so they can be called form the page
    require_once('config.php');
    include_once('Functions.php');

    //checks whether the form has been submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        //gets the email and puts it in a variable 
        //escapes the string which prevents against mysql injections
        $email = mysqli_real_escape_string($conn,$_POST["email"]);

        //intitalises variables 
        $emailErr =  "";
        $emailProb  = TRUE;

        //checks if the email entered is empty 
        if(empty($email)){
            //if the email is empty it populates the emailErr variable with the error message and sets the emailprob to false
            $emailErr = "*Please enter your email";
            $emailProb = TRUE;   
        }
        //checks to see whether the email entered is in the format of a valid email address 
        elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            // if it fails the valid email check then it populates the error message with an appropriate message and sets the problem to true
            $emailErr = "* Invalid Email"; 
            $emailProb = TRUE;   
        }
        //if the following statements do not occour it means the email address entered is valid 
        else{
            //calls the function to 'sanitize' the data by removing ny extra white space etc 
            $email = test_input($_POST["email"]);
            //since there is no problem it sets the problem to false
            $emailProb = FALSE;
        }

        // if the email problem is false meaning the value entered is valid
        if(!$emailProb){

            //checks whether the email is registered 
            if($count = table_check("SELECT * FROM users WHERE email = '$email'") == 1){
                
                //creates a random string using the function with a length of 10
                $token= get_random_string(10);
                // inserts the email address and token is entered into the table  
                table_insert("INSERT INTO token (email,token,used)VALUES('$email','$token',0)");
                
                //sends the email to the user using the mail reset link function 
                $sendMail = mail_reset_link($email,$token);

                // if the message sends correctly then the appropriate message is displayed 
                if($sendMail == TRUE){
                    $mailMsg = "If the email entered is registered an email will be sent with instructions to reset your password. ";
                }
                //if the message is not able to send it will alert the user to this 
                else{
                    $mailMsg = "Unable to send you your password at the moment please try again later.";
                }

            }
            //if the users email address is not in the database it will display the message. the error message does not tell... 
            // the user if the email entered is correct or not so that people will not be able to get a list of what emails are registered and use for malicious purposes 
            else{
                $mailMsg = "If the email entered is registered an email will be sent with instructions to reset your password. ";
            }

        }
        //closes the connection
        mysqli_close($conn);
    }

    ?>
    <!--creates the header of the page -->
    <div id="header" >
        <h1>Sitter Frontier</h1>
        <h2>Leaders of the Sitter March</h2>
    </div>


     <div id ="signup" >
        <fieldset>
        <!--embedded php code which echoes out the mail message for the user to see-->
        <span id="error"> <?php echo $mailMsg;?></span> 
        <h4>Enter Email Address</h4>

        <!--creates form  and sends to itself using post as sensitive information being sent. the htmlspecial chars function used to 
    prevent $_SERVER["PHP_SELF"] exploits by converting special characters to html entites-->
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <span id="error"> <?php echo $emailErr;?></span>           
            <br/>
            <!--creates text box with id so css style code will be applied. echoes the $email value as the value of the 
            txt box so the value entered remains when validating-->
            <input id ="signUpTextbox" type="text" name="email" placeholder="email" value= "<?php echo $email;?>"/>
            <br>
            <!-- creates submit button-->
            <input type='submit' id='submit' value='Get Password'  /> 
             <br>
            <!-- adds a login link so if they wish they can go back to the login page-->
            <h3 style="text-align: center;"><a href="Login.php">Back to Log in</a></h3>
        </form>
        </fieldset>
    </div>
</body>
</html>
