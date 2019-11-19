<?php
// Geef aan dat dit als CSS geintepreteerd moet worden
header('Content-Type: text/css');

// Uit vendor.php zijn we de constanten nodig. (voor kleuren etc)
include_once "../app/vendor.php";
?>
#knop{
    width: 5vw;
    height: 100%;
    padding: 0;
}
#knop:hover{
    opacity: 15;
}

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

.ProductDisplay{
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

.ProductDisplay:hover{
    background-color: #D3EBE9;
    color: #020042;
}

.ProductDisplayLeft{
    float: left;
    width: 20vw;
}

.ProductDisplayRight{
    float: right;
    width: 25vw;
    height: 35vh;
}

.ProductDisplayPrice{

}
/*Knoppen voor de productpaginas */
button {
    width: 130px;
    height: 40px;
    background: linear-gradient(to bottom, #4eb5e5 0%,#389ed5 100%); /* W3C */
    border: none;
    border-radius: 5px;
    position: relative;
    border-bottom: 4px solid #2b8bc6;
    color: #fbfbfb;
    font-weight: 600;
    font-family: 'Open Sans', sans-serif;
    text-shadow: 1px 1px 1px rgba(0,0,0,.4);
    font-size: 15px;
    text-align: left;
    text-indent: 5px;
    box-shadow: 0px 3px 0px 0px rgba(0,0,0,.2);
    cursor: pointer;

    /* Just for presentation */
    display: block;
    margin: 0 auto;
    margin-bottom: 20px;
}
button:active {
    box-shadow: 0px 2px 0px 0px rgba(0,0,0,.2);
    top: 1px;
}

button:after {
    content: "";
    width: 0;
    height: 0;
    display: block;
    border-top: 20px solid #187dbc;
    border-bottom: 20px solid #187dbc;
    border-left: 16px solid transparent;
    border-right: 20px solid #187dbc;
    position: absolute;
    opacity: 0.6;
    right: 0;
    top: 0;
    border-radius: 0 5px 5px 0;
}

/* Button pointing left */

button.back {
    text-align: right;
    padding-right: 12px;
    box-sizing: border-box;
}
button.back:after {
    content: "";
    width: 0;
    height: 0;
    display: block;
    border-top: 20px solid #187dbc;
    border-bottom: 20px solid #187dbc;
    border-right: 16px solid transparent;
    border-left: 20px solid #187dbc;
    position: absolute;
    opacity: 0.6;
    left: 0;
    top: 0;
    border-radius: 5px 0 0 5px;
}

/* Single buttons */

button.site {
    width: 40px;
    text-align: center;
    text-indent: 0;
}
button.site:after{
    display: none;
}

/* Presentation stuff */
.aantalPaginas {
    width: 50vw;
    background: #efefef;
    padding: 30px 10px;
    box-sizing: border-box;
    margin: 0 auto;
    margin-top: 20px;
    text-align: center;
}
