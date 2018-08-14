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
/*
    public static function getAllRole($user_role) {
        $db = DBInit::getInstance();

        $statement = $db->prepare("SELECT user_id, role, first_name, last_name, email FROM user WHERE user_role = :user_role");
        $statement->bindParam(":user_role", $user_role);
        $statement->execute();

        return $statement->fetchAll();
    }

    public static function delete($user_id) {
        $db = DBInit::getInstance();

        $statement = $db->prepare("DELETE FROM user WHERE user_id = :user_id");
        $statement->bindParam(":user_id", $user_id, PDO::PARAM_INT);
        $statement->execute();
    }

    public static function get($user_id) {
        $db = DBInit::getInstance();

        $statement = $db->prepare("SELECT user_id, role, first_name, last_name, email, password, status FROM user 
            WHERE user_id =:user_id");
        $statement->bindParam(":user_id", $user_id, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetch();
    }

    public static function insert($role, $first_name, $last_name, $email, $password, $phone, $address, $status) {
        $db = DBInit::getInstance();

        $statement = $db->prepare("INSERT INTO user (role, first_name, last_name, email, password, phone, address, status)
            VALUES (:role, :first_name, :last_name, :email, :password, :phone, :address, :status)");
        $statement->bindParam(":role", $role);
        $statement->bindParam(":first_name", $first_name);
        $statement->bindParam(":last_name", $last_name);
        $statement->bindParam(":email", $email);
        $statement->bindParam(":password", $password);
        $statement->bindParam(":phone", $phone);
        $statement->bindParam(":address", $address);
        $statement->bindParam(":status", $status);
        
        $statement->execute();
    }
*/
    public static function updatePassword($id, $geslo) {
        $db = DBInit::getInstance();

        $statement = $db->prepare("UPDATE uporabnik SET geslo = :geslo WHERE id =:id");
        $statement->bindParam(":geslo", $geslo);
        $statement->bindParam(":id", $id, PDO::PARAM_INT);
        $statement->execute();
    }
/*    
    public static function updateFirstName($user_id, $first_name) {
        $db = DBInit::getInstance();

        $statement = $db->prepare("UPDATE user SET first_name = :first_name WHERE user_id =:user_id");
        $statement->bindParam(":first_name", $first_name);
        $statement->bindParam(":user_id", $user_id, PDO::PARAM_INT);
        $statement->execute();
    }
    
    public static function updateLastName($user_id, $last_name) {
        $db = DBInit::getInstance();

        $statement = $db->prepare("UPDATE user SET last_name = :last_name WHERE user_id =:user_id");
        $statement->bindParam(":last_name", $last_name);
        $statement->bindParam(":user_id", $user_id, PDO::PARAM_INT);
        $statement->execute();
    }
    
    public static function updateEmail($user_id, $email) {
        $db = DBInit::getInstance();

        $statement = $db->prepare("UPDATE user SET email = :email WHERE user_id =:user_id");
        $statement->bindParam(":email", $email);
        $statement->bindParam(":user_id", $user_id, PDO::PARAM_INT);
        $statement->execute();
    }
    
    public static function updatePhone($user_id, $phone) {
        $db = DBInit::getInstance();

        $statement = $db->prepare("UPDATE user SET phone = :phone WHERE user_id =:user_id");
        $statement->bindParam(":phone", $phone);
        $statement->bindParam(":user_id", $user_id, PDO::PARAM_INT);
        $statement->execute();
    }
    
    public static function updateAddress($user_id, $address) {
        $db = DBInit::getInstance();

        $statement = $db->prepare("UPDATE user SET address = :first_name WHERE user_id =:user_id");
        $statement->bindParam(":address", $address);
        $statement->bindParam(":user_id", $user_id, PDO::PARAM_INT);
        $statement->execute();
    }
*/
    
    
}
