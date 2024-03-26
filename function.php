<?php
function connect_to_db(){
    //global $bdd;
    try {
        $bdd = new PDO('mysql:host=localhost;dbname=we4;charset=utf8', 'root', '', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        echo "connected to db";
    }
    catch (Exception $e) {
        die('Error : ' . $e->getMessage()); // print the error message
    }
    return $bdd;
}
function addb($bdd,$copi){
    $sql = "INSERT INTO login (id, login, password, ) VALUES (NULL, '".$copi['login']."', '".$copi['password']."')";
    $bdd->exec($sql);
    echo "New record created successfully";
}
function verif_login_not_taken($bdd, $login){
    $sql = "SELECT * FROM login WHERE username = ?";
    $stmt = $bdd->prepare($sql);
    $stmt->execute([$login]);
    $result = $stmt->fetch();
    if($result){
        return false;
    }
    return true;
}


function verify_code_user($bdd, $code){
    $sql = "SELECT * FROM login WHERE cles = ?";
    $stmt = $bdd->prepare($sql);
    $stmt->execute([$code]);
    $result = $stmt->fetch();
    if($result){
        return true;
    }

    return false;
}
function id_connection_generation($bdd){
    do{
        $key = "";
        for($i=1;$i<255;$i++) {
            $key .= mt_rand(0,9);
        }
    }while (verify_code_user($bdd, $key));
    return $key;
}
