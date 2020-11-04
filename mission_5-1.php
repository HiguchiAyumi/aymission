<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5-1</title>
</head>
<body>
<?php
    // DB接続設定
    $dsn = 'mysql:dbname=***;host=localhost';
	$user = 'tb-***';
	$password = 'PASSWORD';
	$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));


    //投稿したものを入れるテーブルクリア
    $sql = "CREATE TABLE IF NOT EXISTS mission5"
	." ("
	. "id INT AUTO_INCREMENT PRIMARY KEY," //投稿番号
	. "name char(32)," //名前
	. "comment TEXT," //コメント
	. "time datetime," //日付
	. "pass char(16)" //パスワード
	.");";
	$stmt = $pdo->query($sql);
	
?>
    


<?php
    
    
    //新規作成フォームが空じゃないとき(投稿番号が空のとき)新規作成
    if(empty($_POST["num1"]) && !empty($_POST["str"]) && !empty($_POST["name"]) && !empty($_POST["password"])){
    
        
        
        $str = $_POST["str"];
        $name1 = $_POST["name"];
        //$date = date("Y/m/d H:i:s");
        $passn = $_POST["password"];
        
       
        
    //書き込み
        $sql = $pdo -> prepare("INSERT INTO mission5 (name, comment, time, pass) VALUES (:name, :comment, cast(now() as datetime), :pass)");
        $sql -> bindParam(':name', $name, PDO::PARAM_STR);
        $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
        //timeは変数をバインドする必要がない（後から何も入れない）のでbindParamが要らない？
        $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
        $name = $name1;
        $comment = $str;
        $pass = $passn;
        $sql -> execute();
        
        //テーブルへの書き込み完了
            
        
    }
    
    
    
    
    //削除番号が空じゃないとき削除クリア    
    elseif(!empty($_POST["num"]) && !empty($_POST["passwordc"])){
        $id = $_POST["num"];
        $passc = $_POST["passwordc"];
        
        //パスワードを抽出
        $sql = 'SELECT * FROM mission5 WHERE id = :id';
    	$stmt = $pdo->prepare($sql);
    	$stmt->bindParam(':id', $id, PDO::PARAM_INT);
    	$stmt->execute();
    	$results = $stmt->fetchAll();
        	foreach($results as $row){
        	    //$rowの中にはテーブルのカラム名が入る
        	    $pass = $row['pass'];
        	}
        
        //パスワードが一致するとき実行
        if($passc == $pass){
        
        //sqlで削除

        $sql = 'delete from mission5 where id=:id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        }
        
        /*
        if(file_exists($filename)){
            $lines = file($filename,FILE_IGNORE_NEW_LINES);
            $fp = fopen($filename,"w");
            foreach($lines as $line){
                $comment = explode("<>",$line);
                if($comment[0] !== $num){  //投稿番号と編集番号が一致しないとき書き込み
                    $fp = fopen($filename,"a");
                    fwrite($fp,$line."\n");
                    fclose($fp);
                }elseif(($comment[4] == $_POST["passwordc"]) && ($comment[0] == $num)){  //投稿番号と編集番号が一致するかつパスワードが一致するときだけ何もしない
                    
                }elseif(($comment[0] == $num) && ($comment[4] == $_POST["passwordc"])){  //投稿番号と編集番号が一致してもパスワードが一致しないとき通常通りコピー
                    $fp = fopen($filename,"a");
                    fwrite($fp,$line."\n");
                    fclose($fp);
                }
            }
        }
        */
        
        
    }
    
    
    //編集番号が空じゃないとき新規作成フォームに編集対象を表示
    elseif(!empty($_POST["num2"]) && !empty($_POST["passworde"])){
        $id = $_POST["num2"];
        $passe = $_POST["passworde"];
        
        //パスワードを抽出
        $sql = 'SELECT * FROM mission5 WHERE id = :id';
    	$stmt = $pdo->prepare($sql);
    	$stmt->bindParam(':id', $id, PDO::PARAM_INT);
    	$stmt->execute();
    	$results = $stmt->fetchAll();
        	foreach($results as $row){
        	    //$rowの中にはテーブルのカラム名が入る
        	    $pass = $row['pass'];
        	}
        
        //パスワードが一致するとき編集
        if($passe == $pass){
        
        //SQLで編集対象を抽出
        $sql = 'SELECT * FROM mission5 WHERE id = :id';
    	$stmt = $pdo->prepare($sql);
    	$stmt->bindParam(':id', $id, PDO::PARAM_INT);
    	$stmt->execute();
    	$results = $stmt->fetchAll();
        	foreach($results as $row){
            	$editnum = $row['id'];
                $editname = $row['name'];
                $editstr = $row['comment'];
        	    //$rowの中にはテーブルのカラム名が入る
        	    //$ = $row['pass'];
        	}
        }
    }
    
?>

    <h1>ペットは飼ってますか？</h1>
    <form action="" method="post">
        <input type="hidden" name="num1" 
                value="<?php 
                        echo $editnum; /*送信した編集番号を表示*/
                        ?>">
        <input type="text" name="name" placeholder="名前"
                value="<?php 
                        echo $editname;
                        ?>">
        <input type="text" name="str" placeholder="コメント"
                value="<?php 
                        echo $editstr;
                        ?>">
        <input type="password" name="password" placeholder="パスワードを入力">
        <input type="submit" name="submit1">
    </form>
    
    <form action="" method="post">
        <input type="number" name="num" placeholder="削除対象番号">
        <input type="password" name="passwordc" placeholder="パスワードを入力">
        <input type="submit" name="submit2" value="削除">
        
    </form>
    
    <form action="" method="post">
        <input type="number" name="num2" placeholder="編集対象番号">
        <input type="password" name="passworde" placeholder="パスワードを入力">
        <input type="submit" name="submit3" value="編集">
    </form>

<?php
        
        /*
        //mission_3-4.txtが存在するときのみ実行
        if(file_exists($filename)){
        $lines = file($filename,FILE_IGNORE_NEW_LINES);//$filenameを一行ずつ取得
            foreach($lines as $line){
            $comment = explode("<>",$line);//$linesを全ての行について<>を除いた配列として取得
                //編集番号と$lineの投稿番号、パスワードが一致したとき番号と名前、内容を変数でおく
                if(($comment[0]==$num2) && ($comment[4] == $_POST["passworde"])){
                   $editnum=$comment[0];
                   $editname=$comment[1];
                   $editstr=$comment[2];
                   
                }
            }
        }
        
        
    }
    */
    
    
    //新規作成と編集を分ける
    if(!empty($_POST["num1"]) && !empty($_POST["name"]) && !empty($_POST["str"]) && !empty($_POST["password"])){
        
        
        //新規投稿フォームの中身について変数を指定
        $str2 = $_POST["str"];
        $name2 = $_POST["name"];
        $num3 = $_POST["num1"];
        //$date = date("Y/m/d H:i:s");
        $passedit = $_POST["password"];
        //$all2 = $num3."<>".$name2."<>".$str2."<>".$date."<>".$pass."<>";
        
        
        //書き換え作業
        //sqlで編集(UPDATE)
        $id = $num3; //変更する投稿番号
    	$name = $name2;
    	$comment = $str2; //変更したい名前、コメントを入力
    	$sql = 'UPDATE mission5 SET name=:name, comment=:comment, time=cast(now() as datetime), pass=:pass WHERE id=:id';
    	$stmt = $pdo->prepare($sql);
    	$stmt->bindParam(':name', $name, PDO::PARAM_STR);
    	$stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
    	$stmt->bindParam(':id', $id, PDO::PARAM_STR);
    	$stmt->bindParam(':pass', $passedit, PDO::PARAM_STR);
    	$stmt->execute();
        
    }
    
    $sql = 'SELECT * FROM mission5';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
    	foreach($results as $row){
    	    //$rowの中にはテーブルのカラム名が入る
    	    echo $row['id'].',';
    	    echo $row['name'].',';
    	    echo $row['comment'].',';
    	    echo $row['time'].',';
    	    echo $row['pass'].'<br>';
        echo "<hr>";
    	}

        


    
    
    ?>
    

    
    
    
                
</body>
</html>