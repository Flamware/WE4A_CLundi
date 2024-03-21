
<?php

if (isset($_POST['submit']) and isset($_COOKIE['login'])) {
    include "function.php";
    $bdd = connect_to_db();
    try {
        // Prepared statement for secure execution
        $sql = "INSERT INTO message(date,text,writer) VALUES (?, ?, ?)";
        $stmt = $bdd->prepare($sql);
        $stmt->execute([time(), $_POST['content'], $_COOKIE['login']]);
        echo "Post added!";
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
