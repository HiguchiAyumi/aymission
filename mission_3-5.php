<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_3-5</title>
</head>
<body>
    <?php
    
    $filename = "mission_3-5.txt";
    
    /*新規作成フォームが空じゃないとき新規作成*/
    if(empty($_POST["num1"]) && !empty($_POST["str"]) && !empty($_POST["name"]) && !empty($_POST["password"])){
        
        $str = $_POST["str"];
        $name = $_POST["name"];
        $date = date("Y/m/d H:i:s");
        $pass = $_POST["password"];
    
        $fp = fopen($filename,"a");
        $count = count(file($filename))+1;
        
        $all = $count."<>".$name."<>".$str."<>".$date."<>".$pass."<>"."\n";
        
        fwrite($fp,$all);
        fclose($fp);
        
        
    }
    
    /*削除番号が空じゃないとき削除*/    
    elseif(!empty($_POST["num"])){
        $num = $_POST["num"];
        
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
                    
                }elseif(($comment[0] == $num) && ($comment[4] !== $_POST["passwordc"])){  //投稿番号と編集番号が一致してもパスワードが一致しないとき通常通りコピー
                    $fp = fopen($filename,"a");
                    fwrite($fp,$line."\n");
                    fclose($fp);
                }
            }
        }
    }
    /*編集番号が空じゃないとき新規作成フォームに編集対象を表示*/
    elseif(!empty($_POST["num2"])){
        $num2 = $_POST["num2"];
        
        /*mission_3-4.txtが存在するときのみ実行*/
        if(file_exists($filename)){
        $lines = file($filename,FILE_IGNORE_NEW_LINES);/*$filenameを一行ずつ取得*/
            foreach($lines as $line){
            $comment = explode("<>",$line);/*$linesを全ての行について<>を除いた配列として取得*/
                /*編集番号と$lineの投稿番号、パスワードが一致したとき番号と名前、内容を変数でおく*/
                if(($comment[0]==$num2) && ($comment[4] == $_POST["passworde"])){
                   $editnum=$comment[0];
                   $editname=$comment[1];
                   $editstr=$comment[2];
                   
                }
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
    //新規作成と編集を分ける
    if(!empty($_POST["num1"]) && !empty($_POST["name"]) && !empty($_POST["str"]) && !empty($_POST["password"])){
        $filename="mission_3-5.txt"; //テキストファイルの変数を指定
        //新規投稿フォームの中身について変数を指定
        $str2 = $_POST["str"];
        $name2 = $_POST["name"];
        $num3 = $_POST["num1"];
        $date = date("Y/m/d H:i:s");
        $pass = $_POST["password"];
        $all2 = $num3."<>".$name2."<>".$str2."<>".$date."<>".$pass."<>";
        
        //書き換え作業
        //mission3-4が存在するとき
            if(file_exists($filename)){
                $lines = file($filename,FILE_IGNORE_NEW_LINES);
                $fp = fopen($filename,"w");
                foreach($lines as $line){
                    $comment = explode("<>",$line);
                    //表示された編集番号と投稿番号が一致しないときだけコピー
                    if($comment[0] !== $num3){
                        $fp = fopen($filename,"a");
                        fwrite($fp,$line."\n");
                        fclose($fp);
                    }else{ //編集番号と投稿番号が一致したとき書き換え
                        $fp = fopen($filename,"a");
                        fwrite($fp,$all2."\n");
                        fclose($fp);
                    }
                }
            }    
        }
    ?>
    
                
</body>
</html>