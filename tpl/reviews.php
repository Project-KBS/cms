<?php
    $email = Authentication::getEmail();
    if (isset($_GET["id"])) {

        // Klopt CSRF-protection token?
        if (isset($_POST["csrf_token"]) && hash_equals($csrf_token, $_POST["csrf_token"])) {

            if (isset($_POST['title'], $_POST["cijfer"], $_POST['reviewInputs']) && strlen(trim($_POST["title"])) > 0 && strlen(trim($_POST["reviewInputs"])) > 0) {

                $title = $_POST['title'];
                $cijfer = $_POST['cijfer'];
                $reviewInputs = $_POST['reviewInputs'];

                // TODO error handling hiervoor
                try {
                    if (isset($_POST['edit'])) {
                        Review::update(Database::getConnection(), $email, $_GET['id'], $title, $reviewInputs, $cijfer);
                    } elseif (isset($_POST['verzenden'])) {
                        Review::insert(Database::getConnection(), $email, $_GET['id'], $title, $reviewInputs, $cijfer);
                    }
                } catch (Exception $ignored) {
                    // Print de error als debugging aan staat
                    if (IS_DEBUGGING_ENABLED) {
                        printf("Error: %s", $ignored->getMessage());
                    }
                }
            }

        }
    }

        ?>



        <div id="review-container">

            <hr>
            <h3>Schrijf een review</h3>
        <?php

        //Als je bent ingelogd dan laat de pagina de optie zien om een review te schrijven.
        //Anders laat hij een tekst zien die zegt dat je ingelogd moet zijn om een review te schrijven
        if (Authentication::isLoggedIn()) {

            if (isset($_GET["id"]) && (Review::readOne(Database::getConnection(), $_GET['id'], Authentication::getEmail()))->rowCount() > 0) {

                ?>

                <input type="button"
                       class="btn btn-warning w-100"
                       style="margin: 2rem 0"
                       value="U hebt al een review geschreven voor dit product."
                />

                <?php

            } else {

                ?>

                <input type="button"
                       class="btn btn-primary bootstrap-btn"
                       style="margin: 2rem 0"
                       onclick="const div_reviews = document.getElementById('reviews');
                                if (div_reviews.style.display === 'block') {
                                    div_reviews.style.display = 'none';
                                } else {
                                    div_reviews.style.display = 'block';
                                }"
                       value="Wilt u een review schrijven?"
                />
                <!-- Dit is de form om een review te schrijven, het is momenteel nog niet opgemaakt en hij doet nog niks-->

                <form action="product.php?id=<?php print($_GET["id"]); ?>"
                      id="reviews"
                      method="post"
                      style="display:none; width: 100%; padding: 1.7rem; border-radius: 0.4rem; background: <?php print(VENDOR_THEME_COLOR_BACKGROUNDL); ?>;">

                    <small id="emailHelp"
                           class="form-text text-muted form-section-title"
                           style="color: #292929 !important;">

                        Omschrijf jouw ervaring in een paar woorden:
                    </small>

                    <input type="text"
                           name="title"
                           class="reviewInputs form-control form-control-lg"
                           style="width: 100%"
                           placeholder="Titel van je review"
                    />

                    <br>

                    <small id="emailHelp"
                           class="form-text text-muted form-section-title"
                           style="color: #292929 !important;">

                        Hoe zou je het product aanbevelen op de schaal van 1 tot 10?
                    </small>

                    <select name="cijfer"
                            class="reviewInputs form-control form-control-lg"
                            style="width: 100%">
                        <?php

                            for ($i = 1; $i <= 10; $i++) {
                                print("<option value='$i'>$i</option>");
                            }

                        ?>
                    </select>

                    <br>

                    <small id="emailHelp"
                           class="form-text text-muted form-section-title"
                           style="color: #292929 !important;">

                        Vat je ervaring met het product samen in een kleine tekst:
                    </small>

                    <textarea name="reviewInputs"
                              class="reviewInputs form-control form-control-lg"
                              placeholder="Schrijf hier je review"
                              style="height: 15vw"></textarea>

                    <br>

                    <input type="hidden"
                           name="csrf_token"
                           value="<?php print($csrf_token); ?>"
                    />

                    <input type="submit"
                           class="btn btn-primary bootstrap-btn"
                           style="width: 100%"
                           name="verzenden"
                           value="Verzenden"
                    />

                    <br>
                </form>

                <?php
            }

            //Sluit tag van het if-statement om te checken of je bent ingelogd.
        } else {

            ?>

                <input type="button"
                       class="btn btn-secondary w-100"
                       style="margin: 2rem 0"
                       value="Log in om een review te schrijven"
                       onclick="window.location='inloggen.php';"
                />

            <?php

        }
        ?>

        <hr>
        <h3>Reviews lezen</h3>

        <?php


        //maak een counter om aan te geven hoeveel reviews er zijn
        $reviewCount = 0;

        $stmt = Review::read(Database::getConnection(), $_GET['id']);

        //zet een limit voor reviews als die nog niet bestaat
        if (!isset($_POST["defaultLimit"])) {
            $_POST["defaultLimit"] = 1;
        }
        $defaultLimit = $_POST["defaultLimit"];
        //Als er meer dan 1 row kan worden opgehaald, laadt hij de geschreven reviews
        if ($stmt->rowCount() > 0) {

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                    $reviewCount++;
                    extract($row);

                    //opmaak hard nodig!!
                    if ($reviewCount <= $defaultLimit) {

                        ?>
                        <div>

                            <div class="review-entry-view form-main">

                                <h3 class="mt-2">
                                    "<?php print($Title); ?>"
                                </h3>

                                <h5 class="text-dark">
                                    <?php print($Email); ?> beoordeelde dit product met een <?php print($Score); ?>.0
                                </h5>

                                <hr>

                                <p class="mt-4">
                                    <?php print($Description); ?>
                                </p>

                                <p class="mt-4 text-muted">
                                    Laatst geüpdatet op <?php print($UpdatedWhen); ?>
                                </p>

                            </div>

                            <br>

                            <?php
                            if ($email === $Email) {
                                ?>
                                <div class="row">

                                    <div class="col-8">

                                    </div>

                                    <div class="col-2 bewerken-button-div">

                                        <a class="btn btn-primary bootstrap-btn" href="/edit-review.php?id=<?php print($_GET["id"]); ?>">
                                            Bewerken
                                        </a>

                                    </div>

                                    <div class="col-2">

                                        <form id="delete-review" method="POST" class="w-100">

                                            <input type="submit"
                                                   class="btn btn-danger w-100"
                                                   name="delete-review"
                                                   value="Verwijderen"
                                            />

                                            <input type="hidden"
                                                   name="csrf_token"
                                                   value="<?php print($csrf_token); ?>"
                                            />

                                        </form>

                                        <?php

                                        $currentProductId = intval($_GET["id"]);

                                        //Als je op de verwijder knop drukt, voert hij de delete functie uit
                                        if (isset($_POST['delete-review']) && isset($_POST["csrf_token"]) && hash_equals($csrf_token, $_POST["csrf_token"])) {

                                            Review::delete(Database::getConnection(), $currentProductId, Authentication::getEmail());

                                            // Refresh de pagina om alle reviews te updaten
                                            header("Location: product.php?id=$currentProductId");
                                            printf('<meta http-equiv="refresh" content="0;product.php?id=%d">
                                                               <script type="text/javascript">
                                                                   window.location = "product.php?id=%d";
                                                               </script>', $currentProductId, $currentProductId);
                                        }

                                        ?>

                                    </div>

                                </div>

                                <?php
                            }
                            ?>

                            <hr>

                        </div>
                        <?php
                    } else {
                        if ($reviewCount > $defaultLimit) {
                            ?>

                            <!-- Geen CSRF-protectie omdat het een selector form is. -->
                            <form action="" method="post">
                                <input type="hidden" name="defaultLimit"
                                       value="<?php print($_POST["defaultLimit"] + 5); ?>">
                                <input type="submit" class="meer-weergeven" value="meer weergeven">
                            </form>
                            </div>
                            <?php
                        }
                        break 1;
                    }
                }
        //Als er 0 rows kunnen worden opgehaald laat hij het onderstaande zien.
        }else{
            print("Er zijn nog geen reviews geschreven voor dit product");
        }

    ?>
