<?php
    $email = Authentication::getEmail();
    if (isset($_GET["id"])) {

        if (isset($_POST['title'], $_POST["cijfer"], $_POST['reviewInputs'])) {

            $title = $_POST['title'];
            $cijfer = $_POST['cijfer'];
            $reviewInputs = $_POST['reviewInputs'];

            Review::insert(Database::getConnection(), $email, $_GET['id'], $title, $reviewInputs, $cijfer);
        }
        ?>

        <hr>

        <div id="review-container">
            <?php
            //Als je bent ingelogd dan laat de pagina de optie zien om een review te schrijven.
            //Anders laat hij een tekst zien die zegt dat je ingelogd moet zijn om een review te schrijven
            if(Authentication::isLoggedIn() === true){
                ?>
            <h3>Schrijf een review</h3>

            <input type="button"
                   onclick="const div_reviews = document.getElementById('reviews');
                    if (div_reviews.style.display === 'block') {
                        div_reviews.style.display = 'none';
                    } else {
                        div_reviews.style.display = 'block';
                    }"
                   value="Wilt u een review schrijven?"
            />
                <!-- Dit is de form om een review te schrijven, het is momenteel nog niet opgemaakt en hij doet nog niks-->

                <form action="product.php?id=<?php print($_GET["id"]); ?>" id="reviews" method="post"
                  style="display:none; width: 50vw%; margin-left: 20%">

                <input type="text"
                       name="title"
                       class="reviewInputs"
                       placeholder="Titel van je review"
                />
                <br>

                <select name="cijfer" class="reviewInputs">
                    <?php

                        for ($i = 1; $i <= 10; $i++) {
                            print("<option value='$i'>$i</option>");
                        }

                    ?>
                </select><br>

                <textarea name="reviewInputs"
                          class="reviewInputs"
                          placeholder="Schrijf hier je review"
                          style="height:20vw;">

                </textarea>

                <br>

                <input type="submit"
                       name="verzenden"
                       value="verzenden">

                <br>
            </form>
            <?php

            //Sluit tag van het if-statement om te checken of je bent ingelogd.
                }
            ?>

            <hr>
            <h3>Reviews lezen</h3>

            <?php

            //maak een counter om aan te geven hoeveel reviews er zijn
            $reviewCount =0;

            $stmt = Review::read(Database::getConnection(), $_GET['id']);

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $reviewCount++;
                extract($row);
                //opmaak hard nodig!!
                if ($reviewCount<3) {

                    ?>
                    <div>
                        <h3><?php print($Title); ?></h3><br>
                        <h5><?php print($Email); ?></h5><br>
                        <h5><?php print($Score); ?></h5><br>
                        <p><?php print($Description); ?></p><br>

                        <h5><?php print($UpdatedWhen); ?></h5><br>
                        <?php
                        if($email===$Email){
                            ?>
                            <div class="row">
                                <div class="col-8"></div>
                                <div class="col-4">
                                    <!-- bewerken moet nog gebeuren -->
                                    <button type="button" href="/edit-review.php">Bewerken</button>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                        <hr>
                    </div>
                    <?php
                } else {

                    ?>
                    <div id="weergave">
                        <h3><?php print($Title); ?></h3><br>
                        <h5><?php print($Email); ?></h5><br>
                        <h5><?php print($Score); ?></h5><br>
                        <p><?php print($Description); ?></p><br>

                        <h5><?php print($UpdatedWhen); ?></h5><br>
                        <?php
                        if($email===$Email){
                            ?>
                            <div class="row">
                                <div class="col-8"></div>
                                <div class="col-4">
                                    <!-- bewerken moet nog gebeuren -->
                                    <button type="button" href="/edit-review.php">Bewerken</button>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                        <hr>
                    </div>
                    <?php
                }
            }
            ?>
            <?php
            if($reviewCount>2){
                ?>
                <script>
                    function WeergaveSwitch() {
                        var x = document.getElementById("weergave");
                        if (x.style.display === "none") {
                            x.style.display = "block";
                            document.getElementById("weergaveknop").innerHTML = "Minder weergeven";
                        } else {
                            x.style.display = "none";
                            document.getElementById("weergaveknop").innerHTML = "Alle <?php print($reviewCount);?> reviews weergeven";
                        }
                    }

                    WeergaveSwitch()
                </script>
                <button id="weergaveknop" onclick="WeergaveSwitch()">Alle <?php print($reviewCount);?> reviews weergeven</button>
                <?php
            }
            ?>
        </div>

        <?php
            }
        ?>
