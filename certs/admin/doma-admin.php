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
        <form action="../odjava.php" method="get">
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
        // POSODABLJANJE ZAPISA IMENA PRODAJALCA V PB
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

        // VNOS ZAPISA V PB
        elseif (isset($_POST["do"]) && $_POST["do"] == "add"):
            ?>
            <h1>Vnašanje zapisa</h1>
            <?php
            try {
                DBJokes::insert($_POST["joke_date"], $_POST["joke_text"]);
                echo "Šala uspešno dodana. <a href='$_SERVER[PHP_SELF]'>Na prvo stran.</a></p>";
            } catch (Exception $e) {
                echo "<p>Napaka pri zapisu: {$e->getMessage()}.</p>";
            }

        // BRISANJE ZAPISA IZ PB
        elseif (isset($_POST["do"]) && $_POST["do"] == "delete"):
            ?>
            <h1>Brisanje zapisa</h1>
            <?php
            try {
                DBJokes::delete($_POST["id"]);
                echo "Šala uspešno odstranjena. <a href='$_SERVER[PHP_SELF]'>Na prvo stran.</a></p>";
            } catch (Exception $e) {
                echo "<p>Napaka pri brisanju: {$e->getMessage()}.</p>";
            }
        // PRIKAZ VSEH ZAPISOV
        else:
            ?>
            <h1>Prodajalci</h1>
            <h2><a href="<?= htmlspecialchars($_SERVER["PHP_SELF"]) . "?do=add" ?>">Dodaj prodajalca</a></h2>
            <?php
            try {
                $vsi_prodajalci = DBSpletna::getAllRole("prodajalec");
                var_dump($vsi_prodajalci);
                $orders = DBSpletna::getAllOrders();
                var_dump($orders);
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


