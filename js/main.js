/* --- Deze code zorgt er voor dat de navigatiebalk blijft "plakken" aan de bovenkant van het scherm */
document.addEventListener("DOMContentLoaded", function () {

    /**
     * Functie om de offset van element naar pagina top in pixels te bepalen
     */
    const getOffsetTop = element => {
        let offsetTop = 0;
        while (element) {
            offsetTop += element.offsetTop;
            element = element.offsetParent;
        }
        return offsetTop;
    };

    // De balk met categorieeen.
    const navigatiebalk   = document.getElementById("navigatie-categorieen");
    // De bovenste balk met het WWI-logo.
    const headerbalk      = document.getElementById("header-inline");
    // De offset in pixels vanaf de bovenkant van de pagina naar de categoriebalk.
    const standaardOffset = getOffsetTop(navigatiebalk);

    /**
     * Deze functie wordt gecalled als de user scrollt (zie window.onscroll).
     */
    function onNavScroll() {

        if (window.pageYOffset >= standaardOffset) {

            // Zorg dat de categoriebalk blijft 'hangen' bovenaan het scherm
            navigatiebalk.classList.add("stick");

            // Omdat de categoriebalk op "position:fixed" wordt gezet, wordt de header minder groot!!!
            // Voorkom dit door een extra padding (genaamd "filler") toe te voegen.
            headerbalk.classList.add("stick-filler");

        } else {
            // Revert de wijzigingen die hierboven zijn gemaakt
            navigatiebalk.classList.remove("stick");
            headerbalk.classList.remove("stick-filler");
        }
    }

    // Elke keer dat de gebruiker scrollt wordt de onNavScroll() functie opgeroepen
    window.onscroll = function() {
        onNavScroll()
    };

});
/* --- */

