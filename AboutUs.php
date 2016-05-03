<?php
    
    //includes the file where the functions needed are.  
    include_Once('Functions.php');
    // starts a session 
    session_start();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!--This gets the style sheet and adds in to the page-->
    <link rel="StyleSheet" href="StyleSheet.css">
    <title>About Us</title>
</head>

<body> 
    <!--a div which contains the main elements of the page-->
    <div id="mainBody">
        <h1 id="mainH1"> Sitter Frontier</h1>
        <h2 id="mainH2">Leaders of the Sitter March</h2>
        <!--navigation bar so users can easily navigate throught the app-->
        <ul id="navBar">
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
    <!-- gets images that will be used in the slideshow from the sitter_frontier_images folder-->
    <div  id ="slideshow">
    <img class="aboutUs" src="Sitter_Frontier_Images/image1.jpg" style="width:100%" alt="Sitter Frontier">
    <img class="aboutUs" src="Sitter_Frontier_Images/image2.jpg" style="width:100%" alt="Sitter Frontier">
    <img class="aboutUs" src="Sitter_Frontier_Images/image3.jpg" style="width:100%" alt="Sitter Frontier">
    <img class="aboutUs" src="Sitter_Frontier_Images/image4.jpg" style="width:100%" alt="Sitter Frontier">
    </div>
    <!--calls the slideshow function which will show the images above as a slide show-->
   <script>
       var myIndex = 0;
       SlideShow();
   </script>
    <h3 id ="aH3">About Us </h3>
    <!--fake latin used to simulate where the text for the contact us page will go-->
    <div id="aboutUsTxt">
        <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. 
        Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. 
        Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. 
        Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. 
        Nullam dictum felis eu pede mollis pretium. Integer tincidunt. Cras dapibus. Vivamus elementum semper nisi. 
        Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor eu, consequat vitae, eleifend ac, enim. 
        Aliquam lorem ante, dapibus in, viverra quis, feugiat a, tellus.</p>
        <br>
        <p>Phasellus viverra nulla ut metus varius laoreet. Quisque rutrum. Aenean imperdiet. 
        Etiam ultricies nisi vel augue. Curabitur ullamcorper ultricies nisi. Nam eget dui. 
        Etiam rhoncus. Maecenas tempus, tellus eget condimentum rhoncus, sem quam semper libero, sit amet adipiscing sem neque sed ipsum. 
        Nam quam nunc, blandit vel, luctus pulvinar, hendrerit id, lorem. 
        Maecenas nec odio et ante tincidunt tempus. Donec vitae sapien ut libero venenatis faucibus. 
        Nullam quis ante. Etiam sit amet orci eget eros faucibus tincidunt. Duis leo. 
        Sed fringilla mauris sit amet nibh. Donec sodales sagittis magna. 
        Sed consequat, leo eget bibendum sodales, augue velit cursus nunc, quis gravida magna mi a libero. 
        Fusce vulputate eleifend sapien.</p>
    </div>
</div>
</body>
</html>