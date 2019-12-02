<?php
// Geef aan dat dit als CSS geintepreteerd moet worden
header('Content-Type: text/css');

// Uit vendor.php zijn we de constanten nodig. (voor kleuren etc)
include_once "../app/vendor.php";
?>

#knop {
    width: 5vw;
    height: 100%;
    padding: 0;
}

#knop:hover {
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
    width: 25vw;
    height: 20vh;
    font-size: 2vw;
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
    flex-basis: 0;
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
    max-width: 25vw;

    /* Flexbox eigenschappen */
    flex: 1 15%;
    flex-direction: column;
    justify-content: start;
    align-content: start;

    /* Opmaak eigenschappen */
    border: 1px solid #d1d1d1;
}

/**
 * Extra regels die op onderliggende items van het product-display gelden als de user er met de muis overheen gaat
 */
.product-display *:hover {
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
    color: <?php echo VENDOR_THEME_COLOR_HIGHLIGHT ?>;
    align-self: flex-end;
}

/**
 * Dit zorgt er voor dat de omhoog/omlaag knoppen bij nummer input velden altijd zichtbaar zijn
 */
input[type=number]::-webkit-inner-spin-button,
input[type=number]::-webkit-outer-spin-button {
    opacity: 1;
}

/* Presentation stuff */
.aantalPaginas {
    width: 50vw;
    background: <?php echo VENDOR_THEME_COLOR_BACKGROUND ?>;
    padding: 30px 10px;
    box-sizing: border-box;
    margin: 20px auto 0;
    text-align: center;
}
.WinkelwagenKnop{
    width: 100%;
    height: 3vw;
    align-content: center;
    align-self: flex-end;
}

.ProductMand{
    margin: 5%;
    width: 60%;
}

.ContinueButton{
    background-color: #24b51f;
    color: white;
    border-radius: 1em;
    padding: 10%;
    text-align: center;
    font-weight: bold;
    font-family: "Tw Cen MT";
    width: 200%;
    text-decoration-color: white;
}

.ContinueButton:hover{
    background-color: #1d9c19;
    color: white;
    border-radius: 1em;
    padding: 10%;
    text-align: center;
    font-weight: bold;
    font-family: "Tw Cen MT";
    width: 200%;
}

.FormLabels{
    text-align: right;
}

#photo{
    width: 100%;
}

.centerDivText {
    margin-top: 6%;
}

.iDealLogo{
    max-width: 100%;
}

.lineTop{
    border-top-color: black;
    border-top-width: 1px;
    border-top-style: solid;
    padding-top: 2%;
}
