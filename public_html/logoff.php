<?php

if (session_id() == "") 
    session_start();

$user = $_GET['user'];

if ($user == 'customer'){
    
    if (!empty($_SESSION['loggedIn']) && ($_SESSION['loggedIn'] == 1) && !empty($_SESSION['email']) ) {

        unset($_SESSION['loggedIn']);
        unset($_SESSION['email']);

        
        session_destroy();

    }
}
else if ($user == 'admin'){

    if (!empty($_SESSION['loggedIn']) && ($_SESSION['loggedIn'] == 1) && !empty($_SESSION['email']) && !empty($_SESSION['isAdmin']) && ($_SESSION['isAdmin'] == 1)) {

        unset($_SESSION['loggedIn']);
        unset($_SESSION['email']);
        unset($_SESSION['isAdmin']);

        
        session_destroy();
    }
}

header('location: home.php');

?>
