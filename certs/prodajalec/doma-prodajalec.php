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
        // stranka - DONE!!!
        elseif (isset($_GET["do"]) && $_GET["do"] == "add_stranka"):
            ?>
            <h1>Dodaj stranke</h1>
            <form action="<?= $_SERVER["PHP_SELF"] ?>" method="post">
                <input type="hidden" name="do" value="add_stranka" />
                <input type="text" name="ime_stranke" placeholder="Ime" /><br />
                <input type="text" name="priimek_stranke" placeholder="Priimek" /><br />
                <input type="text" name="email_stranke" placeholder="Email" /><br />
                <input type="password" name="geslo_stranke" placeholder="Geslo" /><br />
                <input type="text" name="telefon_stranke" placeholder="Telefon" /><br />
                <textarea rows="5" cols="20" name="naslov_stranke" placeholder="Naslov"></textarea><br />
                <input type="submit" value="Dodaj" />
            </form>
            <?php    
            
        // UREJANJE -- ZASLONSKA MASKA
        // artikel - DONE!!!
        elseif (isset($_GET["do"]) && $_GET["do"] == "edit_artikel"):
            ?>
            <h1>Urejanje</h1>
            <?php
            try {
                $artikel = DBSpletna::getArticle($_GET["id"])[0]; // POIZVEDBA V PB
                //var_dump($artikel);
                //die();
            } catch (Exception $e) {
                echo "Napaka pri poizvedbi: " . $e->getMessage();
            }
            $id = $artikel["id"];
            $ime = $artikel["ime"]; 
            $opis = $artikel["opis"];
            $cena = $artikel["cena"];
            $status = $artikel["status"];
            ?>
            <h2>Urejanje zapisa id = <?= $id ?></h2>
            <form action="<?= $_SERVER["PHP_SELF"] ?>" method="post">
                <input type="hidden" name="id" value="<?= $id ?>" />
                <input type="hidden" name="do" value="edit_artikel_ime" />
                <input type="text" name="ime_artikla" value="<?= $ime ?>" />
                <input type="submit" value="Shrani" />
            </form>
            <form action="<?= $_SERVER["PHP_SELF"] ?>" method="post">
                <input type="hidden" name="id" value="<?= $id ?>" />
                <input type="hidden" name="do" value="edit_artikel_opis" />
                <textarea rows="7" cols="20" name="opis_artikla"><?= $opis ?></textarea>
                <input type="submit" value="Shrani" />
            </form>
            <form action="<?= $_SERVER["PHP_SELF"] ?>" method="post">
                <input type="hidden" name="id" value="<?= $id ?>" />
                <input type="hidden" name="do" value="edit_artikel_cena" />
                <!--- maybe TODO!!! - maybe CHANGE CENA TO INPUT TYPE NUMBER --->
                <input type="text" name="cena_artikla" value="<?= $cena ?>" />
                <input type="submit" value="Shrani" />
            </form>

            <h2>Izbris artikla</h2>
            <form action="<?= $_SERVER["PHP_SELF"] ?>" method="post">
                <input type="hidden" name="id" value="<?= $id ?>" />
                <!--- depending on article status, change between "aktiviraj" in "deaktiviraj" --->
                <?php
                    switch ($status) {
                    case "aktiven":
                        $gumb_value = "Deaktiviraj";
                        $gumb_action = "deaktiviraj_artikel";
                        break;
                    case "neaktiven":
                        $gumb_value = "Aktiviraj";
                        $gumb_action = "aktiviraj_artikel";
                        break;
                    default:
                        break;
                    }
                ?>
                <input type="hidden" name="do" value="<?= $gumb_action ?>" />
                <input type="submit" value="<?= $gumb_value ?>" />
            </form>		
            <?php
        
        // stranka - DONE!!!
        elseif (isset($_GET["do"]) && $_GET["do"] == "edit_stranka"):
            ?>
            <h1>Urejanje</h1>
            <?php
            try {
                $stranka = DBSpletna::getUser($_GET["id"])[0]; // POIZVEDBA V PB
                //var_dump($prodajalec);
            } catch (Exception $e) {
                echo "Napaka pri poizvedbi: " . $e->getMessage();
            }

            $id = $stranka["id"];
            $ime = $stranka["ime"];
            $priimek = $stranka["priimek"];
            $email = $stranka["email"];
            $telefon = $stranka["telefon"];
            $naslov = $stranka["naslov"];
            $status = $stranka["status"];
            ?>
            <h2>Urejanje zapisa id = <?= $id ?></h2>
            <form action="<?= $_SERVER["PHP_SELF"] ?>" method="post">
                <input type="hidden" name="id" value="<?= $id ?>" />
                <input type="hidden" name="do" value="edit_ime" />
                <input type="text" name="ime_stranke" value="<?= $ime ?>" />
                <input type="submit" value="Spremeni" />
            </form>
            <form action="<?= $_SERVER["PHP_SELF"] ?>" method="post">
                <input type="hidden" name="id" value="<?= $id ?>" />
                <input type="hidden" name="do" value="edit_priimek" />
                <input type="text" name="priimek_stranke" value="<?= $priimek ?>" />
                <input type="submit" value="Spremeni" />
            </form>
            <form action="<?= $_SERVER["PHP_SELF"] ?>" method="post">
                <input type="hidden" name="id" value="<?= $id ?>" />
                <input type="hidden" name="do" value="edit_email" />
                <input type="text" name="email_stranke" value="<?= $email ?>" />
                <input type="submit" value="Spremeni" />
            </form>
            <form action="<?= $_SERVER["PHP_SELF"] ?>" method="post">
                <input type="hidden" name="id" value="<?= $id ?>" />
                <input type="hidden" name="do" value="edit_geslo" />
                <input type="password" name="geslo_stranke" placeholder="Geslo" />
                <input type="submit" value="Spremeni" />
            </form>
            <form action="<?= $_SERVER["PHP_SELF"] ?>" method="post">
                <input type="hidden" name="id" value="<?= $id ?>" />
                <input type="hidden" name="do" value="edit_telefon" />
                <input type="text" name="telefon_stranke" value="<?= $telefon ?>" />
                <input type="submit" value="Spremeni" />
            </form>
            <form action="<?= $_SERVER["PHP_SELF"] ?>" method="post">
                <input type="hidden" name="id" value="<?= $id ?>" />
                <input type="hidden" name="do" value="edit_naslov" />
                <textarea rows="5" cols="20" name="naslov_stranke"><?= $naslov ?></textarea>
                <input type="submit" value="Spremeni" />
            </form>

            <h2>Izbris prodajalca</h2>
            <form action="<?= $_SERVER["PHP_SELF"] ?>" method="post">
                <input type="hidden" name="id" value="<?= $id ?>" />
                <!--- depending on user account status, change between "aktiviraj" in "deaktiviraj" --->
                <?php
                    switch ($status) {
                    case "aktiven":
                        $gumb_value = "Deaktiviraj";
                        $gumb_action = "deaktiviraj_stranko";
                        break;
                    case "neaktiven":
                        $gumb_value = "Aktiviraj";
                        $gumb_action = "aktiviraj_stranko";
                        break;
                    default:
                        break;
                    }
                ?>
                <input type="hidden" name="do" value="<?= $gumb_action ?>" />
                <input type="submit" value="<?= $gumb_value ?>" />
            </form>		
            <?php    
        
            
        // POSODABLJANJE ZAPISA V PB
        // dodaj artikel - DONE!!!
        elseif (isset($_POST["do"]) && $_POST["do"] == "add_artikel"):
            ?>
            <h1>Vnašanje artikla</h1>
            <?php
            try {
                DBSpletna::insertArticle($_POST["ime_artikla"], $_POST["opis_artikla"], $_POST["cena_artikla"]);              
                echo "Artikel uspešno dodan! <a href='$_SERVER[PHP_SELF]'>Na prvo stran.</a></p>";
            } catch (Exception $e) {
                echo "<p>Napaka pri dodajanju artikla: {$e->getMessage()}.</p>";
            }
        // edit artikel IME - DONE!!!
        elseif (isset($_POST["do"]) && $_POST["do"] == "edit_artikel_ime"):
            ?>
            <h1>Obvestilo!</h1>
            <?php
            try {
                DBSpletna::updateImeArtikla($_POST["id"], $_POST["ime_artikla"]);
                echo "Ime artikla uspešno posodobljeno. <a href='$_SERVER[PHP_SELF]'>Na prvo stran.</a></p>";
            } catch (Exception $e) {
                echo "<p>Napaka pri potrjevanju: {$e->getMessage()}.</p>";
            }
        // edit artikel OPIS - DONE!!!
        elseif (isset($_POST["do"]) && $_POST["do"] == "edit_artikel_opis"):
            ?>
            <h1>Obvestilo!</h1>
            <?php
            try {
                DBSpletna::updateOpisArtikla($_POST["id"], $_POST["opis_artikla"]);
                echo "Opis artikla uspešno posodobljen. <a href='$_SERVER[PHP_SELF]'>Na prvo stran.</a></p>";
            } catch (Exception $e) {
                echo "<p>Napaka pri potrjevanju: {$e->getMessage()}.</p>";
            }
        // edit artikel CENA - DONE!!!
        elseif (isset($_POST["do"]) && $_POST["do"] == "edit_artikel_cena"):
            ?>
            <h1>Obvestilo!</h1>
            <?php
            try {
                DBSpletna::updateCenaArtikla($_POST["id"], $_POST["cena_artikla"]);
                echo "Cena artikla uspešno posodobljena. <a href='$_SERVER[PHP_SELF]'>Na prvo stran.</a></p>";
            } catch (Exception $e) {
                echo "<p>Napaka pri potrjevanju: {$e->getMessage()}.</p>";
            }
        // deaktiviranje artikla - TODO!!!
        elseif (isset($_POST["do"]) && $_POST["do"] == "deaktiviraj_artikel"):
            ?>
            <h1>Deaktiviranje artikla</h1>
            <?php
            try {
                DBSpletna::updateArticleStatus($_POST["id"], "neaktiven");
                $id_str = $_POST["id"];
                echo "Artikel $id_str uspešno deaktiviran. <a href='$_SERVER[PHP_SELF]'>Na prvo stran.</a></p>";
            } catch (Exception $e) {
                echo "<p>Napaka pri deaktiviranju: {$e->getMessage()}.</p>";
            }
        // aktiviranje artikla - TODO!!!
        elseif (isset($_POST["do"]) && $_POST["do"] == "aktiviraj_artikel"):
            ?>
            <h1>Aktiviranje artikla</h1>
            <?php
            try {
                DBSpletna::updateArticleStatus($_POST["id"], "aktiven");
                $id_str = $_POST["id"];
                echo "Artikel $id_str uspešno aktiviran. <a href='$_SERVER[PHP_SELF]'>Na prvo stran.</a></p>";
            } catch (Exception $e) {
                echo "<p>Napaka pri aktiviranju: {$e->getMessage()}.</p>";
            }
            
        // dodaj stranko - DONE!!!
        elseif (isset($_POST["do"]) && $_POST["do"] == "add_stranka"):
            ?>
            <h1>Dodajanje stranke</h1>
            <?php
            try {
                DBSpletna::insertStranka($_POST["ime_stranke"], $_POST["priimek_stranke"], $_POST["email_stranke"], password_hash($_POST["geslo_stranke"], PASSWORD_DEFAULT), $_POST["telefon_stranke"], $_POST["naslov_stranke"]);              
                echo "Stranka uspešno dodana! <a href='$_SERVER[PHP_SELF]'>Na prvo stran.</a></p>";
            } catch (Exception $e) {
                echo "<p>Napaka pri dodajanju stranke: {$e->getMessage()}.</p>";
            }
        // posodabljanje zapisa IMENA stranke - DONE!!!
        elseif (isset($_POST["do"]) && $_POST["do"] == "edit_ime"):
            ?>
            <h1>Posodobitev zapisa</h1>
            <?php
            try {
                DBSpletna::updateFirstName($_POST["id"], $_POST["ime_stranke"]);
                echo "Ime uspešno posodobljeno. <a href='$_SERVER[PHP_SELF]'>Na prvo stran.</a></p>";
            } catch (Exception $e) {
                echo "<p>Napaka pri zapisu: {$e->getMessage()}.</p>";
            }
        // posodabljanje zapisa PRIIMKA stranke - DONE!!!
        elseif (isset($_POST["do"]) && $_POST["do"] == "edit_priimek"):
            ?>
            <h1>Posodobitev zapisa</h1>
            <?php
            try {
                DBSpletna::updateLastName($_POST["id"], $_POST["priimek_stranke"]);
                echo "Priimek uspešno posodobljen. <a href='$_SERVER[PHP_SELF]'>Na prvo stran.</a></p>";
            } catch (Exception $e) {
                echo "<p>Napaka pri zapisu: {$e->getMessage()}.</p>";
            }
        // posodabljanje zapisa EMAILA stranke - DONE!!!
        elseif (isset($_POST["do"]) && $_POST["do"] == "edit_email"):
            ?>
            <h1>Posodobitev zapisa</h1>
            <?php
            try {
                DBSpletna::updateEmail($_POST["id"], $_POST["email_stranke"]);
                echo "Email uspešno posodobljen. <a href='$_SERVER[PHP_SELF]'>Na prvo stran.</a></p>";
            } catch (Exception $e) {
                echo "<p>Napaka pri zapisu: {$e->getMessage()}.</p>";
            }
        // posodabljanje zapisa GESLA stranke - DONE!!!
        elseif (isset($_POST["do"]) && $_POST["do"] == "edit_geslo"):
            ?>
            <h1>Posodobitev zapisa</h1>
            <?php
            try {
                // password_hash("stranka", PASSWORD_DEFAULT)
                DBSpletna::updatePassword($_POST["id"], password_hash($_POST["geslo_stranke"], PASSWORD_DEFAULT));
                echo "Geslo uspešno posodobljeno. <a href='$_SERVER[PHP_SELF]'>Na prvo stran.</a></p>";
            } catch (Exception $e) {
                echo "<p>Napaka pri zapisu: {$e->getMessage()}.</p>";
            }
        // posodabljanje zapisa TELEFONA stranke - DONE!!!
        elseif (isset($_POST["do"]) && $_POST["do"] == "edit_telefon"):
            ?>
            <h1>Posodobitev zapisa</h1>
            <?php
            try {
                DBSpletna::updatePhone($_POST["id"], $_POST["telefon_stranke"]);
                echo "Telefon uspešno posodobljeno. <a href='$_SERVER[PHP_SELF]'>Na prvo stran.</a></p>";
            } catch (Exception $e) {
                echo "<p>Napaka pri zapisu: {$e->getMessage()}.</p>";
            }
        // posodabljanje zapisa NASLOVA stranke - DONE!!!
        elseif (isset($_POST["do"]) && $_POST["do"] == "edit_naslov"):
            ?>
            <h1>Posodobitev zapisa</h1>
            <?php
            try {
                DBSpletna::updateAddress($_POST["id"], $_POST["naslov_stranke"]);
                echo "Naslov uspešno posodobljen. <a href='$_SERVER[PHP_SELF]'>Na prvo stran.</a></p>";
            } catch (Exception $e) {
                echo "<p>Napaka pri zapisu: {$e->getMessage()}.</p>";
            }

        // deaktiviranje stranke - DONE!!!
        elseif (isset($_POST["do"]) && $_POST["do"] == "deaktiviraj_stranko"):
            ?>
            <h1>Deaktiviranje uporabniškega računa stranke</h1>
            <?php
            try {
                DBSpletna::updateUserStatus($_POST["id"], "neaktiven");
                $id_str = $_POST["id"];
                echo "Stranka $id_str uspešno deaktivirana. <a href='$_SERVER[PHP_SELF]'>Na prvo stran.</a></p>";
            } catch (Exception $e) {
                echo "<p>Napaka pri deaktiviranju: {$e->getMessage()}.</p>";
            }
        // aktiviranje stranke - DONE!!!
        elseif (isset($_POST["do"]) && $_POST["do"] == "aktiviraj_stranko"):
            ?>
            <h1>Aktiviranje uporabniškega računa stranke</h1>
            <?php
            try {
                DBSpletna::updateUserStatus($_POST["id"], "aktiven");
                $id_str = $_POST["id"];
                echo "Stranka $id_str uspešno aktivirana. <a href='$_SERVER[PHP_SELF]'>Na prvo stran.</a></p>";
            } catch (Exception $e) {
                echo "<p>Napaka pri aktiviranju: {$e->getMessage()}.</p>";
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
