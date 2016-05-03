<?php
    // calls in the connection file as well as the function file
     require_once('config.php');
     include_once('Functions.php');
     // starts session
     session_start();

     //checks to see if the form has been posted
     if ($_SERVER["REQUEST_METHOD"] == "GET"){
         //gets the values posted and puts them in variables
         //escape the string to prevent sql injections
        $username = mysqli_real_escape_string($conn,$_SESSION['username']);
        $sitterID = mysqli_real_escape_string($conn,$_GET['sitter_ID']);
        
        // checks whether the submit button is to add a sitter 
        if(isset($_GET['addSitter'])){
            //calls the table insert function which insert the user and the babysitter into the friends table
            $result = table_insert("INSERT INTO friends (username,sitter_ID)VALUES('$username','$sitterID')");
            
            //if the query is not able to run and fails it echos out the sql query error
            if ($result == FALSE){
                echo sql_error();
            }
            else{
                //otherwose it rediresct you back to the sitters page passing the sitter id back so that you are able to get the sitter information 
                header('Location: SitterInfoPage.php?sitter_ID='.$sitterID);
            }
            
        }
        // if it is not the add sitter submit button then it will be the remove sitter button
        else{
            // instead of adding it deletes from the friends table where the username and sitter id match 
             $result = table_insert("DELETE FROM friends where username ='$username' and sitter_ID = '$sitterID'");
            // if the query fails it echoes out the error message for the user
            if ($result == FALSE){
                echo sql_error();
            } 
            //checks if the remove request is coming from the my account page 
            elseif(isset($_GET['RemoveSitterAccount'])){
            //if it is then it redirects back to the my account page
              header('Location: MyAccount.php');  
            }
            else{
                // if it's not from the account page then it redirects back to the sitter id passing the sitter id back again 
                header('Location: SitterInfoPage.php?sitter_ID='.$sitterID);
            }
        }
       //closes connection 
        mysqli_close($conn);   
     }
          
       

?>
