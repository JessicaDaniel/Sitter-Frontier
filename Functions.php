<?php
    
    
    //incudes the connection
    require_once('config.php');
    
    
    //function which 
    function test_input($data) {
    //removes unneccessary characters from the value entered 
    $data = trim($data);
    //removes backslashes entered 
    $data = stripslashes($data);
    //converts the value entered to a html entitie
    $data = htmlspecialchars($data);
    //returns the value 
    return $data;
    }

    //checks to see whether the two values are the same. takes 2 arguments
    function password_match_check($pass1,$pass2){
        // if they do not match then false is returned 
        if ($pass1 != $pass2){
            return FALSE;
        }
        //if they do match then true is returned 
        else{
            return TRUE;
        }
        
    }
    
    //when a script error occours e.g. a query fails this function will be used 
    function sql_error(){
        //error msg is a window alert which lets the user know something has gone wrong on our end.
        $errorMsg = "<script>window.alert('Not able to execute at the moment please try again later')</script>";
        // returns the error message
        return $errorMsg;
    }

    //checks the table based on the query that is entered in the function 
    function table_check($sql){
        //declares the connection as global 
        global $conn;
        //sets the result variable as the result of the query 
         $result = mysqli_query($conn, $sql );
        //if the query fails returns false
        if (!$result){
           return FALSE;
        }
        //if the query is successful it counts how many rows there are and returns the amount in the count variable
        else{
             $count = mysqli_num_rows($result);
             return $count;   
        }
         
    }

    //function used to insert data whether that is adding, deleting or updating.
    function table_insert($sql){
        //sets the connection as global 
        global $conn;
        // if the hange to the database is successful it returns true 
        if (mysqli_query($conn, $sql) == TRUE){
            return TRUE;
        }
        //if it fails it returns false
        else{
            return FALSE;
        }
    }

    //function used to retreive data from the database based on the query 
    function get_data($sql){
        //delcares the connection as global 
        global $conn;
        //populates the variable with the results of the query 
        $result = mysqli_query($conn,$sql);
        //if the query fails returns false
        if(!$result)
        {
            return FALSE;
        }
        //if it succeeds it returns the result 
        else{
          return $result;  
        }
        
    }

    //function used to generate a random token string for the process of sending a reset password email 
    //takes 1 argument, which is the lenght of string you want 
    function get_random_string($length){
        //populates variable with the characters that should be used to generate string 
        $validCharacters = "ABCDEFGHIJKLMNPQRSTUXYVWZ123456789";
        //gets the length og the string of valid characters 
        $validCharNumber = strlen($validCharacters);
        //initializes the result
        $result = "";

        //for loop to genrate the string until the string length of the number generated is the length specified 
        for ($i = 0; $i < $length; $i++) {
            //populates the index by getting a random number between 0  and the length of the valid characters 
            $index = mt_rand(0, $validCharNumber - 1);
            //it then gets the chracter from the valid characters based on its position and appends it to the result string.
            $result .= $validCharacters[$index];
        }
        //returns the generated string 
        return $result;
    }

    //used to mail the reset email to the user when they have forgotten their password 
     function mail_reset_link($to,$token){
        //creates the subject line 
        $subject = "Sitter Frontier: Reset Password";
        //message created using html tags
        // for the reset link the token is eneterd in as the id of the link 
        $message = '
        <html>
        <head>
        <title>Forgot Password For Sitter Fontier</title>
        </head>
        <body>
        <p>Click on the given link to reset your password <a href="http://localhost:32955/ResetPassword.php?token='.$token.'">Reset Password</a></p>

        </body>
        </html>
        ';
        //email address for sitter frontier
        $headers = 'From: Sitter Frontier<SitterFrontier@example.com>';
        
        //uses mail function to send the email 
        if(mail($to,$subject,$message,$headers)){
            //if the mail send successfully true is returned 
            return TRUE; 
        }
        else{
            //if the mail function fails false is returned 
          return FALSE;  
        } 
     }
      //checks the strength of the value passed through
     function pass_strength($password){
     //creates error message and populate $msg variable 
         $msg = "<p>*Password: Must Be Between 8-20 Characters.</p>
            <p>Must Contain At Least 1 Number.</p>
            <p>Must Contain At Least 1 Capital Letter.</p>
            <p>Must Contain At Least 1 Lowercase Letter</p>";
        //checks whether the password contains at least: 1 capital and lower case letter, a number and is between 8 and 20 characters 
        if(!preg_match('/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%]{8,20}$/', $password)){
            //if the check fails then the error msg is returned 
            return $msg;
        }
 
     }
    

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title></title>
    </head>
    <body>
                
       <script type="text/javascript">
           //function that shows an element takes the id of the element as a parameter
           function show(id) {
               //changes the elements display to a block display
               document.getElementById(id).style.display = "block";

           }
           //function which hides an element 
           function hide(id) {
               //changes the elements display to none so it is not shown
               document.getElementById(id).style.display = "none";
           }
           
           //used to create a slideshow for images 
           function SlideShow() {
               //declares a variable i
               var i;
               //declare variable x as the element with the class name of about us.
               var x = document.getElementsByClassName("aboutUs");

               //for all the elements in x it changes the dispplay to none so they are not shown.
               //the loop continues unitl the lenght of x is reached.
               for (i = 0; i < x.length; i++) {
                   x[i].style.display = "none";
               }

               
               myIndex++;
               //if the my index is greater than the amount of elements in x then my index is reset back to th first element 1
               if (myIndex > x.length) {
                   myIndex = 1
               }

               //changes the display of the elements form none back to block so they are shown
               x[myIndex - 1].style.display = "block";
               //changes the image every 2 seconds 
               setTimeout(SlideShow, 2000);   
           }

        </script> 

    </body>
</html>
