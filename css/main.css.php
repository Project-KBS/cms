<?php
// Geef aan dat dit als CSS geintepreteerd moet worden
header('Content-Type: text/css');

// Uit vendor.php zijn we de constanten nodig. (voor kleuren etc)
include_once "../app/vendor.php";
?>

.content-container {
    width: 35vw;
    margin-left: 30vw;
    margin-top: 5vh;
}

.Big-button{
    width: 500px;
    height: 200px;
    font-size: 40px;
}
