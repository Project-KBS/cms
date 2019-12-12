<hr>
<h3>Schrijf een review</h3>
<!--var id = document.getElementById('review-container'); if(id.style.display=='none'){id.style.display == 'block'; document.getElementById('jsKnop').style.display == 'none' }-->
<input type="button" onclick="var id = document.getElementById('reviews'); if(id.style.display == 'block'){ id.style.display = 'none';} else{ id.style.display = 'block';}" value="Wilt u een review schrijven?" />

<!-- Dit is de form om een review te schrijven, het is momenteel nog niet opgemaakt en hij doet nog niks-->
<div id="review-container">
    <form action="product.php?id=<?php print($_GET["id"]); ?>" id="reviews" method="post" style="display:none; width: 50vw%; margin-left: 20%">
        <input type="text" name="reviewerName" class="reviewInputs" placeholder="Vul hier je naam in"><br>
        <input type="text" name="title" class="reviewInputs" placeholder="Titel van je review"><br>

        <select name="cijfer" class="reviewInputs">
            <?php
            for($i=1;$i<=10;$i++){
                print("<option value='$i'>$i</option>");
            } ?>
        </select><br>

        <input type="textarea" name="reviewInputs" class="reviewInputs" placeholder="Schrijf hier je review" style="height:20vw;" ><br>
        <input type="submit" name="verzenden" value="verzenden"><br>
    </form>
    <?php

    if(isset($_POST['verzenden'], $_POST['reviewerName'],$_POST['title'], $_POST['reviewInputs'])) {
        $email = "ronaldbijsma@gmail.com";
        $naam = $_POST['reviewerName'];
        $title = $_POST['title'];
        $cijfer = $_POST['cijfer'];
        $reviewInputs = $_POST['reviewInputs'];
        //print_r($_POST);
        Review::insert(Database::getConnection(), $email, $_GET['id'], $title, $reviewInputs, $cijfer);
    }


    ?>
    <hr>
    <h3>Reviews lezen</h3>
    <hr>
    <?php

    //maak een switch om de eerste review te veranderen
    $weergaveswitch=0;

    $stmt = Review::read(Database::getConnection(), $_GET['id']);

    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        //opmaak hard nodig!!
        if($weergaveswitch===0) {
            $weergaveswitch = 1;

            ?>
            <div>
                <h3><?php print($Title); ?></h3><br>
                <h5><?php print($Email); ?></h5><br>
                <h5><?php print($Score); ?></h5><br>
                <p><?php print($Description); ?></p><br>

                <h5><?php print($UpdatedWhen); ?></h5><br>
            </div>
            <?php
        }else{
            ?>
            <div id="weergave">
                <h3><?php print($Title); ?></h3><br>
                <h5><?php print($Email); ?></h5><br>
                <h5><?php print($Score); ?></h5><br>
                <p><?php print($Description); ?></p><br>

                <h5><?php print($UpdatedWhen); ?></h5><br>
            </div>
            <?php
        }
    }
    ?>
    <script>
        function WeergaveSwitch() {
            var x = document.getElementById("weergave");
            if (x.style.display === "none") {
                x.style.display = "block";
                document.getElementById("weergaveknop").innerHTML = "Minder weergeven";
            } else {
                x.style.display = "none";
                document.getElementById("weergaveknop").innerHTML = "Alles weergeven";
            }
        }
        WeergaveSwitch()
    </script>
    <button id="weergaveknop" onclick="WeergaveSwitch()">Alles weergeven</button>
</div>
