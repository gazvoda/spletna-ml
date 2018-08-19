<?php

require_once 'db/database_spletna.php';

session_start();
if (!isset($_SESSION["uporabnik_id"])) {
    echo "Za ogled te strani morate biti prijavljeni!";
} elseif ($_SESSION["uporabnik_vloga"] == "stranka") {


$validationRules = ['do' => [
        'filter' => FILTER_VALIDATE_REGEXP,
        'options' => [
            "regexp" => "/^(submit_order)$/"
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

switch ($data["do"]) {
    case "submit_order":
        if (isset($_SESSION["cart"])) {
            
            $neobdelano = "neobdelano";
            DBSpletna::insertOrder($_SESSION["znesek"], $neobdelano, $_SESSION["uporabnik_id"]);
            
            echo "Naročilo je bilo poslano, počakajte, da prodajalec potrdi nakup.";
            unset($_SESSION["cart"]);
            ?>
            <a href="doma-stranka.php">Domov</a>
            <?php
        }
        break;
        
    default:
    ?>
    <html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Predračun</title>
    </head>
    <body>
        <h1>Predračun</h1>
        
        <?php
            $uporabnik = DBSpletna::getUser($_SESSION["uporabnik_id"])[0];
            $ime = $uporabnik["ime"];
            $priimek = $uporabnik["priimek"];
            $stranka_id = $_SESSION["uporabnik_id"];
            
            echo "<p> <b>Stranka:</b> $ime $priimek (ID $stranka_id): <br /> <b>Artikli:</b>  </p>\n";
            
            foreach ($_SESSION["cart"] as $num => $row) {
                
                $artikel = DBSpletna::getArticle($num)[0];           
                
                $artikelIme = $artikel["ime"];
                $artikelOpis = $artikel["opis"];
                $artikelCena = $artikel["cena"];
                
                echo "<p>$artikelIme $artikelOpis (Cena: ".number_format($artikelCena, 2)." EUR)<br />  Količina: $row </p>\n"; 
            }
            $znesek = $_SESSION["znesek"];
            echo "<p><b>SKUPAJ: </b> ".number_format($znesek, 2)." EUR<br /> </p>\n";
        ?> 
        
        <form action="stranka-racun.php" method="POST">
            <input type="hidden" name="do" value="submit_order" />
            <input type="submit" value="Potrdi naročilo" />
        </form>
        

    </body>
</html>
    <?php
        break;
}
}
