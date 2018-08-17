<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//$uporabnisko_ime = $_POST["uporabnisko_ime"];
//$geslo = $_POST["geslo"];
session_start();
//echo "Prijava uspesna za admina";
//var_dump($_SESSION);
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
        // VNOS PRODAJALCA -- ZASLONSKA MASKA
        if (isset($_GET["do"]) && $_GET["do"] == "add"):
            ?>
            <h1>Dodaj prodajalca</h1>
            <form action="<?= $_SERVER["PHP_SELF"] ?>" method="post">
                <input type="hidden" name="do" value="add" />
                <input type="text" name="ime_prodajalca" placeholder="Ime" /><br />
                <input type="text" name="priimek_prodajalca" placeholder="Priimek" /><br />
                <input type="text" name="email_prodajalca" placeholder="Email" /><br />
                <input type="password" name="geslo_prodajalca" placeholder="Geslo" /><br />
                <input type="submit" value="Dodaj" />
            </form>
            <?php
        // UREJANJE PRODAJALCA -- ZASLONSKA MASKA
        elseif (isset($_GET["do"]) && $_GET["do"] == "edit"):
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
            $status = $prodajalec["status"];
            
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
                <!--- depending on user account status, change between "aktiviraj" in "deaktiviraj" --->
                <?php
                    switch ($status) {
                    case "aktiven":
                        $gumb_value = "Deaktiviraj";
                        $gumb_action = "deaktiviraj_prodajalca";
                        break;
                    case "neaktiven":
                        $gumb_value = "Aktiviraj";
                        $gumb_action = "aktiviraj_prodajalca";
                        break;
                    default:
                        break;
                    }
                ?>
                <input type="hidden" name="do" value="<?= $gumb_action ?>" />
                <input type="submit" value="<?= $gumb_value ?>" />
            </form>		
            <?php
        // posodabljanje zapisa IMENA prodajalca v pb
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

        // posodabljanje zapisa PRIIMKA prodajalca v pb
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
        
        // posodabljanje zapisa EMAILA prodajalca v pb
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
            
        // posodabljanje zapisa GESLA prodajalca v pb
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
            
        // VNOS ZAPISA V PB: dodajanje prodajalca
        elseif (isset($_POST["do"]) && $_POST["do"] == "add"):
            ?>
            <h1>Vnašanje zapisa</h1>
            <?php
            try {
                DBSpletna::insertProdajalec($_POST["ime_prodajalca"], $_POST["priimek_prodajalca"], $_POST["email_prodajalca"], password_hash($_POST["geslo_prodajalca"], PASSWORD_DEFAULT));              
                echo "Prodajalec uspešno dodan! <a href='$_SERVER[PHP_SELF]'>Na prvo stran.</a></p>";
            } catch (Exception $e) {
                echo "<p>Napaka pri dodajanju prodajalca: {$e->getMessage()}.</p>";
            }

        // deaktiviranje prodajalca - TODO!!!
        elseif (isset($_POST["do"]) && $_POST["do"] == "deaktiviraj_prodajalca"):
            ?>
            <h1>Deaktiviranje uporabniškega računa prodajalca</h1>
            <?php
            try {
                DBSpletna::updateUserStatus($_POST["id"], "neaktiven");
                $id_str = $_POST["id"];
                echo "Prodajalec $id_str uspešno deaktiviran. <a href='$_SERVER[PHP_SELF]'>Na prvo stran.</a></p>";
            } catch (Exception $e) {
                echo "<p>Napaka pri deaktiviranju: {$e->getMessage()}.</p>";
            }
            
        // aktiviranje prodajalca - TODO!!!
        elseif (isset($_POST["do"]) && $_POST["do"] == "aktiviraj_prodajalca"):
            ?>
            <h1>Aktiviranje uporabniškega računa prodajalca</h1>
            <?php
            try {
                DBSpletna::updateUserStatus($_POST["id"], "aktiven");
                $id_str = $_POST["id"];
                echo "Prodajalec $id_str uspešno aktiviran. <a href='$_SERVER[PHP_SELF]'>Na prvo stran.</a></p>";
            } catch (Exception $e) {
                echo "<p>Napaka pri aktiviranju: {$e->getMessage()}.</p>";
            }
        
// PRIKAZ VSEH ZAPISOV
        else:
            ?>
            <h1>Prodajalci</h1>
            <h2><a href="<?= htmlspecialchars($_SERVER["PHP_SELF"]) . "?do=add" ?>">Dodaj prodajalca</a></h2>
            <?php
            try {
                $vsi_prodajalci = DBSpletna::getAllRole("prodajalec");
                $orders = DBSpletna::getAllOrders();
            } catch (Exception $e) {
                echo "Prišlo je do napake: {$e->getMessage()}";
            }

            foreach ($vsi_prodajalci as $num => $row) {
                $url = htmlspecialchars($_SERVER["PHP_SELF"]) . "?do=edit&id=" . $row["id"];
                $id_prodajalec = $row["id"];
                $ime = $row["ime"];
                $priimek = $row["priimek"];
                $email = $row["email"];
                $status = $row["status"];

                echo "<p>$id_prodajalec: <b>$ime $priimek</b> [<a href='$url'>Uredi</a>]</p>\n";
            }
        endif;
        ?>
    </body>
</html>


