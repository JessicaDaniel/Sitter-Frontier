<!DOCTYPE html>
<html lang="en">
<head>
     <!--This gets the style sheet and adds in to the page-->
    <link rel="StyleSheet" href="StyleSheet.css">
    <title>Home</title>
</head>
<body>
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
        if (isset($_GET['submit'])) {
        //intializes variables 
         $searchProb = TRUE;
         //escapes strings to prevent against injections 
         $postcode = mysqli_real_escape_string($conn,$_GET["postcode"]) ;
         $gender = mysqli_real_escape_string($conn,$_GET["gender"]);
         $maxAge = mysqli_real_escape_string($conn,$_GET["maxAge"]);
         $minAge = mysqli_real_escape_string($conn,$_GET["minAge"]);
         $distance = mysqli_real_escape_string($conn,$_GET["distance"]);
         $postErr = $genderErr = $ageErr = $distanceErr = "";
         $postProblem = $genderProblem = $ageProblem = $distanceProblem = TRUE;

         //checks for empty postcode if its trure an error message is created and the problem is set to true 
          if (empty($postcode))
             {
                 $postErr = "*Postcode is required";
                 $postProblem = TRUE;
             }
             //if the value is valid sanitizes the value and sets problem to false
             else{
                $postcode = test_input($_GET["postcode"]);
                $postProblem = FALSE; 
                
             }

             //checks whether gender is empty if true then error message is set and the problem is set to be true 
             if (empty($gender))
             {
                 $genderErr = "*Select a Gender";
                 $genderProblem = TRUE;
             }
             else{
                 //valid data is sanitized and the problem is set to false 
                $gender = test_input($_GET["gender"]);
                $genderProblem = FALSE;
             }

             //checks whether distance is empty if true then error message is set and the problem is set to be true 
             if (empty($distance))
             {
                 $distanceErr = "*Select a Distance";
                 $distanceProblem = TRUE;
             }
             else{
                 //valid data is sanitized and the problem is set to false 
                $distance = test_input($_GET["distance"]);
                $distanceProblem = FALSE;
             }

             // if either ages are empty a error message is printed and the problem is set to be true
             if(empty($maxAge) or empty($minAge)==TRUE){
                $ageErr = "*Enter Min/Max age";
                $ageProblem = TRUE; 
             }
             //checks whether the value entered is numeric if not error message set and the problem is set to true 
             elseif(!is_numeric($minAge) or !is_numeric($maxAge)){
                $ageErr = "*Enter a valid Min/Max age";
                $ageProblem = TRUE; 
             }
             else{
                //valid data is sanitized and the problem is set to false 
                $maxAge = test_input($_GET["maxAge"]);
                $minAge = test_input($_GET["minAge"]);
                $ageProblem = FALSE; 
             }

             //if all the problems are false the search problem is set to be false also 
             if(!$postProblem && !$genderProblem && !$ageProblem && !$distanceProblem){
                  $searchProb = FALSE;
              }

              //if the search problem is false the search criteria are stroed in the session and they are redirected to the search results
              if($searchProb == FALSE){
                 $_SESSION['postcode']=$postcode;
                 $_SESSION['gender']=$gender;
                 $_SESSION['minAge']=$minAge;
                 $_SESSION['maxAge']=$maxAge;
                 $_SESSION['distance']=$distance;

                 header('Location: SearchResults.php');
              }
     }
    }
     
?>

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
        <!--user must be logged in so no need to change the button to logged in for when the user is not logged in-->
        <li  style="float:right"><a id="navLinks" href="Logout.php">Log Out</a></li>
    </ul>

     <!--creates form  and sends to itself. the htmlspecial chars function used to prevent $_SERVER["PHP_SELF"] exploits by converting 
            special characters to html entites
    the method used is get instead of post as the information is not sensitive -->
    <form method="GET"  action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">

        <fieldset id="search">
            <h3 id="searchH3">Start your search for the perfect babysitter below.</h3>
            <br>
              <!--echoes out the error message for the user-->
            <span id="error"> <?php echo $postErr;?></span>
            <!--creates text box with id so css style code will be applied. echoes the value entered as the value of the 
            txt box so the value entered remains when validating-->
            <input id ="signUpTextbox" type="text" name="postcode" placeholder="Postcode" value="<?php echo $postcode;?>" />            
            <br/>
            <span id="error"> <?php echo $genderErr;?></span><br>
            <label>Select Gender Preference</label>
            <br/>
           <!--creates radio buttons to select the gender. using phph it checks whether there is a value in the gender and
            if it equals the respective value for the radio button so male or felmale if it does then it checks the radio button which matches-->
            <input type="radio" name="gender"  <?php if (isset($gender) && $gender== "female") echo "checked"; ?> value="female"> Female
            <input  type="radio" name="gender"  <?php if (isset($gender)&& $gender== "male") echo "checked"; ?> value="male"> Male

            <br/>
            <span id="error"> <?php echo $ageErr;?></span><br>
            <label>Minimum Age</label> <label> Maximum Age</label> <br>
            <!--creates more txt box inputs-->
            <input id="searchTextbox" type="text" name="minAge" placeholder="Min"  value="<?php echo $minAge;?>"/>
            <input id="searchTextbox" type="text" name="maxAge" placeholder="Max" value="<?php echo $maxAge;?>" />
            <br>
            <span id="error"> <?php echo $distanceErr;?></span><br>
            <label>Select Distance</label><br>
            <!--creates more radio button inputs-->
            <input type="radio" name="distance"  <?php if (isset($distance) && $distance== "15") echo "checked"; ?> value="15"> 15 Mins
            <input  type="radio" name="distance" <?php if (isset($distance) && $distance== "30") echo "checked"; ?>  value="30"> 30 Mins
            <input type="radio" name="distance" <?php if (isset($distance) && $distance== "45") echo "checked"; ?> value="45"> 45 Mins
            <input  type="radio" name="distance" <?php if (isset($distance) && $distance== "60") echo "checked"; ?>  value="60"> 1 Hour
            <input  type="radio" name="distance" <?php if (isset($distance) && $distance== "61") echo "checked"; ?>  value="180"> 1+ Hours
            <br>
            <br>
            <!--creates submit button used to send form-->
            <input  type='submit' name='submit' value='Search'  />
        </fieldset>

    </form>    
</div>
</body>
</html>
