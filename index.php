<?php

require_once 'db/database_spletna.php';
echo "Za nakupovanje je potrebna registracija ali prijava";
?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Spletna trgovina - ML</title>
    </head>
    <body>

<?php
$allowAccess = FALSE;
$isPost = filter_input(INPUT_SERVER, 'REQUEST_METHOD') == 'POST';

if ($isPost) {
    $rules = array(
        'email' => FILTER_SANITIZE_STRING,
        'geslo' => FILTER_SANITIZE_STRING
    );

    $sent = filter_input_array(INPUT_POST, $rules);

    if ($sent["email"] != NULL && $sent["geslo"] != NULL) {
        try {
            $dbh = new PDO("mysql:host=localhost;dbname=spletna_trgovina", "root", "ep");
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            
            $stmt = $dbh->prepare("SELECT * FROM uporabnik WHERE email = ?");
            $stmt->bindValue(1, $sent["email"]);
            $stmt->execute();

            // zapis iz baze
            $uporabnik = $stmt->fetch();

            // pravilnost gesla preverimo s klicem funkcije 
            // password_verify(geslo, geslo_v_bazi)
            if (password_verify($sent["geslo"], $uporabnik["geslo"]) && $uporabnik["status"] == "aktiven") {
                $allowAccess = TRUE;
            }
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }
}
?>

        <?php
        if ($isPost) {
            if ($allowAccess) {
                //echo "Dobrodošli na skrivni strani!";
                //var_dump($uporabnik);
                session_start();
                // Store Session Data
                //echo "$user[role]";
                $_SESSION['uporabnik_vloga']= $uporabnik['vloga'];  // Initializing Session with value of PHP Variable
                $_SESSION['uporabnik_id'] = $uporabnik['id'];
                $_SESSION['uporabnik_email'] = $uporabnik['email'];
                //var_dump($_SESSION);
                switch ($_SESSION['uporabnik_vloga']) {
                    case "administrator":
                        header("Location: certs/prijava-cert.php");
                        exit();
                        break;
                    case "prodajalec":
                        header("Location: certs/prijava-cert.php");
                        exit();
                        break;
                    case "stranka":
                        header("Location: doma-stranka.php");
                        exit();
                        break;
                    default:
                        break;
                }
            } else {
                echo "Prijava neuspešna.";
            }
        } else {
            ?>
            <form action="registracija.php">
                <input type="submit" value="Registracija">
            </form>
            <form action="<?= basename(__FILE__) ?>" method="post">
                Prijava z obstoječim računom: <br />
                Email <input type="text" name="email" />
                Geslo <input type="password" name="geslo" />
                <input type="submit" value="Prijava">
            </form>
            <?php
                // $articles = DBSpletna::getAllArticles();
                // var_dump($articles);
                
                // DBSpletna::updatePassword(6, password_hash("stranka", PASSWORD_DEFAULT));
            ?>
            <?php
        }
        ?>
        
        <div id="main">
            <?php foreach (DBSpletna::getAllArticles() as $artikel): ?>
                <div class="book">
                        <p><?= $artikel['ime'] ?>: <?= $artikel['opis'] ?></p>
                        <p><?= number_format($artikel['cena'], 2) ?> EUR<br/>
                </div>
            <?php endforeach; ?>
        </div>
    </body>
</html>