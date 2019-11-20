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
    width: 50vw;
    height: 40vh;
    color: black;

    max-height: 15.25em;
}

.ProductDisplay:hover {
    background-color: #D3EBE9;
    color: #020042;
}

.ProductDisplayLeft {
    height: 100%;
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
