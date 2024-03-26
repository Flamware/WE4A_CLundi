<script src="function.js"></script>
<?php
if(isset($_GET['follow']) and isset($_GET['user'])){
    require_once dirname(__FILE__).'/function.php';
    $bdd=connect_to_db();
    $sql="INSERT INTO follow (follower,followed) VALUES (?,?)";
    $stmt = $bdd->prepare($sql);
    $stmt->execute([$_COOKIE['user'],$_GET['follow']]);


}
?>
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
            <?php
            if(isset($_COOKIE['user_view'])){
                echo "<form action='file.php' method='GET'>";
                echo "<input type='submit' value=$_COOKIE[user_view] name='follow' >";
                echo "</form>";
            }

            ?>
            <div class="new post">
                New Post
            </div>
        </a>
        <div class="notifiction">
            Notification
        </div>
    <div class="file">
        <?php
    if(isset($_COOKIE['login'])) {
        if (isset($_COOKIE['user_view'])) {
            require_once dirname(__FILE__) . '/function.php';
            $bdd = connect_to_db();
            $sql = "SELECT * FROM post where writer = '" . $_COOKIE['user_view'] . "'";
            $stmt = $bdd->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll();
            foreach ($result as $row) {
                echo "<div class='post'>";
                echo "<div class='text'>" . $row['text'] . "</div>";
                echo "</div>";
                }
            }
        else{
                include "function.php";
                $bdd = connect_to_db();
                $sql = "SELECT * FROM post order by date";
                $stmt = $bdd->prepare($sql);
                $stmt->execute();
                $result = $stmt->fetchAll();
                foreach ($result as $row) {
                    echo "<div class='post'>";
                    echo "<div class='text'>" . $row['text'] . "</div>";
                    echo "</div>";
                    echo "<form action='file.php' method='GET'>";
                    echo "<input type='submit' value=$row[writer] name='user_view' >";}
            }


    }
        ?>

    </div>
    <div class="contact">
        <?php
        if(isset($_COOKIE['login'])){
            require_once dirname(__FILE__).'/function.php';
            $bdd=connect_to_db();
            $sql="select followed from login join follow on login.username=follow.follower where login.cles=? ";
            $stmt = $bdd->prepare($sql);
            $stmt->execute([$_COOKIE['login']]);
            $result = $stmt->fetch();
            if(!$result){
                echo "pas de contact";
            }else{
                foreach ($result as $raw){
                    echo "<div class ='text'>".$raw['text']."</div>";
                }
            }



        }
        ?>

    </div>
        <div class="pologne">
            pologne
        </div>
    </div>




    </body>
</html>
