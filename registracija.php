<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once 'db/database_spletna.php';

session_start();

?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <meta charset="UTF-8" />
        <title>Registracija stranke</title>
    </head>
    <body>

<?php

$url = filter_input(INPUT_SERVER, "PHP_SELF", FILTER_SANITIZE_SPECIAL_CHARS);
$validationRules = ['do' => [
        'filter' => FILTER_VALIDATE_REGEXP,
        'options' => [
            "regexp" => "/^(create_profile)$/"
            ]
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
        ]
    
];
$data = filter_input_array(INPUT_POST, $validationRules);


if (isset($data["do"]) && $data["do"] == "create_profile"){    
    ?>
    <h1>Ustvarjanje novega uporabnika</h1>
    <?php
    try {
        $ime = $data["ime_stranke"];
        $priimek = $data["priimek_stranke"];
        $email = $data["email_stranke"];
        $password = password_hash($data["geslo_stranke"], PASSWORD_DEFAULT);
        $telefon = $data["telefon_stranke"];
        $naslov = $data["naslov_stranke"];
        
        DBSpletna::insertStranka($ime, $priimek, $email, $password, $telefon, $naslov);
        echo "Registracija stranke $ime $priimek je uspešna! <a href='index.php'>Prijava.</a></p>";
    } catch (Exception $e) {
        echo "<p>Napaka pri registraciji: {$e->getMessage()}.</p>";
    }
}
else{
    ?>
    <h1>Ustvarjanje novega uporabnika</h1>
    <h2>Vnesite vsa naslednja polja:</h2>
        <form action="<?= $url ?>" method="post">
            <input type="hidden" name="do" value="create_profile" />
            
            <label class="label1">Ime:</label>
            <input class="input1" type="text" name="ime_stranke" placeholder="Ime" /> <br />
            <label class="label1">Priimek:</label>
            <input class="input1" type="text" name="priimek_stranke" placeholder="Priimek" /> <br />
            <label class="label1">E-mail:</label>
            <input class="input1" type="text" name="email_stranke" placeholder="E-mail" /> <br />
            <label class="label1">Geslo:</label>
            <input class="input1" type="password" name="geslo_stranke" placeholder="Geslo" /> <br />
            <label class="label1">Telefon:</label>
            <input class="input1" type="text" name="telefon_stranke" placeholder="Telefonska številka" /> <br /> <br /> <br />
            <label class="label1">Naslov:</label>
            <textarea class="input1" rows="5" cols="20" name="naslov_stranke" placeholder="Naslov"></textarea> <br /> <br /> <br /> <br />  <br /> <br /> 
            
            <input type="submit" value="Ustvari uporabnika" />
        </form>
    <?php
}   
    ?>   
    </body>
</html>
