<?php
require_once '../../db/database_spletna.php';
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Primer z bazo in knjižnico PDO</title>
    </head>
    <body>
        <?php /*
        // VNOS -- ZASLONSKA MASKA
        if (isset($_GET["do"]) && $_GET["do"] == "add"):
            ?>
            <h1>Dodajanje</h1>
            <form action="<?= $_SERVER["PHP_SELF"] ?>" method="post">
                <input type="hidden" name="do" value="add" />
                Datum: <input type="text" name="joke_date" value="<?= date("Y-m-d") ?>" /><br />
                <textarea rows="8" cols="60" name="joke_text"></textarea><br />
                <input type="submit" value="Shrani" />
            </form>
            <?php
        // UREJANJE -- ZASLONSKA MASKA
        elseif (isset($_GET["do"]) && $_GET["do"] == "edit"):
            ?>
            <h1>Urejanje</h1>
            <?php
            try {
                $joke = DBJokes::get($_GET["id"]); // POIZVEDBA V PB
                $order = DBSpletna::getAllOrders();
            } catch (Exception $e) {
                echo "Napaka pri poizvedbi GET ALL ORDERS: " . $e->getMessage();
            }
            $id = $order["id"];
            $postavka = $order["postavka"]; 
            $status = $order["status"];
            $id_stranka = $order["id_stranka"];
            
            $date = $joke["joke_date"];
            $text = $joke["joke_text"];
            ?>
            <h2>Urejanje zapisa id = <?= $id ?></h2>
            <form action="<?= $_SERVER["PHP_SELF"] ?>" method="post">
                <input type="hidden" name="id" value="<?= $id ?>" />
                <input type="hidden" name="do" value="edit" />
                Datum: <input type="text" name="joke_date" value="<?= $date ?>" /><br />
                <textarea rows="8" cols="60" name="joke_text"><?= $text ?></textarea><br />
                <input type="submit" value="Shrani" />
            </form>

            <h2>Izbris zapisa</h2>
            <form action="<?= $_SERVER["PHP_SELF"] ?>" method="post">
                <input type="hidden" name="id" value="<?= $id ?>" />
                <input type="hidden" name="do" value="delete" />
                <input type="submit" value="Briši" />
            </form>		
            <?php
         */
        // POSODABLJANJE ZAPISA V PB
        if (isset($_POST["do"]) && $_POST["do"] == "edit"):
            ?>
            <h1>Obvestilo!</h1>
            <?php
            try {
                DBSpletna::updateRacunStatus($_POST["id"]);
                echo "Šala uspešno posodobljena. <a href='$_SERVER[PHP_SELF]'>Na prvo stran.</a></p>";
            } catch (Exception $e) {
                echo "<p>Napaka pri potrjevanju: {$e->getMessage()}.</p>";
            }
        elseif (isset($_POST["do"]) && $_POST["do"] == "zavrni"):
            ?>
            <h1>Obvestilo!</h1>
            <?php
            try {
                DBSpletna::updateRacunStatus2($_POST["id"]);
                echo "Naročilo je bilo zavrnjeno!. <a href='$_SERVER[PHP_SELF]'>Na prvo stran.</a></p>";
            } catch (Exception $e) {
                echo "<p>Napaka pri zavrnitvi računa!: {$e->getMessage()}.</p>";
            }
        // PRIKAZ VSEH ZAPISOV
        else:
            ?>
            <h1>Vsa naročila</h1>
            <h2><a href="<?= $_SERVER["PHP_SELF"] . "?do=add" ?>">Pregled vseh naročil</a></h2>
            <?php
            try {
                $order = DBSpletna::getAllOrders();
            } catch (Exception $e) {
                echo "Prišlo je do napake: {$e->getMessage()}";
            }
            
            foreach ($order as $num => $row) {
                $url = $_SERVER["PHP_SELF"] . "?do=edit&id=" . $row["id"];
                $postavka = $row["postavka"];
                $status = $row["status"];
                $stranka_id = $row["stranka_id"];
                if ($status == "neobdelano") {
                    echo "<p><b>STRANKA $stranka_id</b>:</p>\n";
                    echo "Vrednost naročila: $postavka EUR </p>\n";
                    echo "<p>$status $stranka_id </br></p>\n"; // [<a href='$url'>Potrdi račun</a>]</br></p>\n";
                    ?>
                    <form action="<?= $url ?>" method="post">
                        <input type="hidden" name="id" value="<?= $row["id"] ?>" />
                        <input type="hidden" name="do" value="edit" />
                        <input type="submit" value="Potrdi" />
                    </form>
                    <form action="<?= $url ?>" method="post">
                        <input type="hidden" name="id" value="<?= $row["id"] ?>" />
                        <input type="hidden" name="do" value="zavrni" />
                        <input type="submit" value="Zavrni" />
                    </form>
                    <?php
                }
                
            }
        endif;
        ?>
    </body>
</html>
