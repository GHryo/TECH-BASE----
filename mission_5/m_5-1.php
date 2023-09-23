<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>mission_5-1</title>
    </head>
    <body>
        <h1>好きな食べ物</h1>
        
    <?php
        
        //データベースへ接続
        $dsn = 'mysql:dbname=データベース名;host=localhost';
        $user = 'ユーザ名';
        $password = 'パスワード';
        $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
        
        //データベース内にテーブルを作成
        $sql = "CREATE TABLE IF NOT EXISTS tbm5_1"
        ." ("
        . "id INT AUTO_INCREMENT PRIMARY KEY,"
        . "name char(32),"
        . "comment TEXT,"
        . "date DATETIME,"
        . "password TEXT"
        .");";
        $stmt = $pdo->query($sql);
        
        //投稿フォーム処理
        //名前とコメントが入力され、かつ”edit_NO”がなかったら
        if(!empty($_POST["name"]) && !empty($_POST["str"]) && empty($_POST["edit_NO"])){
            //パスワードがあったら
            if(!empty($_POST["pass"])){
                
                //変数に代入
                $name = $_POST["name"];
                $comment = $_POST["str"];
                $date = date("Y/m/d H:i:s");
                $pass = $_POST["pass"];
            
                //テーブルにデータを入力
                $sql = "INSERT INTO tbm5_1 (name, comment, date, password) VALUES (:name, :comment, :date, :password)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
                $stmt->bindParam(':date', $date, PDO::PARAM_STR);
                $stmt->bindParam(':password', $pass, PDO::PARAM_STR);
                $stmt->execute();
            //パスワードがなかったら
            }else{
                
                $name = $_POST["name"];
                $comment = $_POST["str"];
                $date = date("Y/m/d H:i:s");
            
                //テーブルにデータを入力
                $sql = "INSERT INTO tbm5_1 (name, comment, date, password) VALUES (:name, :comment, :date, null)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
                $stmt->bindParam(':date', $date, PDO::PARAM_STR);
                $stmt->execute();
            }
        
        //削除フォーム処理
        //削除対象番号が入力されたら
        }elseif(!empty($_POST["d_num"])){
            
            //変数に代入
            $d_num = $_POST["d_num"];
            $d_pass = $_POST["d_pass"];
            
            //入力したデータレコードを抽出し、表示する
            $sql = 'SELECT * FROM tbm5_1';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            
        //編集フォーム処理
        //edit_NOがあったら
        }elseif(!empty($_POST["edit_NO"])){
            
            //変数に代入
            $edit_num = $_POST["edit_NO"];
            $edit_name = $_POST["name"];
            $edit_com = $_POST["str"]; 
            $edit_date = date("Y/m/d H:i:s");
            $edit_pass = $_POST["pass"];
            
            //入力したデータレコードを抽出し、表示する
            $sql = 'SELECT * FROM tbm5_1';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
        }
        
        //編集番号フォームに既存の投稿を表示させるためのコード
        //編集対象番号が入力されたら
        if(!empty($_POST["e_num"])){
                
            $editp_num = $_POST["e_num"];
            
             //入力したデータレコードを抽出し、表示する
            $sql = 'SELECT * FROM tbm5_1';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            foreach ($results as $row){
                        
                $p_num = $row['id'];
                
                //編集したい既存の投稿の番号と編集対象番号、かつパスワードと編集パスワードが一致したら    
                if($p_num == $editp_num && $row['password'] == $_POST["e_pass"]){
                            
                    $edit_name = $row['name'];
                    $edit_com = $row['comment'];
                    $edit_pass = $row['password'];
                    break;
        
                }
            }
        }
        
    ?>
    
        <form action="" method="post">
        <!-- 投稿フォームを作成 --> 
            <!-- 名前入力欄 --> 
            <input type="text" name="name" placeholder="名前" value="<?php if(!empty($_POST["e_num"]) && !empty($_POST["e_pass"])){echo $edit_name;} ?>"><br>
            <!-- コメント入力欄 -->
            <input type="text" name="str" placeholder="コメント" value="<?php if(!empty($_POST["e_num"]) && !empty($_POST["e_pass"])){echo $edit_com;} ?>"><br>
                
            <input type="hidden" name="edit_NO" placeholder="編集番号" value="<?php if(!empty($_POST["e_num"]) && !empty($_POST["e_pass"])){echo $editp_num;} ?>">
            <!-- 投稿パスワード入力欄 -->  
            <input type="text" name="pass" placeholder="パスワード" value="<?php if(!empty($_POST["e_num"]) && !empty($_POST["e_pass"])){echo $edit_pass;} ?>">
            <!-- 送信ボタン -->
            <input type="submit" name="submit" value="投稿"><br>
            
        <!-- 削除フォームを作成 -->             
                
            <input type="number" name="d_num" placeholder="削除対象番号"><br>
            <!-- 削除パスワード入力欄 -->  
            <input type="text" name="d_pass" placeholder="削除パスワード">
            <!-- 送信ボタン --> 
            <input type="submit" name="delete" value="削除"><br>

        <!-- 編集番号指定用フォームを作成 -->             
            <!-- 編集対象番号入力欄 -->                  
            <input type="number" name="e_num" placeholder="編集対象番号"><br>
            <!-- 編集パスワード入力欄 -->  
            <input type="text" name="e_pass" placeholder="編集パスワード">
            <!-- 送信ボタン -->                
            <input type="submit" name="edit" value="編集">

        </form>
    
    <?php
        //投稿機能ブラウザ表示
        if(!empty($_POST["name"]) && !empty($_POST["str"]) && empty($_POST["edit_NO"])){
            //パスワードがあったら
            if(!empty($_POST["pass"])){
                //入力したデータレコードを抽出し、表示する
                $sql = 'SELECT * FROM tbm5_1';
                $stmt = $pdo->query($sql);
                $results = $stmt->fetchAll();
                foreach ($results as $row){
                
                    echo $row['id'].',';
                    echo $row['name'].',';
                    echo $row['comment'].',';
                    echo $row['date'].'<br>';
                    echo "<hr>";
                }
            //パスワード入力されていなかったら（パスワードがなくても投稿可能）
            }else{
                $sql = 'SELECT * FROM tbm5_1';
                $stmt = $pdo->query($sql);
                $results = $stmt->fetchAll();
                foreach ($results as $row){
                
                    echo $row['id'].',';
                    echo $row['name'].',';
                    echo $row['comment'].',';
                    echo $row['date'].'<br>';
                    echo "<hr>";
                }
            }
        
        //削除機能ブラウザ表示
        }elseif(!empty($_POST["d_num"])){
             foreach ($results as $row){
                //パスワードがあったら 
                if(!empty($_POST["d_pass"])){
                    
                    //パスワードが一致していたら
                    if($row['id'] == $d_num && $row['password'] == $d_pass){
                
                        //テーブルからデータレコードを削除
                        $sql = 'delete from tbm5_1 where id=:id AND password=:password';
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(':id', $d_num, PDO::PARAM_INT);
                        $stmt->bindParam(':password', $d_pass, PDO::PARAM_INT);
                        $stmt->execute();
                        
                        $sql = 'SELECT * FROM tbm5_1';
                        $stmt = $pdo->query($sql);
                        $results = $stmt->fetchAll();
                        foreach ($results as $row){
                            echo $row['id'].',';
                            echo $row['name'].',';
                            echo $row['comment'].',';
                            echo $row['date'].'<br>';
                            echo "<hr>";
                        }
                        
                    //パスワードが一致していなかったら（削除できない）
                    }else{
                        echo $row['id'].',';
                        echo $row['name'].',';
                        echo $row['comment'].',';
                        echo $row['date'].'<br>';
                        echo "<hr>";
                    }
                //パスワードが入力されていなかったら（削除できない）
                }else{
                    echo $row['id'].',';
                    echo $row['name'].',';
                    echo $row['comment'].',';
                    echo $row['date'].'<br>';
                    echo "<hr>";
                }
            }
        
        //変種機能ブラウザ表示
        }elseif(!empty($_POST["e_num"])){
            foreach ($results as $row){
                //パスワードがあったら
                if(!empty($_POST["e_pass"])){
                     //パスワードが一致していたら
                    if($row['password'] == $_POST["e_pass"]){
                        //$rowの中にはテーブルのカラム名が入る
                        echo $row['id'].',';
                        echo $row['name'].',';
                        echo $row['comment'].',';
                        echo $row['date'].'<br>';
                        echo "<hr>";
                    //パスワードが一致していなかったら    
                    }else{
                        echo $row['id'].',';
                        echo $row['name'].',';
                        echo $row['comment'].',';
                        echo $row['date'].'<br>';
                        echo "<hr>";
                    }
                //パスワード入力されていなかったら
                }else{
                    echo $row['id'].',';
                    echo $row['name'].',';
                    echo $row['comment'].',';
                    echo $row['date'].'<br>';
                    echo "<hr>";
                }
            }
            
        }elseif(!empty($_POST["edit_NO"])){
             foreach ($results as $row){
                
                if($row['id'] == $edit_num){
                    if($row['password'] == $edit_pass){
                            
                        //テーブルのデータレコードを編集
                        $sql = 'UPDATE tbm5_1 SET name=:name,comment=:comment,date=:date WHERE id=:id';
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(':name', $edit_name, PDO::PARAM_STR);
                        $stmt->bindParam(':comment', $edit_com, PDO::PARAM_STR);
                        $stmt->bindParam(':date', $edit_date, PDO::PARAM_STR);
                        $stmt->bindParam(':id', $edit_num, PDO::PARAM_INT);
                        $stmt->execute();
                        
                        //入力したデータレコードを抽出し、表示する
                        $sql = 'SELECT * FROM tbm5_1';
                        $stmt = $pdo->query($sql);
                        $results = $stmt->fetchAll();
                        foreach ($results as $row){
                            //$rowの中にはテーブルのカラム名が入る
                            echo $row['id'].',';
                            echo $row['name'].',';
                            echo $row['comment'].',';
                            echo $row['date'].'<br>';
                            echo "<hr>";
                        }
                    }else{
                        $sql = 'SELECT * FROM tbm5_1';
                        $stmt = $pdo->query($sql);
                        $results = $stmt->fetchAll();
                        foreach ($results as $row){
                            echo $row['id'].',';
                            echo $row['name'].',';
                            echo $row['comment'].',';
                            echo $row['date'].'<br>';
                            echo "<hr>";
                        }
                    }
                }
            }
        }
      
    ?>
        
    </body>
</html>