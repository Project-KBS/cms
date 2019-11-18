<?php
// Geef aan dat dit als CSS geintepreteerd moet worden
header('Content-Type: text/css');

// Uit vendor.php zijn we de constanten nodig. (voor kleuren etc)
include_once "../app/vendor.php";
?>

.content-container {
    width: 50vw;
    margin-left: 25vw;
    margin-top: 5vh;
}

.Big-button{
    width: 500px;
    height: 200px;
    font-size: 40px;
}

#Productphoto{
    width: 100%;
}

.SearchProductDisplay{
    border-style: solid;
    border-color: black;
    border-width: 1px;
    border-radius: 3px;
    padding: 1vw;
    margin-bottom: 2vw;
    background-color: #DFF5F3;
    width: 50vw;
    height: 40vh;
    color: black;
}

.SearchProductDisplay:hover{
    background-color: #D3EBE9;
    color: #020042;
}

.SearchProductDisplayLeft{
    float: left;
    width: 20vw;
}

.SearchProductDisplayRight{
    float: right;
    width: 25vw;
    height: 35vh;
}

.SearchProductDisplayPrice{

}
