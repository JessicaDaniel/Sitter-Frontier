<?php
    //starts session 
    session_start();

?>

<!DOCTYPE html>
<html lang="en">
<head>
     <!--This gets the style sheet and adds in to the page-->
    <link rel="StyleSheet" href="StyleSheet.css">
    <title>Contact Us</title>
</head>
<body>
    <!--a div which contains the main elements of the page-->
    <div id="mainBody">
        <h1 id="mainH1"> Sitter Frontier</h1>
        <h2 id="mainH2">Leaders of the Sitter March</h2>
         <!--navigation bar so users can easily navigate throught the app-->
        <ul id="navBar">
            <li><a id="navLinks" href="Home.php.php">Home</a></li>
            <li><a id="navLinks" href="AboutUs.php">About Us</a></li>
            <li><a id="navLinks" href="ContactUs.php">Contact Us</a></li>
            <li><a id="navLinks" href="MyAccount.php">My Account</a></li>
             <!--checks to see whether the user is logged in. if they are then the logout button is shown if they are
            not logged in the log in button is shown-->
             <?php  if(isset($_SESSION['username'])):?>
            <li  style="float:right"><a id="navLinks" href="Logout.php">Log Out</a></li>
            <?php else:?>
             <li  style="float:right"><a id="navLinks" href="Login.php">Log In</a></li>
            <?php endif;?>
       </ul>

        <h3 id ="aH3">Contact Us </h3>
     <!--fake latin used between paragraph tags to show the positioning o fht econtact information-->
    <div id="aboutUsTxt">
        <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. 
        Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. 
        Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. 
        Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. 
        Nullam dictum felis eu pede mollis pretium. Integer tincidunt. Cras dapibus. Vivamus elementum semper nisi. 
        Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor eu, consequat vitae, eleifend ac, enim. 
        Aliquam lorem ante, dapibus in, viverra quis, feugiat a, tellus.</p>
        <br>
        <p>Telephone: 01112223334</p>
        <br>
        <p>Fax: 01-617-542-2652</p>
        <br>
        <p>Email: SitterFrontier@Gmail.com</p>
    </div>
    </div>
</body>
</html>
