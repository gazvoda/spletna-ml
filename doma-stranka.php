<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <meta charset="UTF-8" />
        <title>Spletna trgovina - ML</title>
    </head>
    <body>

<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once 'db/database_spletna.php';

session_start();
echo "Dobrodosli v Spletni trgovini - ML!";
// var_dump($_SESSION);

$artikli = DBSpletna::getAllArticles();
//var_dump($artikli);

$url = filter_input(INPUT_SERVER, "PHP_SELF", FILTER_SANITIZE_SPECIAL_CHARS);
$validationRules = ['do' => [
        'filter' => FILTER_VALIDATE_REGEXP,
        'options' => [
            "regexp" => "/^(add_into_cart|update_cart|purge_cart|edit_profile|edit_ime|edit_priimek|edit_email|edit_geslo|edit_telefon|edit_naslov)$/"
        ]
    ],
    'id' => [
        'filter' => FILTER_VALIDATE_INT,
        'options' => ['min_range' => 0]
    ],
    'kolicina' => [
        'filter' => FILTER_VALIDATE_INT,
        'options' => ['min_range' => 0]
    ]
];
$data = filter_input_array(INPUT_POST, $validationRules);
var_dump($data);

$data_get = filter_input_array(INPUT_GET, $validationRules);
var_dump($data_get);
// var_dump($_SESSION);

switch ($data["do"]) {
    case "add_into_cart":
        try {
            // var_dump(DBSpletna::getArticle($data["id"]));
            $artikel = DBSpletna::getArticle($data["id"])[0];
            // var_dump($artikel);
            // var_dump(DBSpletna::getAllArticles());
            if (isset($_SESSION["cart"][$artikel['id']])) {
                $_SESSION["cart"][$artikel['id']] ++;
            } else {
                $_SESSION["cart"][$artikel['id']] = 1;
            }
        } catch (Exception $exc) {
            die($exc->getMessage());
        }
        break;
    case "update_cart":
        if (isset($_SESSION["cart"][$data["id"]])) {
            if ($data["kolicina"] > 0) {
                $_SESSION["cart"][$data["id"]] = $data["kolicina"];
            } else {
                unset($_SESSION["cart"][$data["id"]]);
            }
        }
        break;
    case "purge_cart":
        unset($_SESSION["cart"]);
        break;
    case "edit_ime":
        ?>
            <h1>Posodobitev zapisa</h1>
            <?php
            try {
                DBSpletna::updateFirstName($_SESSION["uporabnik_id"], $_POST["ime_stranke"]);
                echo "Ime uspešno posodobljeno.</p>";
            } catch (Exception $e) {
                echo "<p>Napaka pri zapisu: {$e->getMessage()}.</p>";
            }
        break;
    case "edit_priimek":
        ?>
            <h1>Posodobitev zapisa</h1>
            <?php
            try {
                DBSpletna::updateLastName($_SESSION["uporabnik_id"], $_POST["priimek_stranke"]);
                echo "Priimek uspešno posodobljen.</p>";
            } catch (Exception $e) {
                echo "<p>Napaka pri zapisu: {$e->getMessage()}.</p>";
            }
        break;
    case "edit_email":
        ?>
            <h1>Posodobitev zapisa</h1>
            <?php
            try {
                DBSpletna::updateEmail($_SESSION["uporabnik_id"], $_POST["email_stranke"]);
                echo "Email uspešno posodobljen.</p>";
            } catch (Exception $e) {
                echo "<p>Napaka pri zapisu: {$e->getMessage()}.</p>";
            }
        break;
    case "edit_geslo":
        ?>
            <h1>Posodobitev zapisa</h1>
            <?php
            try {
                // password_hash("stranka", PASSWORD_DEFAULT)
                DBSpletna::updatePassword($_SESSION["uporabnik_id"], password_hash($_POST["geslo_stranke"], PASSWORD_DEFAULT));
                echo "Geslo uspešno posodobljeno. <a href='$_SERVER[PHP_SELF]'>Na prvo stran.</a></p>";
            } catch (Exception $e) {
                echo "<p>Napaka pri zapisu: {$e->getMessage()}.</p>";
            }
        break;
    case "edit_telefon":
        ?>
            <h1>Posodobitev zapisa</h1>
            <?php
            try {
                DBSpletna::updatePhone($_SESSION["uporabnik_id"], $_POST["telefon_stranke"]);
                echo "Telefon uspešno posodobljeno. <a href='$_SERVER[PHP_SELF]'>Na prvo stran.</a></p>";
            } catch (Exception $e) {
                echo "<p>Napaka pri zapisu: {$e->getMessage()}.</p>";
            }
        break;
    case "edit_naslov":
        ?>
            <h1>Posodobitev zapisa</h1>
            <?php
            try {
                DBSpletna::updateAddress($_SESSION["uporabnik_id"], $_POST["naslov_stranke"]);
                echo "Naslov uspešno posodobljen. <a href='$_SERVER[PHP_SELF]'>Na prvo stran.</a></p>";
            } catch (Exception $e) {
                echo "<p>Napaka pri zapisu: {$e->getMessage()}.</p>";
            }
        break;
    default:
        break;
}

if ($data_get["do"] == "edit_profile" && isset($_SESSION["uporabnik_id"])) {
            ?>
            <h1>Urejanje</h1>
            <?php
            try {
                $stranka = DBSpletna::getUser($_SESSION["uporabnik_id"])[0]; // POIZVEDBA V PB
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
            ?>
            <h2>Urejanje zapisa id = <?= $id ?></h2>
            <form action="<?= $url ?>" method="post">
                <input type="hidden" name="do" value="edit_ime" />
                <input type="text" name="ime_stranke" value="<?= $ime ?>" />
                <input type="submit" value="Spremeni" />
            </form>
            <form action="<?= $url ?>" method="post">
                <input type="hidden" name="do" value="edit_priimek" />
                <input type="text" name="priimek_stranke" value="<?= $priimek ?>" />
                <input type="submit" value="Spremeni" />
            </form>
            <form action="<?= $url ?>" method="post">
                <input type="hidden" name="do" value="edit_email" />
                <input type="text" name="email_stranke" value="<?= $email ?>" />
                <input type="submit" value="Spremeni" />
            </form>
            <form action="<?= $url ?>" method="post">
                <input type="hidden" name="do" value="edit_geslo" />
                <input type="password" name="geslo_stranke" placeholder="Geslo" />
                <input type="submit" value="Spremeni" />
            </form>
            <form action="<?= $url ?>" method="post">
                <input type="hidden" name="do" value="edit_telefon" />
                <input type="text" name="telefon_stranke" value="<?= $telefon ?>" />
                <input type="submit" value="Spremeni" />
            </form>
            <form action="<?= $url ?>" method="post">
                <input type="hidden" name="do" value="edit_naslov" />
                <textarea rows="5" cols="20" name="naslov_stranke"><?= $naslov ?></textarea>
            <input type="submit" value="Spremeni" />
            </form>	
            <?php
} else {
    ?>

        <h1>Spletna trgovina - ML</h1>
        
        <form action="../../odjava.php" method="get">
            <input type="submit" value="Odjava">
        </form>
        <form action="<?= $url ?>" method="GET">
            <input type="hidden" name="do" value="edit_profile" />
            <button type="submit">Uredi svoj profil</button>
        </form>
        
        <div id="main">
            <?php foreach (DBSpletna::getAllArticles() as $artikel): ?>
                <div class="book">
                    <form action="<?= $url ?>" method="post">
                        <input type="hidden" name="do" value="add_into_cart" />
                        <input type="hidden" name="id" value="<?= $artikel['id'] ?>" />
                        <p><?= $artikel['ime'] ?>: <?= $artikel['opis'] ?></p>
                        <p><?= number_format($artikel['cena'], 2) ?> EUR<br/>
                            <button type="submit">V košarico</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="cart">
            <h3>Košarica</h3>

            <?php
            $kosara = isset($_SESSION["cart"]) ? $_SESSION["cart"] : [];
            // var_dump($kosara);
            // var_dump($artikel);
            // var_dump($_SESSION);
            if ($kosara):
                $znesek = 0;

                foreach ($kosara as $id => $kolicina):
                    $artikel = DBSpletna::getArticle($id)[0];
                    $znesek += $artikel['cena'] * $kolicina;
                    $_SESSION['znesek'] = $znesek;
                    ?>
                    <form action="<?= $url ?>" method="post">
                        <input type="hidden" name="do" value="update_cart" />
                        <input type="hidden" name="id" value="<?= $artikel['id'] ?>" />
                        <input type="number" name="kolicina" value="<?= $kolicina ?>"
                               class="short_input" />
                        &times; <?=
                        (strlen($artikel['opis']) < 30) ?
                                $artikel['opis'] :
                                substr($artikel['opis'], 0, 26) . " ..."
                        ?> 
                        <button type="submit">Posodobi</button> 
                    </form>
                <?php endforeach; ?>

                <p>Total: <b><?= number_format($znesek, 2) ?> EUR</b></p>

                <form action="<?= $url ?>" method="POST">
                    <input type="hidden" name="do" value="purge_cart" />
                    <input type="submit" value="Izprazni košarico" />
                </form>
                <form action="stranka-racun.php" method="POST">
                    <input type="submit" value="Zaključi nakup" />
                </form>
            <?php else: ?>
                Košara je prazna.                
            <?php endif; ?>
        </div>
        <?php
}   
?>   
    </body>
</html>
