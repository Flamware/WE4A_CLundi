<html>
    <head>
        <title>File</title>
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>
    <body>
    <div class="menu">
        <div class="champ de recherche">
            <form action="file.php" method="post">
                <input type="text" name="recherche" placeholder="Recherche">
                <br>
                <input type="submit" value="Rechercher">
            </form>
        </div>
        <div class="logo">
            <img src="logo.png" alt="Logo">
        </div>
        <a href="login%20in.php">
            <div class="profil">
                Profil
            </div>
        </a>
        <a href="new.php">
            <div class="new post">
                New Post
            </div>
        </a>
        <div class="notifiction">
            Notification
        </div>
    <div class="file">
        <?php
    if(isset($_COOKIE['login'])){
        include "function.php";
        $bdd = connect_to_db();
        $sql = "SELECT * FROM message where writer = '".$_COOKIE['login']."'";
        $stmt = $bdd->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        foreach ($result as $row){
            echo "<div class='post'>";
            echo "<div class='text'>".$row['text']."</div>";
            echo "</div>";
        }
    }
        ?>

    </div>
    <div class="contact">
        Contact
    </div>
        <div class="pologne">
            pologne
        </div>
    </div>




    </body>
</html>
