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
    height: 40vw;
    margin-left: 10vw;
    margin-top: 5vh;
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

/**
 * Dit is de overkoepelende div van alle producten
 *
 * Bootstrap geeft automatisch display:flex!
 */
#product-sectie {
    /* Element eigenschappen */
    position: relative;

    /* Flexbox eigenschappen */
    flex-wrap: wrap;
    justify-content: start;
    align-items: stretch;
    align-content: start;
}

/**
 * Dit is een div van een product
 *
 * Bootstrap geeft automatisch display:flex!
 */
.product-display {
    /* Element eigenschappen */
    margin: 1rem;
    padding: 0.4rem;
    min-width: 20rem;

    /* Flexbox eigenschappen */
    flex: 1 15%;
    flex-direction: column;
    justify-content: start;
    align-content: start;

    /* Opmaak eigenschappen */
    border: 1px solid #d1d1d1;
}

/**
 * Extra regels die op product-display gelden als de user er met de muis overheen gaat
 */
.product-display:hover {
    /* Opmaak eigenschappen */
    text-decoration: none;
}

/**
 * De div met de productfoto
 */
.product-foto {

}

/**
 * De productfoto
 */
.product-foto img {
    /* Element eigenschappen */
    width: 100%;
}

/**
 * De div met info van het product
 */
.product-beschrijving {
    /* Element eigenschappen */
    margin-top: auto;
    padding-top: 1.4rem;

    /* Opmaak eigenschappen */
    text-align: center;
}

/**
 * De omschrijving van het product
 */
.product-beschrijving p {
    /* Opmaak eigenschappen */
    color: #555;
}

/**
 * De prijs tekst
 */
.product-beschrijving h5 {
    /* Opmaak eigenschappen */
    color: #ff0000;
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
