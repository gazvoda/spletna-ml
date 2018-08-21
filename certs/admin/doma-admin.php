<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//$uporabnisko_ime = $_POST["uporabnisko_ime"];
//$geslo = $_POST["geslo"];
session_start();
$authorized_users = ["Ana"];

$client_cert = filter_input(INPUT_SERVER, "SSL_CLIENT_CERT");

if ($client_cert == null) {
    die('err: Spremenljivka SSL_CLIENT_CERT ni nastavljena.');
}


$cert_data = openssl_x509_parse($client_cert);
$commonname = (is_array($cert_data['subject']['CN']) ?
                $cert_data['subject']['CN'][0] : $cert_data['subject']['CN']);
if (in_array($commonname, $authorized_users)) {

//echo "<p>Vsebina certifikata: ";
//var_dump($cert_data);


//echo "Prijava uspesna za admina";
if (!isset($_SESSION["uporabnik_id"])) {
    echo "Za ogled te strani morate biti prijavljeni!";
} elseif ($_SESSION["uporabnik_vloga"] == "administrator") {

require_once '../../db/database_spletna.php';

?>

<html>
    <head>
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
                    "regexp" => "/^(add|edit|edit_ime|edit_priimek|edit_email|edit_geslo|add|deaktiviraj_prodajalca|aktiviraj_prodajalca)$/"
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
            'ime_prodajalca' => [
                'filter' => FILTER_SANITIZE_STRING
            ],
            'priimek_prodajalca' => [
                'filter' => FILTER_SANITIZE_STRING
            ],
            'email_prodajalca' => [
                'filter' => FILTER_SANITIZE_EMAIL
            ],
            'geslo_prodajalca' => [
                'filter' => FILTER_SANITIZE_STRING
            ]
        ];
        $data = filter_input_array(INPUT_POST, $validationRules);
        // var_dump($data);

        $data_get = filter_input_array(INPUT_GET, $validationRules);
        // var_dump($data_get);
        // var_dump($_SESSION);
        
        // VNOS PRODAJALCA -- ZASLONSKA MASKA
        if (isset($data_get["do"]) && $data_get["do"] == "add"):
            ?>
            <h1>Dodaj prodajalca</h1>
            <form action="<?= $url ?>" method="post">
                <input type="hidden" name="do" value="add" />
                <input type="text" name="ime_prodajalca" placeholder="Ime" /><br />
                <input type="text" name="priimek_prodajalca" placeholder="Priimek" /><br />
                <input type="text" name="email_prodajalca" placeholder="Email" /><br />
                <input type="password" name="geslo_prodajalca" placeholder="Geslo" /><br />
                <input type="submit" value="Dodaj" />
            </form>
            <?php
        // UREJANJE PRODAJALCA -- ZASLONSKA MASKA
        elseif (isset($data_get["do"]) && $data_get["do"] == "edit"):
            ?>
            <h1>Urejanje</h1>
            <?php
            try {
                $prodajalec = DBSpletna::getUser($data_get["id"])[0]; // POIZVEDBA V PB
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
            <form action="<?= $url ?>" method="post">
                <input type="hidden" name="id" value="<?= $id ?>" />
                <input type="hidden" name="do" value="edit_ime" />
                <input type="text" name="ime_prodajalca" value="<?= $ime ?>" />
                <input type="submit" value="Spremeni" />
            </form>
            <form action="<?= $url ?>" method="post">
                <input type="hidden" name="id" value="<?= $id ?>" />
                <input type="hidden" name="do" value="edit_priimek" />
                <input type="text" name="priimek_prodajalca" value="<?= $priimek ?>" />
                <input type="submit" value="Spremeni" />
            </form>
            <form action="<?= $url ?>" method="post">
                <input type="hidden" name="id" value="<?= $id ?>" />
                <input type="hidden" name="do" value="edit_email" />
                <input type="text" name="email_prodajalca" value="<?= $email ?>" />
                <input type="submit" value="Spremeni" />
            </form>
            <form action="<?= $url ?>" method="post">
                <input type="hidden" name="id" value="<?= $id ?>" />
                <input type="hidden" name="do" value="edit_geslo" />
                <input type="password" name="geslo_prodajalca" placeholder="Novo geslo" />
                <input type="submit" value="Spremeni" />
            </form>
            
            <?php 
                if ($_SESSION["uporabnik_id"] != $id) {
                    ?>
                    <h2>Izbris prodajalca</h2>
                    <form action="<?= $url ?>" method="post">
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
                <?php }
            ?>
            	
            <?php
        // posodabljanje zapisa IMENA prodajalca v pb
        elseif (isset($data["do"]) && $data["do"] == "edit_ime"):
            ?>
            <h1>Posodobitev zapisa</h1>
            <?php
            try {
                // var_dump($_POST);
                // var_dump($_SESSION["ime_prodajalca"]);
                // die();
                DBSpletna::updateFirstName($data["id"], $data["ime_prodajalca"]);
                echo "Ime uspešno posodobljeno. <a href='$url'>Na prvo stran.</a></p>";
            } catch (Exception $e) {
                echo "<p>Napaka pri zapisu: {$e->getMessage()}.</p>";
            }

        // posodabljanje zapisa PRIIMKA prodajalca v pb
        elseif (isset($data["do"]) && $data["do"] == "edit_priimek"):
            ?>
            <h1>Posodobitev zapisa</h1>
            <?php
            try {
                // var_dump($_POST);
                // var_dump($_SESSION["ime_prodajalca"]);
                // die();
                DBSpletna::updateLastName($data["id"], $data["priimek_prodajalca"]);
                echo "Priimek uspešno posodobljen. <a href='$url'>Na prvo stran.</a></p>";
            } catch (Exception $e) {
                echo "<p>Napaka pri zapisu: {$e->getMessage()}.</p>";
            }
        
        // posodabljanje zapisa EMAILA prodajalca v pb
        elseif (isset($data["do"]) && $data["do"] == "edit_email"):
            ?>
            <h1>Posodobitev zapisa</h1>
            <?php
            try {
                // var_dump($_POST);
                // var_dump($_SESSION["ime_prodajalca"]);
                // die();
                DBSpletna::updateEmail($data["id"], $data["email_prodajalca"]);
                echo "Email uspešno posodobljen. <a href='$url'>Na prvo stran.</a></p>";
            } catch (Exception $e) {
                echo "<p>Napaka pri zapisu: {$e->getMessage()}.</p>";
            }
            
        // posodabljanje zapisa GESLA prodajalca v pb
        elseif (isset($data["do"]) && $data["do"] == "edit_geslo"):
            ?>
            <h1>Posodobitev zapisa</h1>
            <?php
            try {
                // var_dump($_POST);
                // var_dump($_SESSION["ime_prodajalca"]);
                // die();
                // password_hash("stranka", PASSWORD_DEFAULT)
                DBSpletna::updatePassword($data["id"], password_hash($data["geslo_prodajalca"], PASSWORD_DEFAULT));
                echo "Geslo uspešno posodobljeno. <a href='$url'>Na prvo stran.</a></p>";
            } catch (Exception $e) {
                echo "<p>Napaka pri zapisu: {$e->getMessage()}.</p>";
            }
            
        // VNOS ZAPISA V PB: dodajanje prodajalca
        elseif (isset($data["do"]) && $data["do"] == "add"):
            ?>
            <h1>Vnašanje zapisa</h1>
            <?php
            try {
                DBSpletna::insertProdajalec($data["ime_prodajalca"], $data["priimek_prodajalca"], $data["email_prodajalca"], password_hash($data["geslo_prodajalca"], PASSWORD_DEFAULT));              
                echo "Prodajalec uspešno dodan! <a href='$url'>Na prvo stran.</a></p>";
            } catch (Exception $e) {
                echo "<p>Napaka pri dodajanju prodajalca: {$e->getMessage()}.</p>";
            }

        // deaktiviranje prodajalca - TODO!!!
        elseif (isset($data["do"]) && $data["do"] == "deaktiviraj_prodajalca"):
            ?>
            <h1>Deaktiviranje uporabniškega računa prodajalca</h1>
            <?php
            try {
                DBSpletna::updateUserStatus($data["id"], "neaktiven");
                $id_str = $data["id"];
                echo "Prodajalec $id_str uspešno deaktiviran. <a href='$url'>Na prvo stran.</a></p>";
            } catch (Exception $e) {
                echo "<p>Napaka pri deaktiviranju: {$e->getMessage()}.</p>";
            }
            
        // aktiviranje prodajalca - TODO!!!
        elseif (isset($data["do"]) && $data["do"] == "aktiviraj_prodajalca"):
            ?>
            <h1>Aktiviranje uporabniškega računa prodajalca</h1>
            <?php
            try {
                DBSpletna::updateUserStatus($data["id"], "aktiven");
                $id_str = $data["id"];
                echo "Prodajalec $id_str uspešno aktiviran. <a href='$url'>Na prvo stran.</a></p>";
            } catch (Exception $e) {
                echo "<p>Napaka pri aktiviranju: {$e->getMessage()}.</p>";
            }
        
// PRIKAZ VSEH ZAPISOV
        else:
            ?>
            <form action="<?= $url ?>" method="GET">
                <input type="hidden" name="do" value="edit" />
                <input type="hidden" name="id" value="<?= $_SESSION["uporabnik_id"] ?>" />
                <button type="submit">Uredi svoj profil</button>
            </form>
            <h1>Prodajalci</h1>
            <h2><a href="<?= $url . "?do=add" ?>">Dodaj prodajalca</a></h2>
            <?php
            try {
                $vsi_prodajalci = DBSpletna::getAllRole("prodajalec");
                $orders = DBSpletna::getAllOrders();
            } catch (Exception $e) {
                echo "Prišlo je do napake: {$e->getMessage()}";
            }

            foreach ($vsi_prodajalci as $num => $row) {
                $url_klik = $url . "?do=edit&id=" . $row["id"];
                $id_prodajalec = $row["id"];
                $ime = $row["ime"];
                $priimek = $row["priimek"];
                $email = $row["email"];
                $status = $row["status"];

                echo "<p>$id_prodajalec: <b>$ime $priimek</b> [<a href='$url_klik'>Uredi</a>]</p>\n";
            }
        endif;
        ?>
    </body>
</html>


<?php

}

} else {
    echo "$commonname ni avtoriziran uporabnik in nima dostopa do te strani.";
}