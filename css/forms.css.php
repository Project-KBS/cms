<?php
// Geef aan dat dit als CSS geintepreteerd moet worden
header('Content-Type: text/css');

// Uit vendor.php zijn we de constanten nodig. (voor kleuren etc)
include_once "../app/vendor.php";
?>

#form-title {
    padding: 1rem 0;
    margin-bottom: 0.75em;
}

.form-main {
    padding: 0.8rem 1.7rem;
    border-radius: 0.4rem;
    background: <?php print(VENDOR_THEME_COLOR_BACKGROUNDL); ?>;
}

#form-footer {
    margin-top: 4.2rem;
}

.form-message {
    width: 100%;
    padding: .8rem 1.95rem !important;
    background-color: <?php echo VENDOR_THEME_COLOR_HIGHLIGHT ?> !important;
    border-color: <?php echo VENDOR_THEME_COLOR_HIGHLIGHT ?> !important;
    margin-bottom: 1.75rem;
}

.form-section-title {
    color: #292929 !important;
    font-size: 120% !important;
    padding: 0.2rem;
}
