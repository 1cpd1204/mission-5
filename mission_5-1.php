<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5-1</title>
</head>
    <tittle>
     <span style="font-size:24px;">好きな映画は何ですか？</span>
     </tittle>
<body>
    <form action="" method="post">
        <input type="text" name="name" placeholder="名前を入力してください">
        <input type="text" name="str" placeholder="コメントを入力してください">
        <input type="text" name="pass_id" placeholder="パスワードを入力">
        <input type="text" name="delete_id" placeholder="削除する番号を入力">
        <input type="text" name="edit_id" placeholder="編集する番号を入力">
        <input type="submit" name="submit" value="送信">
        <input type="submit" name="edit_submit" value="編集">
        <input type="submit" name="delete_submit" value="削除">
    </form>
    
<?php
$dsn = 'mysql:dbname=tb250442db;host=localhost';
$user = 'tb-250442';
$password = 'VMfy2wvMuh';
$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

// テーブル作成
    $sql = "CREATE TABLE IF NOT EXISTS tbtest123"
    ." ("
    ."id INT AUTO_INCREMENT PRIMARY KEY,"
    ."name VARCHAR(32),"
    ."comment TEXT,"
    ."password VARCHAR(32)"
    .");";
    $stmt = $pdo->query($sql);

if (isset($_POST["pass_id"])) {
    $password = $_POST["pass_id"];
}

if (!empty($_POST["name"]) && !empty($_POST["str"])) {
    $name = $_POST["name"];
    $str = $_POST["str"];
    $password = $_POST["pass_id"]; // 新規投稿時にパスワードを保存

    $sql = "INSERT INTO tbtest123 (name, comment, password) VALUES (:name, :comment, :password)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':comment', $str, PDO::PARAM_STR);
    $stmt->bindParam(':password', $password, PDO::PARAM_STR);
    $stmt->execute();
}

if (isset($_POST["delete_submit"])) {
    $delete_id = $_POST["delete_id"];
    $password = $_POST["pass_id"]; // 削除時にパスワードを確認
    $a = $password;

    $sql = "DELETE FROM tbtest123 WHERE id = :delete_id AND password = :password";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':delete_id', $delete_id, PDO::PARAM_INT);
    $stmt->bindParam(':password', $a, PDO::PARAM_STR);
    $stmt->execute();
}

if (isset($_POST["edit_submit"])) {
    $edit_id = $_POST["edit_id"];

    $sql = "SELECT * FROM tbtest123 WHERE id = :edit_id AND password = :password";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':edit_id', $edit_id, PDO::PARAM_INT);
    $stmt->bindParam(':password', $password, PDO::PARAM_STR);
    $stmt->execute();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $name = $_POST["name"];
        $edited_comment = $_POST["str"];

        $sql = "UPDATE tbtest123 SET name = :name, comment = :edited_comment WHERE id = :edit_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':edited_comment', $edited_comment, PDO::PARAM_STR);
        $stmt->bindParam(':edit_id', $edit_id, PDO::PARAM_INT);
        $stmt->execute();
    }
}

$sql = "SELECT * FROM tbtest123";
$stmt = $pdo->query($sql);

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo $row['id'] . "<br>" . $row['name'] . "<br>" . $row['comment'] .  "<br>";
}
?>

    </body>
</html>
