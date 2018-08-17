<?php
session_start();
require_once '../../db/database_spletna.php';
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Domaca stran</title>
    </head>
    <body>
        
        <form action="nastavitve.php" method="get">
            <input type="submit" value="Nastavitve">
        </form>
        <form action="../../odjava.php" method="get">
            <input type="submit" value="Odjava">
        </form>
        
        <?php
        // VNOS -- ZASLONSKA MASKA
        // artikel - DONE!!!
        if (isset($_GET["do"]) && $_GET["do"] == "add_artikel"):
            ?>
            <h1>Dodajanje</h1>
            <form action="<?= $_SERVER["PHP_SELF"] ?>" method="post">
                <input type="hidden" name="do" value="add_artikel" />
                <input type="text" name="ime_artikla" placeholder="Ime artikla" /><br />
                <textarea rows="7" cols="20" name="opis_artikla" placeholder="Opis artikla"></textarea><br />
                <!--- maybe TODO!!! - maybe CHANGE CENA TO INPUT TYPE NUMBER --->
                <input type="text" name="cena_artikla" placeholder="Cena v EUR" /><br />
                <input type="submit" value="Shrani" />
            </form>
            <?php
        // stranka - TODO!!!
        elseif (isset($_GET["do"]) && $_GET["do"] == "add_stranka"):
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
        // artikel - TODO!!!
        elseif (isset($_GET["do"]) && $_GET["do"] == "edit_artikel"):
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
        
        // stranka - TODO!!!
        elseif (isset($_GET["do"]) && $_GET["do"] == "edit_stranka"):
            ?>
            <h1>Urejanje</h1>
            <?php
            try {
                $prodajalec = DBSpletna::getUser($_GET["id"])[0]; // POIZVEDBA V PB
                //var_dump($prodajalec);
            } catch (Exception $e) {
                echo "Napaka pri poizvedbi: " . $e->getMessage();
            }

            $id = $prodajalec["id"];
            $ime = $prodajalec["ime"];
            $priimek = $prodajalec["priimek"];
            $email = $prodajalec["email"];
            ?>
            <h2>Urejanje zapisa id = <?= $id ?></h2>
            <form action="<?= $_SERVER["PHP_SELF"] ?>" method="post">
                <input type="hidden" name="id" value="<?= $id ?>" />
                <input type="hidden" name="do" value="edit_ime" />
                <input type="text" name="ime_prodajalca" placeholder="Ime" />
                <input type="submit" value="Spremeni" />
            </form>
            <form action="<?= $_SERVER["PHP_SELF"] ?>" method="post">
                <input type="hidden" name="id" value="<?= $id ?>" />
                <input type="hidden" name="do" value="edit_priimek" />
                <input type="text" name="priimek_prodajalca" placeholder="Priimek" />
                <input type="submit" value="Spremeni" />
            </form>
            <form action="<?= $_SERVER["PHP_SELF"] ?>" method="post">
                <input type="hidden" name="id" value="<?= $id ?>" />
                <input type="hidden" name="do" value="edit_email" />
                <input type="text" name="email_prodajalca" placeholder="Email" />
                <input type="submit" value="Spremeni" />
            </form>
            <form action="<?= $_SERVER["PHP_SELF"] ?>" method="post">
                <input type="hidden" name="id" value="<?= $id ?>" />
                <input type="hidden" name="do" value="edit_geslo" />
                <input type="password" name="geslo_prodajalca" placeholder="Geslo" />
                <input type="submit" value="Spremeni" />
            </form>

            <h2>Izbris prodajalca</h2>
            <form action="<?= $_SERVER["PHP_SELF"] ?>" method="post">
                <input type="hidden" name="id" value="<?= $id ?>" />
                <input type="hidden" name="do" value="delete" />
                <input type="submit" value="Briši" />
            </form>		
            <?php    
        
            
        // POSODABLJANJE ZAPISA V PB
        // dodaj artikel - TODO!!!
        elseif (isset($_POST["do"]) && $_POST["do"] == "add_artikel"):
            ?>
            <h1>Vnašanje zapisa</h1>
            <?php
            try {
                DBSpletna::insertProdajalec($_POST["ime_prodajalca"], $_POST["priimek_prodajalca"], $_POST["email_prodajalca"], password_hash($_POST["geslo_prodajalca"], PASSWORD_DEFAULT));              
                echo "Artikel uspešno dodan! <a href='$_SERVER[PHP_SELF]'>Na prvo stran.</a></p>";
            } catch (Exception $e) {
                echo "<p>Napaka pri dodajanju artikla: {$e->getMessage()}.</p>";
            }

        // edit artikel - TODO!!!
        elseif (isset($_POST["do"]) && $_POST["do"] == "edit_artikel"):
            ?>
            <h1>Obvestilo!</h1>
            <?php
            try {
                DBSpletna::updateRacunStatus($_POST["id"]);
                echo "Artikel uspešno posodobljen. <a href='$_SERVER[PHP_SELF]'>Na prvo stran.</a></p>";
            } catch (Exception $e) {
                echo "<p>Napaka pri potrjevanju: {$e->getMessage()}.</p>";
            }
            
        // dodaj stranko - TODO!!!
        elseif (isset($_POST["do"]) && $_POST["do"] == "add_stranka"):
            ?>
            <h1>Dodajanje stranke</h1>
            <?php
            try {
                DBSpletna::insertProdajalec($_POST["ime_prodajalca"], $_POST["priimek_prodajalca"], $_POST["email_prodajalca"], password_hash($_POST["geslo_prodajalca"], PASSWORD_DEFAULT));              
                echo "Stranka uspešno dodana! <a href='$_SERVER[PHP_SELF]'>Na prvo stran.</a></p>";
            } catch (Exception $e) {
                echo "<p>Napaka pri dodajanju stranke: {$e->getMessage()}.</p>";
            }
        // posodabljanje zapisa IMENA stranke - TODO!!!
        elseif (isset($_POST["do"]) && $_POST["do"] == "edit_ime"):
            ?>
            <h1>Posodobitev zapisa</h1>
            <?php
            try {
                // var_dump($_POST);
                // var_dump($_SESSION["ime_prodajalca"]);
                // die();
                DBSpletna::updateFirstName($_POST["id"], $_POST["ime_prodajalca"]);
                echo "Ime uspešno posodobljeno. <a href='$_SERVER[PHP_SELF]'>Na prvo stran.</a></p>";
            } catch (Exception $e) {
                echo "<p>Napaka pri zapisu: {$e->getMessage()}.</p>";
            }
        // posodabljanje zapisa PRIIMKA stranke - TODO!!!
        elseif (isset($_POST["do"]) && $_POST["do"] == "edit_priimek"):
            ?>
            <h1>Posodobitev zapisa</h1>
            <?php
            try {
                // var_dump($_POST);
                // var_dump($_SESSION["ime_prodajalca"]);
                // die();
                DBSpletna::updateLastName($_POST["id"], $_POST["priimek_prodajalca"]);
                echo "Priimek uspešno posodobljen. <a href='$_SERVER[PHP_SELF]'>Na prvo stran.</a></p>";
            } catch (Exception $e) {
                echo "<p>Napaka pri zapisu: {$e->getMessage()}.</p>";
            }
        // posodabljanje zapisa EMAILA stranke - TODO!!!
        elseif (isset($_POST["do"]) && $_POST["do"] == "edit_email"):
            ?>
            <h1>Posodobitev zapisa</h1>
            <?php
            try {
                // var_dump($_POST);
                // var_dump($_SESSION["ime_prodajalca"]);
                // die();
                DBSpletna::updateEmail($_POST["id"], $_POST["email_prodajalca"]);
                echo "Email uspešno posodobljen. <a href='$_SERVER[PHP_SELF]'>Na prvo stran.</a></p>";
            } catch (Exception $e) {
                echo "<p>Napaka pri zapisu: {$e->getMessage()}.</p>";
            }
        // posodabljanje zapisa GESLA stranke - TODO!!!
        elseif (isset($_POST["do"]) && $_POST["do"] == "edit_geslo"):
            ?>
            <h1>Posodobitev zapisa</h1>
            <?php
            try {
                // var_dump($_POST);
                // var_dump($_SESSION["ime_prodajalca"]);
                // die();
                // password_hash("stranka", PASSWORD_DEFAULT)
                DBSpletna::updatePassword($_POST["id"], password_hash($_POST["geslo_prodajalca"], PASSWORD_DEFAULT));
                echo "Geslo uspešno posodobljeno. <a href='$_SERVER[PHP_SELF]'>Na prvo stran.</a></p>";
            } catch (Exception $e) {
                echo "<p>Napaka pri zapisu: {$e->getMessage()}.</p>";
            }
        // posodabljanje zapisa TELEFONA stranke - TODO!!!
        elseif (isset($_POST["do"]) && $_POST["do"] == "edit_geslo"):
            ?>
            <h1>Posodobitev zapisa</h1>
            <?php
            try {
                // var_dump($_POST);
                // var_dump($_SESSION["ime_prodajalca"]);
                // die();
                // password_hash("stranka", PASSWORD_DEFAULT)
                DBSpletna::updatePassword($_POST["id"], password_hash($_POST["geslo_prodajalca"], PASSWORD_DEFAULT));
                echo "Geslo uspešno posodobljeno. <a href='$_SERVER[PHP_SELF]'>Na prvo stran.</a></p>";
            } catch (Exception $e) {
                echo "<p>Napaka pri zapisu: {$e->getMessage()}.</p>";
            }
        // posodabljanje zapisa NASLOVA stranke - TODO!!!
        elseif (isset($_POST["do"]) && $_POST["do"] == "edit_geslo"):
            ?>
            <h1>Posodobitev zapisa</h1>
            <?php
            try {
                // var_dump($_POST);
                // var_dump($_SESSION["ime_prodajalca"]);
                // die();
                // password_hash("stranka", PASSWORD_DEFAULT)
                DBSpletna::updatePassword($_POST["id"], password_hash($_POST["geslo_prodajalca"], PASSWORD_DEFAULT));
                echo "Geslo uspešno posodobljeno. <a href='$_SERVER[PHP_SELF]'>Na prvo stran.</a></p>";
            } catch (Exception $e) {
                echo "<p>Napaka pri zapisu: {$e->getMessage()}.</p>";
            }

        // edit narocilo - DONE!!!
        elseif (isset($_POST["do"]) && $_POST["do"] == "edit_narocilo"):
            ?>
            <h1>Obvestilo!</h1>
            <?php
            try {
                DBSpletna::updateRacunStatus($_POST["id"]);
                echo "Naročilo uspešno potrjeno. <a href='$_SERVER[PHP_SELF]'>Na prvo stran.</a></p>";
            } catch (Exception $e) {
                echo "<p>Napaka pri potrjevanju: {$e->getMessage()}.</p>";
            }
        
        // zavrni narocilo - DONE!!!
        elseif (isset($_POST["do"]) && $_POST["do"] == "zavrni_narocilo"):
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
            <!--- NAROCILA --->
            <h2>NAROČILA</h2>
            <?php
            try {
                $order = DBSpletna::getAllOrders();
            } catch (Exception $e) {
                echo "Prišlo je do napake: {$e->getMessage()}";
            }
            
            foreach ($order as $num => $row) {
                $url = $_SERVER["PHP_SELF"] . "?do=edit_narocilo&id=" . $row["id"];
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
                        <input type="hidden" name="do" value="edit_narocilo" />
                        <input type="submit" value="Potrdi" />
                    </form>
                    <form action="<?= $url ?>" method="post">
                        <input type="hidden" name="id" value="<?= $row["id"] ?>" />
                        <input type="hidden" name="do" value="zavrni_narocilo" />
                        <input type="submit" value="Zavrni" />
                    </form>
                    <?php
                }
                
            }
            ?>
            <!--- ARTIKLI --->
            <h2>ARTIKLI</h2>
            <h3><a href="<?= $_SERVER["PHP_SELF"] . "?do=add_artikel" ?>">Dodaj artikel</a></h3>
            <?php
            try {
                $artikli = DBSpletna::getAllArticles();
            } catch (Exception $e) {
                echo "Prišlo je do napake: {$e->getMessage()}";
            }
            foreach ($artikli as $num => $row) {
                $url = $_SERVER["PHP_SELF"] . "?do=edit_artikel&id=" . $row["id"];
                $id_artikla = $row["id"];
                $ime_artikla = $row["ime"];
                $opis_artikla = $row["opis"];
                $cena = $row["cena"];
                
                echo "<p>$id_artikla: <b>$ime_artikla</b><br />$opis_artikla<br />" . number_format($cena, 2) . " EUR<br />[<a href='$url'>Uredi</a>]</p>\n";
            }
            ?>
            
            <!--- STRANKE --->
            <h2>STRANKE</h2>
            <h3><a href="<?= $_SERVER["PHP_SELF"] . "?do=add_stranka" ?>">Dodaj stranko</a></h3>
            <?php
            try {
                $stranke = DBSpletna::getAllCustomers();
            } catch (Exception $e) {
                echo "Prišlo je do napake: {$e->getMessage()}";
            }
            foreach ($stranke as $num => $row) {
                $url = $_SERVER["PHP_SELF"] . "?do=edit_stranka&id=" . $row["id"];
                $id_stranke = $row["id"];
                $ime_stranke = $row["ime"];
                $priimek_stranke = $row["priimek"];
                $email_stranke = $row["email"];
                $telefon = $row["telefon"];
                $naslov = $row["naslov"];
                
                echo "<p>$id_stranke: <b>$ime_stranke $priimek_stranke</b><br />$email_stranke<br />$telefon<br />$naslov<br />[<a href='$url'>Uredi</a>]</p>\n";
            }
        endif;
        ?>
    </body>
</html>
