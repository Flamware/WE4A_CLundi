
<?php

if (isset($_POST['submit']) and isset($_COOKIE['user'])) {
    include "function.php";
    $bdd = connect_to_db();
    try {
        // Prepared statement for secure execution
        $sql = "INSERT INTO post(date,text,writer) VALUES (?, ?, ?)";
        $stmt = $bdd->prepare($sql);
        date_default_timezone_set('Europe/Paris');
        $date = date('y-m-d h:i:s');
        $stmt->execute([$date, $_POST['content'], $_COOKIE['user']]);
        echo "Post added!";
        header("Location: file.php");
    } catch (PDOException $e) {
        die('Error: ' . $e->getMessage());
    }
}
    ?>



    <html>
    <head>
        <title>New post</title>
    </head>

    <body>
    <form action="" method="POST">
        <div><label for="title">rentre un titre :</label>
            <input id="title" type="text" name="title"  />
        </div>
        <div>
            <label for="content">rentre un contenu :</label>
            <input id="content" type="text" name="content"/>
        </div>
        <input type="submit" value="envoyer" name="submit" />
</form>
    </body>
    </html>
