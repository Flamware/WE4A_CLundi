<?php
$connected = false;
if(isset($_POST['submit'])) {
    include "function.php";
    $bdd = connect_to_db();
    try {
        // Prepared statement for secure execution
    $sql = "SELECT * FROM login WHERE username = ? AND password = ?";
    $stmt = $bdd->prepare($sql);
    $stmt->execute([$_POST['username'], $_POST['password']]);
    $result = $stmt->fetch();
    if($result){
        echo "User connected!";
        setcookie("login",$result['cles'],[
            'expires' => time() + 365*24*3600,
            'secure' => true,
            'httponly' => true,
        ]);
        setcookie("user",$result['username'],[
            'expires' => time() + 365*24*3600,
            'secure' => true,
            'httponly' => true,
        ]);
        ?>Bonjour <?php echo $_POST['username']; ?> !<br />
        <?php echo $_POST['password']; ?><br />
        <?php
        $connected = true;
        header("Location: file.php");
        }else{
            ?> <div>Wrong login or password</div> <?php
        }
    } catch (PDOException $e) {
        die('Error: ' . $e->getMessage());
    }


}
if($connected==false){
?>


<html lang="fr">
<head>
    <title>Titre de la page</title>
</head>
<body>
<form action="" method="POST">
    <div><label for="username">rentre un login :</label>
        <input id="username" type="text" name="username"  />
    </div>
    <div>
        <label for="password">rentre un password :</label>
        <input id="password" type="text" name="password"/>
    </div>
    <input type="submit" value="login" name="submit" />
</form>
<a href="subscribe.php">
    <div> cree un nouveau compte</div>
</a>
</body>
</html>
<?php } ?>
