<html>
<head>
    <title>Subscribe</title>
</head>
<body>
<?php if(isset($_POST['submit'])) {
    include "function.php";
    $bdd = connect_to_db();

    try {
        if(verif_login_not_taken($bdd, $_POST['username'])){
            echo "Login not taken";
            // Prepared statement for secure execution
            $key = id_connection_generation($bdd);
            $sql = "INSERT INTO login(username,password,cles) VALUES (?, ?, ?)";
            $stmt = $bdd->prepare($sql);
            $stmt->execute([$_POST['username'], $_POST['password'], $key]);
            echo "User registration successful!"; // Success message
            setcookie("login",$key,[
                'expires' => time() + 365*24*3600,
                'secure' => true,
                'httponly' => true,
            ]);
            setcookie("user",$_POST['user'],[
                'expires' => time() + 365*24*3600,
                'secure' => true,
                'httponly' => true,
            ]);
            header("Location: file.php");
        }
        else{
            echo "Login already taken";
            header("Location: subscribe.php");

        }
        // Prepared statement for secure execution

    } catch (PDOException $e) {
        die('Error: ' . $e->getMessage());
    }

    ?>Bonjour <?php echo $_POST['username']; ?> !<br />
    <?php echo $_POST['password']; ?><br />
    <?php
} else { ?>

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
</html>
<?php } ?>