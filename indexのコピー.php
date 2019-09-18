<?php

$error_message = null;
$message       = '';
$dbname        = 'Challenge_1';

$max_length_message = 200;
$min_length_message = 10;

try {
    $pdo = new PDO("mysql:dbname={$dbname};host=localhost", 'root', 'root');

    if (isset($_POST['message'])) {
        $message = $_POST['message'];

        if (strlen($message) > $max_length_message || strlen($message) < $min_length_message) {
            $error_message = "Your message must be {$min_length_message} to {$max_length_message} characters long";
        }

        if ($error_message === null) {
            $statment = $pdo->prepare('INSERT INTO messages (message) VALUES (:message)');
            $statment->bindParam(':message', $message, PDO::PARAM_STR);
            $statment->execute();
        }
    }
} catch (PDOException $Exception) {
    die('接続エラー: '.$Exception->getMessage());
}

$sql = $pdo->query('SELECT * FROM messages order by created_at desc');
if (!$sql) {
    $info = $pdo->errorinfo();
    exit($info[2]);
}

?>

<html>
  <head>
      <title>Bulletin board Level 1</title>
    <link rel="stylesheet" href="/stylesheet.css">
  </head>
  <body>
    <?php if ($error_message !== null) :?>
      <p class="error_message"><?php echo "{$error_message}"?></p>
    <?php endif ?>
    <form action="index.php" method="post" >
      <textarea name="message"></textarea><br><br>
      <input type="submit" value="Submit" id="submit_button"/>
    </form>
    <?php while ($posted_content = $sql->fetch()) :?>
      <div class="messages">
        <p class="message"><?php echo "{$posted_content['message']}"?></p>
        <p class="created_at"><?php echo "{$posted_content['created_at']}"?></p>
      </div>
    <?php endwhile ?>
  </body>

</html>
