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

.content-container-home {
    width: 80vw;
    margin-left: 10vw;
    margin-top: 5vh;
}

.content-container {
    width: 50%/*vw*/;
    margin-left: 25%/*vw*/;
    margin-top: 5%/*vh*/;
}

/* 404 pagina niet gevonden knop om terug te gaan naar de homepage */
.Big-button{
    width: 500px;
    height: 200px;
    font-size: 40px;
}

.Productphoto{
    width: 100%;
}

.ProductDisplay {
    border: 1px solid black;
    border-radius: 3px;
    padding: 1vw;
    margin-bottom: 2vw;
    background-color: #DFF5F3;
    width: 45vw;
    color: black;
    max-height: 15.25em;
}

.ProductDisplay1 {
    border: 1px solid black;
    border-radius: 3px;
    padding: 1vw;
    background-color: #DFF5F3;
    width: 19vw;
    color: black;
    position: absolute;
    height: 60vh;
}

.ProductDisplay2 {
    border: 1px solid black;
    border-radius: 3px;
    padding: 1vw;
    background-color: #DFF5F3;
    width: 19vw;
    color: black;
    position: absolute;
    margin-left: 20vw;
    height: 60vh;
}

.ProductDisplay3 {
    border: 1px solid black;
    border-radius: 3px;
    padding: 1vw;
    background-color: #DFF5F3;
    width: 19vw;
    color: black;
    position: absolute;
    margin-left: 40vw;
    height: 60vh;
}

.ProductDisplay4 {
    border: 1px solid black;
    border-radius: 3px;
    padding: 1vw;
    background-color: #DFF5F3;
    width: 19vw;
    color: black;
    position: absolute;
    margin-left: 60vw;
    height: 60vh;
}

.ProductDisplay:hover {
    background-color: #D3EBE9;
    color: #020042;
}

.ProductDisplayTop {

}

/* Zorg dat alle afbeeldingen binnen het vakje blijven */
.ProductDisplayTop img {
    width: 100%;
    margin-left: auto;
    margin-right: auto;
}

.ProductDisplayBottom {

}

.ProductDisplayLeft {
    height: 25vh;
}

/* Zorg dat alle afbeeldingen binnen het vakje blijven */
.ProductDisplayLeft img {
    height: 100%;
    margin-left: auto;
    margin-right: auto;
}

.ProductDisplayRight {

}

.ProductDisplayPrice {

}
/*Knoppen voor de productpaginas */


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
