<?php
session_start();
require_once '../../db/database_spletna.php';
?>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="../../css/style.css">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Domaca stran</title>
    </head>
    <body>
        
        <form action="../../odjava.php" method="get">
            <input type="submit" value="Odjava">
        </form>
        
        <?php
        
        $url = filter_input(INPUT_SERVER, "PHP_SELF", FILTER_SANITIZE_SPECIAL_CHARS);
        // var_dump($url);
        $validationRules = ['do' => [
                'filter' => FILTER_VALIDATE_REGEXP,
                'options' => [
                    "regexp" => "/^(add_artikel|add_stranka|edit_artikel|edit|add_artikel|edit_artikel_ime|edit_artikel_opis|edit_artikel_cena|deaktiviraj_artikel|aktiviraj_artikel|add_stranka|edit_ime|edit_priimek|edit_email|edit_geslo|edit_telefon|edit_naslov|deaktiviraj_stranko|aktiviraj_stranko|edit_narocilo|zavrni_narocilo|show|storno|storniraj)$/"
                ]
            ],
            'id' => [
                'filter' => FILTER_VALIDATE_INT,
                'options' => ['min_range' => 0]
            ],
            'kolicina' => [
                'filter' => FILTER_VALIDATE_INT,
                'options' => ['min_range' => 0]
            ],
            'ime_stranke' => [
                'filter' => FILTER_SANITIZE_STRING
            ],
            'priimek_stranke' => [
                'filter' => FILTER_SANITIZE_STRING
            ],
            'email_stranke' => [
                'filter' => FILTER_SANITIZE_EMAIL
            ],
            'geslo_stranke' => [
                'filter' => FILTER_SANITIZE_STRING
            ],
            'telefon_stranke' => [
                'filter' => FILTER_SANITIZE_STRING
            ],
            'naslov_stranke' => [
                'filter' => FILTER_SANITIZE_STRING
            ],
            'ime_artikla' => [
                'filter' => FILTER_SANITIZE_STRING
            ],
            'opis_artikla' => [
                'filter' => FILTER_SANITIZE_STRING
            ],
            'cena_artikla' => [
                'filter' => FILTER_SANITIZE_STRING
            ]

        ];
        $data = filter_input_array(INPUT_POST, $validationRules);
        // var_dump($data);

        $data_get = filter_input_array(INPUT_GET, $validationRules);
        // var_dump($data_get);
        // var_dump($_SESSION);
        
        // VNOS -- ZASLONSKA MASKA
        // artikel - DONE!!!
        if (isset($data_get["do"]) && $data_get["do"] == "add_artikel"):
            ?>
            <h1>Dodajanje</h1>
            <form action="<?= $url ?>" method="post">
                <input type="hidden" name="do" value="add_artikel" />
                <input type="text" name="ime_artikla" placeholder="Ime artikla" /><br />
                <textarea rows="7" cols="20" name="opis_artikla" placeholder="Opis artikla"></textarea><br />
                <!--- maybe TODO!!! - maybe CHANGE CENA TO INPUT TYPE NUMBER --->
                <input type="text" name="cena_artikla" placeholder="Cena v EUR" /><br />
                <input type="submit" value="Shrani" />
            </form>
            <?php
        // stranka - DONE!!!
        elseif (isset($data_get["do"]) && $data_get["do"] == "add_stranka"):
            ?>
            <h1>Dodaj stranke</h1>
            <form action="<?= $url ?>" method="post">
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
        elseif (isset($data_get["do"]) && $data_get["do"] == "edit_artikel"):
            ?>
            <h1>Urejanje</h1>
            <?php
            try {
                $artikel = DBSpletna::getArticle($data_get["id"])[0]; // POIZVEDBA V PB
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
            <form action="<?= $url ?>" method="post">
                <input type="hidden" name="id" value="<?= $id ?>" />
                <input type="hidden" name="do" value="edit_artikel_ime" />
                <input type="text" name="ime_artikla" value="<?= $ime ?>" />
                <input type="submit" value="Shrani" />
            </form>
            <form action="<?= $url ?>" method="post">
                <input type="hidden" name="id" value="<?= $id ?>" />
                <input type="hidden" name="do" value="edit_artikel_opis" />
                <textarea rows="7" cols="20" name="opis_artikla"><?= $opis ?></textarea>
                <input type="submit" value="Shrani" />
            </form>
            <form action="<?= $url ?>" method="post">
                <input type="hidden" name="id" value="<?= $id ?>" />
                <input type="hidden" name="do" value="edit_artikel_cena" />
                <!--- maybe TODO!!! - maybe CHANGE CENA TO INPUT TYPE NUMBER --->
                <input type="text" name="cena_artikla" value="<?= $cena ?>" />
                <input type="submit" value="Shrani" />
            </form>

            <h2>Izbris artikla</h2>
            <form action="<?= $url ?>" method="post">
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
        elseif (isset($data_get["do"]) && $data_get["do"] == "edit"):
            ?>
            <h1>Urejanje</h1>
            <?php
            try {
                $stranka = DBSpletna::getUser($data_get["id"])[0]; // POIZVEDBA V PB
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
            <form action="<?= $url ?>" method="post">
                <input type="hidden" name="id" value="<?= $id ?>" />
                <input type="hidden" name="do" value="edit_ime" />
                <input type="text" name="ime_stranke" value="<?= $ime ?>" />
                <input type="submit" value="Spremeni" />
            </form>
            <form action="<?= $url ?>" method="post">
                <input type="hidden" name="id" value="<?= $id ?>" />
                <input type="hidden" name="do" value="edit_priimek" />
                <input type="text" name="priimek_stranke" value="<?= $priimek ?>" />
                <input type="submit" value="Spremeni" />
            </form>
            <form action="<?= $url ?>" method="post">
                <input type="hidden" name="id" value="<?= $id ?>" />
                <input type="hidden" name="do" value="edit_email" />
                <input type="text" name="email_stranke" value="<?= $email ?>" />
                <input type="submit" value="Spremeni" />
            </form>
            <form action="<?= $url ?>" method="post">
                <input type="hidden" name="id" value="<?= $id ?>" />
                <input type="hidden" name="do" value="edit_geslo" />
                <input type="password" name="geslo_stranke" placeholder="Geslo" />
                <input type="submit" value="Spremeni" />
            </form>
            <?php 
                if (isset($_SESSION["uporabnik_id"]) && $_SESSION["uporabnik_id"] != $id) { ?>
                    <form action="<?= $url ?>" method="post">
                        <input type="hidden" name="id" value="<?= $id ?>" />
                        <input type="hidden" name="do" value="edit_telefon" />
                        <input type="text" name="telefon_stranke" value="<?= $telefon ?>" />
                        <input type="submit" value="Spremeni" />
                    </form>
                    <form action="<?= $url ?>" method="post">
                        <input type="hidden" name="id" value="<?= $id ?>" />
                        <input type="hidden" name="do" value="edit_naslov" />
                        <textarea rows="5" cols="20" name="naslov_stranke"><?= $naslov ?></textarea>
                    <input type="submit" value="Spremeni" />
                    </form>
                    <h2>Izbris prodajalca</h2>
                    <form action="<?= $url ?>" method="post">
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
                <?php }
            ?>		
            <?php    
        
            
        // POSODABLJANJE ZAPISA V PB
        // dodaj artikel - DONE!!!
        elseif (isset($data["do"]) && $data["do"] == "add_artikel"):
            ?>
            <h1>Vnašanje artikla</h1>
            <?php
            try {
                DBSpletna::insertArticle($data["ime_artikla"], $data["opis_artikla"], $data["cena_artikla"]);              
                echo "Artikel uspešno dodan! <a href='$url'>Na prvo stran.</a></p>";
            } catch (Exception $e) {
                echo "<p>Napaka pri dodajanju artikla: {$e->getMessage()}.</p>";
            }
        // edit artikel IME - DONE!!!
        elseif (isset($data["do"]) && $data["do"] == "edit_artikel_ime"):
            ?>
            <h1>Obvestilo!</h1>
            <?php
            try {
                DBSpletna::updateImeArtikla($data["id"], $data["ime_artikla"]);
                echo "Ime artikla uspešno posodobljeno. <a href='$url'>Na prvo stran.</a></p>";
            } catch (Exception $e) {
                echo "<p>Napaka pri potrjevanju: {$e->getMessage()}.</p>";
            }
        // edit artikel OPIS - DONE!!!
        elseif (isset($data["do"]) && $data["do"] == "edit_artikel_opis"):
            ?>
            <h1>Obvestilo!</h1>
            <?php
            try {
                DBSpletna::updateOpisArtikla($data["id"], $data["opis_artikla"]);
                echo "Opis artikla uspešno posodobljen. <a href='$url'>Na prvo stran.</a></p>";
            } catch (Exception $e) {
                echo "<p>Napaka pri potrjevanju: {$e->getMessage()}.</p>";
            }
        // edit artikel CENA - DONE!!!
        elseif (isset($data["do"]) && $data["do"] == "edit_artikel_cena"):
            ?>
            <h1>Obvestilo!</h1>
            <?php
            try {
                DBSpletna::updateCenaArtikla($data["id"], $data["cena_artikla"]);
                echo "Cena artikla uspešno posodobljena. <a href='$url'>Na prvo stran.</a></p>";
            } catch (Exception $e) {
                echo "<p>Napaka pri potrjevanju: {$e->getMessage()}.</p>";
            }
        // deaktiviranje artikla - TODO!!!
        elseif (isset($data["do"]) && $data["do"] == "deaktiviraj_artikel"):
            ?>
            <h1>Deaktiviranje artikla</h1>
            <?php
            try {
                DBSpletna::updateArticleStatus($data["id"], "neaktiven");
                $id_str = $data["id"];
                echo "Artikel $id_str uspešno deaktiviran. <a href='$url'>Na prvo stran.</a></p>";
            } catch (Exception $e) {
                echo "<p>Napaka pri deaktiviranju: {$e->getMessage()}.</p>";
            }
        // aktiviranje artikla - TODO!!!
        elseif (isset($data["do"]) && $data["do"] == "aktiviraj_artikel"):
            ?>
            <h1>Aktiviranje artikla</h1>
            <?php
            try {
                DBSpletna::updateArticleStatus($data["id"], "aktiven");
                $id_str = $data["id"];
                echo "Artikel $id_str uspešno aktiviran. <a href='$url'>Na prvo stran.</a></p>";
            } catch (Exception $e) {
                echo "<p>Napaka pri aktiviranju: {$e->getMessage()}.</p>";
            }
            
        // dodaj stranko - DONE!!!
        elseif (isset($data["do"]) && $data["do"] == "add_stranka"):
            ?>
            <h1>Dodajanje stranke</h1>
            <?php
            try {
                DBSpletna::insertStranka($data["ime_stranke"], $data["priimek_stranke"], $data["email_stranke"], password_hash($data["geslo_stranke"], PASSWORD_DEFAULT), $data["telefon_stranke"], $data["naslov_stranke"]);              
                echo "Stranka uspešno dodana! <a href='$url'>Na prvo stran.</a></p>";
            } catch (Exception $e) {
                echo "<p>Napaka pri dodajanju stranke: {$e->getMessage()}.</p>";
            }
        // posodabljanje zapisa IMENA stranke - DONE!!!
        elseif (isset($data["do"]) && $data["do"] == "edit_ime"):
            ?>
            <h1>Posodobitev zapisa</h1>
            <?php
            try {
                DBSpletna::updateFirstName($data["id"], $data["ime_stranke"]);
                echo "Ime uspešno posodobljeno. <a href='$url'>Na prvo stran.</a></p>";
            } catch (Exception $e) {
                echo "<p>Napaka pri zapisu: {$e->getMessage()}.</p>";
            }
        // posodabljanje zapisa PRIIMKA stranke - DONE!!!
        elseif (isset($data["do"]) && $data["do"] == "edit_priimek"):
            ?>
            <h1>Posodobitev zapisa</h1>
            <?php
            try {
                DBSpletna::updateLastName($data["id"], $data["priimek_stranke"]);
                echo "Priimek uspešno posodobljen. <a href='$url'>Na prvo stran.</a></p>";
            } catch (Exception $e) {
                echo "<p>Napaka pri zapisu: {$e->getMessage()}.</p>";
            }
        // posodabljanje zapisa EMAILA stranke - DONE!!!
        elseif (isset($data["do"]) && $data["do"] == "edit_email"):
            ?>
            <h1>Posodobitev zapisa</h1>
            <?php
            try {
                DBSpletna::updateEmail($data["id"], $data["email_stranke"]);
                echo "Email uspešno posodobljen. <a href='$url'>Na prvo stran.</a></p>";
            } catch (Exception $e) {
                echo "<p>Napaka pri zapisu: {$e->getMessage()}.</p>";
            }
        // posodabljanje zapisa GESLA stranke - DONE!!!
        elseif (isset($data["do"]) && $data["do"] == "edit_geslo"):
            ?>
            <h1>Posodobitev zapisa</h1>
            <?php
            try {
                // password_hash("stranka", PASSWORD_DEFAULT)
                DBSpletna::updatePassword($data["id"], password_hash($data["geslo_stranke"], PASSWORD_DEFAULT));
                echo "Geslo uspešno posodobljeno. <a href='$url'>Na prvo stran.</a></p>";
            } catch (Exception $e) {
                echo "<p>Napaka pri zapisu: {$e->getMessage()}.</p>";
            }
        // posodabljanje zapisa TELEFONA stranke - DONE!!!
        elseif (isset($data["do"]) && $data["do"] == "edit_telefon"):
            ?>
            <h1>Posodobitev zapisa</h1>
            <?php
            try {
                DBSpletna::updatePhone($data["id"], $data["telefon_stranke"]);
                echo "Telefon uspešno posodobljeno. <a href='$url'>Na prvo stran.</a></p>";
            } catch (Exception $e) {
                echo "<p>Napaka pri zapisu: {$e->getMessage()}.</p>";
            }
        // posodabljanje zapisa NASLOVA stranke - DONE!!!
        elseif (isset($data["do"]) && $data["do"] == "edit_naslov"):
            ?>
            <h1>Posodobitev zapisa</h1>
            <?php
            try {
                DBSpletna::updateAddress($data["id"], $data["naslov_stranke"]);
                echo "Naslov uspešno posodobljen. <a href='$url'>Na prvo stran.</a></p>";
            } catch (Exception $e) {
                echo "<p>Napaka pri zapisu: {$e->getMessage()}.</p>";
            }

        // deaktiviranje stranke - DONE!!!
        elseif (isset($data["do"]) && $data["do"] == "deaktiviraj_stranko"):
            ?>
            <h1>Deaktiviranje uporabniškega računa stranke</h1>
            <?php
            try {
                DBSpletna::updateUserStatus($data["id"], "neaktiven");
                $id_str = $data["id"];
                echo "Stranka $id_str uspešno deaktivirana. <a href='$url'>Na prvo stran.</a></p>";
            } catch (Exception $e) {
                echo "<p>Napaka pri deaktiviranju: {$e->getMessage()}.</p>";
            }
        // aktiviranje stranke - DONE!!!
        elseif (isset($data["do"]) && $data["do"] == "aktiviraj_stranko"):
            ?>
            <h1>Aktiviranje uporabniškega računa stranke</h1>
            <?php
            try {
                DBSpletna::updateUserStatus($data["id"], "aktiven");
                $id_str = $data["id"];
                echo "Stranka $id_str uspešno aktivirana. <a href='$url'>Na prvo stran.</a></p>";
            } catch (Exception $e) {
                echo "<p>Napaka pri aktiviranju: {$e->getMessage()}.</p>";
            }
        
        // edit narocilo - DONE!!!
        elseif (isset($data["do"]) && $data["do"] == "edit_narocilo"):
            ?>
            <h1>Obvestilo!</h1>
            <?php
            try {
                DBSpletna::updateRacunStatus($data["id"]);
                echo "Naročilo uspešno potrjeno. <a href='$url'>Na prvo stran.</a></p>";
            } catch (Exception $e) {
                echo "<p>Napaka pri potrjevanju: {$e->getMessage()}.</p>";
            }
        // zavrni narocilo - DONE!!!
        elseif (isset($data["do"]) && $data["do"] == "zavrni_narocilo"):
            ?>
            <h1>Obvestilo!</h1>
            <?php
            try {
                DBSpletna::updateRacunStatus2($data["id"]);
                echo "Naročilo je bilo zavrnjeno!. <a href='$url'>Na prvo stran.</a></p>";
            } catch (Exception $e) {
                echo "<p>Napaka pri zavrnitvi računa!: {$e->getMessage()}.</p>";
            }
        // Ogled racunov
        elseif (isset($data_get["do"]) && $data_get["do"] == "show"):
            ?>
            <h1>Zgodovina vseh računov!</h1>
            <?php
            try {
                $racuni = DBSpletna::getAllOrders();
                echo "<p><b>Vsi računi do dne </b>" . date("d/m/Y") . "! <a href='$url'>Na prvo stran.</a></p>";
                //var_dump($racuni);
                foreach ($racuni as $num => $row) {                    
                    $id_racun = $row["id"];
                    $cena = $row["postavka"];
                    $status = $row["status"];
                    $id_stranka = $row["stranka_id"];

                    if ($status == "zavrnjeno"){
                        echo "<p>Račun št. $id_racun za stranko: $id_stranka <br /> Cena " . number_format($cena, 2) . " EUR </br> Status: <font color='red'>$status</font>";                    
                    }
                    else if ($status == "odobreno"){
                        $url_klik = $url . "?do=storno&id=". $id_racun;
                        echo "<p>Račun št. $id_racun za stranko: $id_stranka <br /> Cena " . number_format($cena, 2) . " EUR </br> Status: <font color='green'>$status</font> [<a href='$url_klik'>Storniraj</a>]</p>\n"; // <br />[<a href='$url'>Uredi</a>]</p>\n";
                    
 
                    }
                    else if ($status == "stornirano"){
                        echo "<p>Račun št. $id_racun za stranko: $id_stranka <br /> Cena " . number_format($cena, 2) . " EUR </br> Status: <font color='brown'>$status</font>"; // <br />[<a href='$url'>Uredi</a>]</p>\n";
                    }
                    else{
                        echo "<p>Račun št. $id_racun za stranko: $id_stranka <br /> Cena " . number_format($cena, 2) . " EUR </br> Status: <font color='grey'>$status</font>"; // <br />[<a href='$url'>Uredi</a>]</p>\n";
                    }
                }
            } catch (Exception $e) {
                echo "<p>Napaka pri prikazu vseh računov!: {$e->getMessage()}.</p>";
            }      
        elseif (isset($data_get["do"]) && $data_get["do"] == "storno"):
            ?>
            <h1>Storniranje računa</h1>
            <?php 
            $url_klik = $url . "?do=show";
            echo "Ali ste prepričani, da želite stornirati račun? <a href='$url_klik'>Ne, vrni se nazaj.</a></p>";
            
            ?>
            <form action="<?= htmlspecialchars($url) ?>" method="POST">
                <input type="hidden" name="do" value="storniraj" />
                <input type="hidden" name="id" value="<?=$data_get["id"]?>" />
                <button class="gumb"type="submit">Storniraj</button>
            </form>
            <?php
        // Storniraj narcilo
        elseif (isset($data["do"]) && $data["do"] == "storniraj"):
            ?>
            <h1>Račun je storniran!</h1>
            <?php
            try {
                $id_racun = $data["id"];
                DBSpletna::updateRacunStatus3($id_racun);
                echo "Račun št. $id_racun uspešno storniran! <a href='$url'>Na prvo stran.</a></p>";
            } catch (Exception $e) {
                echo "<p>Napaka pri storniranju: {$e->getMessage()}.</p>";
            }  
        // PRIKAZ VSEH ZAPISOV
        else:
            ?>
            <!--- Ogled zgodovine računov ---> 
            <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="GET">
                <input type="hidden" name="do" value="show" />
                <button type="submit">Ogled vseh naročil</button>
            </form>
            
            <!--- Urejanje profila ---> 
            <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="GET">
                <input type="hidden" name="do" value="edit" />
                <input type="hidden" name="id" value="<?= $_SESSION["uporabnik_id"] ?>" />
                <button type="submit">Uredi svoj profil</button>
            </form>
            <!--- NAROCILA --->
            <h2>NAROČILA</h2>
            <?php
            try {
                $order = DBSpletna::getAllOrders();
            } catch (Exception $e) {
                echo "Prišlo je do napake: {$e->getMessage()}";
            }
            
            foreach ($order as $num => $row) {
                $url_klik = $url . "?do=edit_narocilo&id=" . $row["id"];
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
            <h3><a href="<?= $url . "?do=add_artikel" ?>">Dodaj artikel</a></h3>
            <?php
            try {
                $artikli = DBSpletna::getAllArticles();
            } catch (Exception $e) {
                echo "Prišlo je do napake: {$e->getMessage()}";
            }
            foreach ($artikli as $num => $row) {
                $url_klik = $url . "?do=edit_artikel&id=" . $row["id"];
                $id_artikla = $row["id"];
                $ime_artikla = $row["ime"];
                $opis_artikla = $row["opis"];
                $cena = $row["cena"];
                
                echo "<p>$id_artikla: <b>$ime_artikla</b><br />$opis_artikla<br />" . number_format($cena, 2) . " EUR<br />[<a href='$url_klik'>Uredi</a>]</p>\n";
            }
            ?>
            
            <!--- STRANKE --->
            <h2>STRANKE</h2>
            <h3><a href="<?= $url . "?do=add_stranka" ?>">Dodaj stranko</a></h3>
            <?php
            try {
                $stranke = DBSpletna::getAllCustomers();
            } catch (Exception $e) {
                echo "Prišlo je do napake: {$e->getMessage()}";
            }
            foreach ($stranke as $num => $row) {
                $url_klik = $url . "?do=edit&id=" . $row["id"];
                $id_stranke = $row["id"];
                $ime_stranke = $row["ime"];
                $priimek_stranke = $row["priimek"];
                $email_stranke = $row["email"];
                $telefon = $row["telefon"];
                $naslov = $row["naslov"];
                
                echo "<p>$id_stranke: <b>$ime_stranke $priimek_stranke</b><br />$email_stranke<br />$telefon<br />$naslov<br />[<a href='$url_klik'>Uredi</a>]</p>\n";
            }
        endif;
        ?>
    </body>
</html>
