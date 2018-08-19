<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

session_start();
if (!isset($_SESSION["uporabnik_id"])) {
    echo "Za ogled te strani morate biti prijavljeni!";
} elseif ($_SESSION["uporabnik_vloga"] == "stranka") {

require_once 'db/database_spletna.php';

// var_dump($_SESSION);

?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <meta charset="UTF-8" />
        <title>Spletna trgovina - ML</title>
    </head>
    <body>
        <h1>Spletna trgovina - ML</h1>
        
        <form action="odjava.php" method="get">
            <input type="submit" value="Odjava">
        </form>
       <!--- <h2>Seznam vseh naročil:</h2> --->
        <?php echo "<p><b>Vsa naročila do dne </b>" . date("d/m/Y") . "! <a href='doma-stranka.php'>Na prvo stran.</a></p>";
        ?><div id="main">
            <?php foreach (DBSpletna::getAllOrdersStranka($_SESSION["uporabnik_id"]) as $order): 
                $id_narocila = $order["id"];
                $postavka = $order["postavka"];
                $status = $order["status"];
                echo "<p>Naročilo <b>#$id_narocila:</b><br />Cena: " . number_format($postavka, 2) . " EUR<br />Status: $status</p>";
                
            endforeach; ?>
        </div>

        
    </body>
</html>
<?php }