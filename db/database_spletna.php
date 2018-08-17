<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once 'database_init.php';

class DBSpletna {

    public static function getAllUsers() {
        $db = DBInit::getInstance();

        $statement = $db->prepare("SELECT id, vloga, ime, priimek, email FROM uporabnik");
        $statement->execute();

        return $statement->fetchAll();
    }
    
    public static function getUser($id) {
        $db = DBInit::getInstance();

        $statement = $db->prepare("SELECT id, vloga, ime, priimek, email, telefon, naslov, status FROM uporabnik WHERE id = :id");
        $statement->bindParam(":id", $id);
        $statement->execute();
        
        return $statement->fetchAll();
    }
    
    public static function getAllArticles() {
        $db = DBInit::getInstance();
        
        $statement = $db->prepare("SELECT id, ime, opis, cena, zaloga FROM artikel");
        $statement->execute();
        
        return $statement->fetchAll();
    }
    
    public static function getArticle($id) {
        $db = DBInit::getInstance();
        
        $statement = $db->prepare("SELECT id, ime, opis, cena, zaloga FROM artikel WHERE id = :id");
        $statement->bindParam(":id", $id);
        $statement->execute();
        
        return $statement->fetchAll();
    }

    public static function getAllRole($vloga) {
        $db = DBInit::getInstance();

        $statement = $db->prepare("SELECT id, vloga, ime, priimek, email, status "
                . "FROM uporabnik WHERE vloga = :vloga");
        $statement->bindParam(":vloga", $vloga);
        $statement->execute();

        return $statement->fetchAll();
    }
    
    public static function getAllCustomers() {
        $db = DBInit::getInstance();

        $statement = $db->prepare("SELECT id, vloga, ime, priimek, email, telefon, naslov status "
                . "FROM uporabnik WHERE vloga = 'stranka'");
        $statement->execute();

        return $statement->fetchAll();
    }
    public static function getAllOrders() {
        $db = DBInit::getInstance();

        $statement = $db->prepare("SELECT id, postavka, status, stranka_id FROM racun");
        $statement->execute();

        return $statement->fetchAll();
    }
    

    public static function deleteProdajalec($id) {
        $db = DBInit::getInstance();

        $statement = $db->prepare("DELETE FROM uporabnik WHERE id = :user_id");
        $statement->bindParam(":user_id", $id, PDO::PARAM_INT);
        $statement->execute();
    }
/*
    public static function get($user_id) {
        $db = DBInit::getInstance();

        $statement = $db->prepare("SELECT user_id, role, first_name, last_name, email, password, status FROM user 
            WHERE user_id =:user_id");
        $statement->bindParam(":user_id", $user_id, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetch();
    }
    */
    public static function insertProdajalec($ime, $priimek, $email, $password) {
        $db = DBInit::getInstance();
        $vloga = "prodajalec";
        $status = "aktiven";        

        $statement = $db->prepare("INSERT INTO uporabnik (vloga, ime, priimek, email, geslo, status)
            VALUES (:vloga, :first_name, :last_name, :email, :password, :status)");
        $statement->bindParam(":vloga", $vloga);
        $statement->bindParam(":first_name", $ime);
        $statement->bindParam(":last_name", $priimek);
        $statement->bindParam(":email", $email);
        $statement->bindParam(":password", $password);
        $statement->bindParam(":status", $status);
        
        $statement->execute();
    }

    public static function updatePassword($id, $geslo) {
        $db = DBInit::getInstance();

        $statement = $db->prepare("UPDATE uporabnik SET geslo = :geslo WHERE id =:id");
        $statement->bindParam(":geslo", $geslo);
        $statement->bindParam(":id", $id, PDO::PARAM_INT);
        $statement->execute();
    }
    
    public static function insertOrder($postavka, $neobdelano, $stranka) {
        $db = DBInit::getInstance();
        
        $statement = $db->prepare("INSERT INTO racun (postavka, status, stranka_id) VALUES (:postavka, :status, :stranka)");
        $statement->bindParam(":postavka", $postavka);
        $statement->bindParam(":status", $neobdelano);
        $statement->bindParam(":stranka", $stranka);
        $statement->execute();
    }

    public static function updateFirstName($id, $ime) {
        $db = DBInit::getInstance();

        $statement = $db->prepare("UPDATE uporabnik SET ime = :ime WHERE id =:id");
        $statement->bindParam(":ime", $ime);
        $statement->bindParam(":id", $id, PDO::PARAM_INT);
        $statement->execute();
    }
    
    public static function updateLastName($id, $priimek) {
        $db = DBInit::getInstance();

        $statement = $db->prepare("UPDATE uporabnik SET priimek = :priimek WHERE id =:id");
        $statement->bindParam(":priimek", $priimek);
        $statement->bindParam(":id", $id, PDO::PARAM_INT);
        $statement->execute();
    }
    
    public static function updateEmail($id, $email) {
        $db = DBInit::getInstance();

        $statement = $db->prepare("UPDATE uporabnik SET email = :email WHERE id =:id");
        $statement->bindParam(":email", $email);
        $statement->bindParam(":id", $id, PDO::PARAM_INT);
        $statement->execute();
    }
    
    public static function updatePhone($id, $telefon) {
        $db = DBInit::getInstance();

        $statement = $db->prepare("UPDATE uporabnik SET telefon = :telefon WHERE id =:id");
        $statement->bindParam(":telefon", $telefon);
        $statement->bindParam(":id", $id, PDO::PARAM_INT);
        $statement->execute();
    }
    
    public static function updateAddress($id, $naslov) {
        $db = DBInit::getInstance();

        $statement = $db->prepare("UPDATE uporabnik SET naslov = :naslov WHERE id =:id");
        $statement->bindParam(":naslov", $naslov);
        $statement->bindParam(":id", $id, PDO::PARAM_INT);
        $statement->execute();
    }
    
    public static function updateRacunStatus($id) {
        $db = DBInit::getInstance();
        $status = "odobreno";
        
        $statement = $db->prepare("UPDATE racun SET status = :status WHERE id =:id");
        $statement->bindParam(":status", $status);
        $statement->bindParam(":id", $id, PDO::PARAM_INT);
        $statement->execute();
    }
    public static function updateRacunStatus2($id) {
        $db = DBInit::getInstance();
        $status = "zavrnjeno";
        
        $statement = $db->prepare("UPDATE racun SET status = :status WHERE id =:id");
        $statement->bindParam(":status", $status);
        $statement->bindParam(":id", $id, PDO::PARAM_INT);
        $statement->execute();
    }
    
}
