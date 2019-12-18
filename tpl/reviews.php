<?php
    $email = Authentication::getEmail();
    if (isset($_GET["id"])) {

        // Klopt CSRF-protection token?
        if (isset($_POST["csrf_token"]) && hash_equals($csrf_token, $_POST["csrf_token"])) {

            if (isset($_POST['title'], $_POST["cijfer"], $_POST['reviewInputs']) && strlen(trim($_POST["title"])) > 0 && strlen(trim($_POST["reviewInputs"])) > 0) {

                $title = $_POST['title'];
                $cijfer = $_POST['cijfer'];
                $reviewInputs = $_POST['reviewInputs'];

                if (isset($_POST['edit'])) {
                    Review::update(Database::getConnection(), $email, $_GET['id'], $title, $reviewInputs, $cijfer);
                } elseif (isset($_POST['verzenden'])) {
                    Review::insert(Database::getConnection(), $email, $_GET['id'], $title, $reviewInputs, $cijfer);
                }
            }

        }
    }

        ?>



        <div id="review-container">
        <?php

        //Als je bent ingelogd dan laat de pagina de optie zien om een review te schrijven.
        //Anders laat hij een tekst zien die zegt dat je ingelogd moet zijn om een review te schrijven
        if (Authentication::isLoggedIn() === true) {

            ?>
            <hr>
            <h3>Schrijf een review</h3>

            <input type="button"
                   class="write-review"
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
                  style="display:none; width: 50vw%; margin-left: 20%">

                <input type="text"
                       name="title"
                       class="reviewInputs"
                       placeholder="Titel van je review"
                />

                <br>

                <select name="cijfer"
                        class="reviewInputs">
                    <?php

                    for ($i = 1; $i <= 10; $i++) {
                        print("<option value='$i'>$i</option>");
                    }

                    ?>
                </select>

                <br>

                <textarea name="reviewInputs"
                          class="reviewInputs"
                          placeholder="Schrijf hier je review"
                          style="height: 20vw;">

                    </textarea>

                <br>

                <input type="hidden"
                       name="csrf_token"
                       value="<?php print($csrf_token); ?>"
                />

                <input type="submit"
                       class="write-review"
                       name="verzenden"
                       value="verzenden"
                />

                <br>
            </form>

            <?php

            //Sluit tag van het if-statement om te checken of je bent ingelogd.
        }
        ?>

        <hr>
        <h3>Reviews lezen</h3>
        <hr>

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
                            <h3><?php print($Title); ?></h3>
                            <h5>Score: <?php print($Score); ?></h5>
                            <p><?php print($Description); ?></p>
                            <div class="row">
                                <div class="col-6">
                                    <h5><?php print($UpdatedWhen); ?></h5>
                                </div>
                                <div class="col-6">
                                    <h5><?php print($Email); ?></h5>
                                </div>
                            </div>

                            <br>

                            <?php
                            if ($email === $Email) {
                                ?>
                                <div class="row">

                                    <div class="col-4">

                                    </div>

                                    <div class="col-4 bewerken-button-div">

                                        <a class="bewerken-button" href="/edit-review.php?id=<?php print($_GET["id"]); ?>">
                                            Bewerken
                                        </a>

                                    </div>

                                    <div class="col-4">

                                        <form id="delete-review" method="POST">

                                            <input type="submit"
                                                   class="delete-review"
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
