<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5-1</title>
</head>
<tittle>
    <span style="font-size:32px;">苦手な食べ物</span>
</tittle>
<body>
    <form action="Mission_5-1.php" method="post">
        <input type="text" name="name" placeholder="お名前">
        <input type="text" name="text" placeholder="コメント">
        <input type="number" name="pass1" placeholder="パスワード">
        <input type="submit" name="submit" value="投稿">
    </form>
    
    <form action="Mission_5-1.php" method="post">
        <input type="number" name ="delete" placeholder="投稿番号">
        <input type="number" name="pass2" placeholder="パスワード">
        <input type="submit" name="submit2" value="削除">
    </form>
    
    <form action="Mission_5-1.php" method="post">
        <input type="number" name ="edit" placeholder="投稿番号">
        <input type="text" name="editname" placeholder="お名前">
        <input type="text" name="edittext" placeholder="コメント">
        <input type="number" name="pass3" placeholder="パスワード">
        <input type="submit" name="submit3" value="編集">
    </form>
    
    <?php
        //パスワード関数
        function pass($pdo, $id){
            $sql = 'SELECT pass FROM 掲示板 WHERE id=:id';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $results = $stmt->fetch();
            $key = $results['pass'];
            return $key;
        }
        
        //データーベース接続
        $dsn = 'mysql:dbname=データベース名;host=localhost';
        $user = 'ユーザ名';
        $password = 'パスワード';
        $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
        
        //テーブル作成
        $sql = "CREATE TABLE IF NOT EXISTS 掲示板"
        ." ("
        . "id INT AUTO_INCREMENT PRIMARY KEY,"
        . "name CHAR(32),"
        . "comment TEXT,"
        . "date TEXT,"
        . "pass INT"
        . ");";
        $stmt = $pdo->query($sql);
        
        //投稿機能
        if(!empty($_POST["name"]) && !empty($_POST["text"]) && !empty($_POST["pass1"]) && !empty($_POST["submit"])){
            $name = $_POST["name"];
            $comment = $_POST["text"];
            $date = date("Y/n/j G:i:s");
            $pass = $_POST["pass1"];
            
            $sql = "INSERT INTO 掲示板 (name, comment, date, pass) VALUES (:name, :comment, :date, :pass)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
            $stmt->bindParam(':date', $date, PDO::PARAM_STR);
            $stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
            $stmt->execute();
        }
        
        //削除機能
        if(!empty($_POST["delete"]) && !empty($_POST["pass2"]) && !empty($_POST["submit2"])){
            $id = $_POST["delete"];
            
            pass($pdo, $id);
            if($_POST["pass2"] == pass($pdo, $id)){
                $sql = 'delete from 掲示板 where id=:id';
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
            }
        }
        
        //編集機能
        if(!empty($_POST["edit"]) && !empty($_POST["editname"]) && !empty($_POST["edittext"]) && !empty($_POST["pass3"]) && !empty($_POST["submit3"])){
            $id = $_POST["edit"];
            $name = $_POST["editname"];
            $comment = $_POST["edittext"];
            $date = date("Y/n/j G:i:s");
            $pass = $_POST["pass3"];
            
            pass($pdo, $id);
            if($_POST["pass3"] == pass($pdo, $id)){
                $sql = 'UPDATE 掲示板 SET name=:name,comment=:comment,date=:date WHERE id=:id';
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
                $stmt->bindParam(':date', $date, PDO::PARAM_STR);
                $stmt->execute();
            }
        }
        
        //ブラウザに表示
        $sql = 'SELECT * FROM 掲示板';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){
            echo $row['id'].' ';
            echo $row['name'].' ';
            echo $row['comment'].' ';
            echo $row['date'].'<br>';
            echo "<hr>";
        }
    ?>
</body>