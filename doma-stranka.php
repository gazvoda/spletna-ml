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
var_dump($artikli);

$url = filter_input(INPUT_SERVER, "PHP_SELF", FILTER_SANITIZE_SPECIAL_CHARS);
$validationRules = ['do' => [
        'filter' => FILTER_VALIDATE_REGEXP,
        'options' => [
            "regexp" => "/^(add_into_cart|update_cart|purge_cart)$/"
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
    case "add_into_cart":
        try {
            $artikel = DBSpletna::getArticle($data["id"]);
            
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
    default:
        break;
}
?><!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <meta charset="UTF-8" />
        <title>Spletna trgovina - ML</title>
    </head>
    <body>

        <h1>Spletna trgovina - ML</h1>
        
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

            if ($kosara):
                $znesek = 0;

                foreach ($kosara as $id => $kolicina):
                    $knjiga = DBSpletna::getArticle($id);
                    $znesek += $artikel['cena'] * $kolicina;
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
            <?php else: ?>
                Košara je prazna.                
            <?php endif; ?>
        </div>
    </body>
</html>
