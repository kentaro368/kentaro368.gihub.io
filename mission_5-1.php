<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5-1</title>
</head>
<body>
        
    <?php
        $dsn = 'データベース名';
	    $user = 'ユーザー名';
	    $password = 'パスワード';
	    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
	    $sql = "CREATE TABLE IF NOT EXISTS tbtest2"
	    ." ("
	    . "id INT AUTO_INCREMENT PRIMARY KEY,"
	    . "name char(32),"
	    . "comment TEXT,"
	    . "PostedAt char(32),"
	    . "password char(8)"
	    .");";
	    
	    
        
        $PostedAt = date("Y/m/d H:i:s");//年月日時分秒
        
        //編集番号を取得し，指定された投稿番号の名前とコメントと投稿日時とパスワードを取得
        if(isset($_POST['edit'])){
            $edi_num = $_POST['edi_num'];//編集番号を取得
            $edi_pass = $_POST['pass3'];
              
            //もし編集番号が空でなかったら以下を実行
            if($edi_num != null){
                //
                $sql = 'SELECT * FROM tbtest2';
	            $stmt = $pdo->query($sql);
	            $results = $stmt->fetchAll();
	            foreach ($results as $row){
		//$rowの中にはテーブルのカラム名が入る
		            if($row['id'] == $edi_num && $row['password'] == $edi_pass) {
		                $edi_num = $row['id'];
		                $edi_name = $row['name'];
		                $edi_comments = $row['comment'];
		            }
		        }
                
            }
        }    
    

    ?> 
    <!--入力フォームと送信ボタンなど作成-->
    <form action="" method="post"><!--action属性なし(送信先)、method="post" は本文がそのまま送信-->    
        <!---入力フォームと送信-->
        入力フォーム<?php if($edi_num){echo "（編集モード）";}  ?><br>
        <input type="hidden" name="edit_post" value="<?php echo $edi_num; ?>">
        <input type="text" name="name" placeholder="名前を入力してください" value="<?php echo $edi_name; ?>"><br><!--type=""text"は1行のテキストボックス 名前はstr-->
        <input type="text" name="comment" placeholder="コメントを入力してください"  value="<?php echo $edi_comments; ?>"><br>
        <input type="password" name = "pass1" placeholder="パスワードを入力してください">
        <input type="submit" name="submit"><br>
        
        
        
        <!---削除フォームと削除ボタン-->
        削除フォーム<br>
        <input type="num" name="del_num" placeholder="削除したい投稿番号を入力してください"><br>
        <input type="password" name = "pass2" placeholder="パスワードを入力してください">
        <button type ="submit" name = "delete">削除</button><br>
        
        <!---編集フォームと編集ボタン-->
        編集番号入力フォーム<br>
        <input type="num" name="edi_num" placeholder="編集したい投稿番号を入力してください"><br>
        <input type="password" name = "pass3" placeholder="パスワードを入力してください">
        <button type="submit" name="edit">編集</button><br>
        <!--type="submit"は送信ボタン作成、名前はsubmit-->
        
        
    </form>   
      
    
    <?php

        if(isset($_POST['submit'])){
            $after_edi_name = $_POST['name'];
            $after_edi_comment = $_POST['comment'];
            $after_edi_num = $_POST['edit_post'];
            $after_edi_pass = $_POST['pass1'];
            
            //もし編集番号が空でなかったら以下を実行
            if($after_edi_num != null){
                $id = $after_edi_num; //変更する投稿番号
	            $name = $after_edi_name;
	            $comment = $after_edi_comment; //変更したい名前、変更したいコメントは自分で決めること
	            $PostedAt = date("Y/m/d H:i:s");//年月日時分秒
	            $password = $after_edi_pass;
	                        
	            $sql = 'UPDATE tbtest2 SET name=:name,comment=:comment, PostedAt=:PostedAt, password=:password WHERE id=:id';
	            $stmt = $pdo->prepare($sql);
	            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
	            $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
    	        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    	        $stmt->bindParam(':PostedAt', $PostedAt, PDO::PARAM_STR);
    	        $stmt->bindParam(':password', $password, PDO::PARAM_STR);
    	        $stmt->execute();

                            
    
            //編集番号が空だったら。つまり追記する場合、以下を実行             
            }elseif($after_edi_num == null ){
                $addName = $_POST['name'];
                $addComment = $_POST['comment'];
                $PostedAt = date("Y/m/d H:i:s");//年月日時分秒
                $addPass = $_POST['pass1'];
                    
                    
                    
                $sql = $pdo -> prepare("INSERT INTO tbtest2 (name, comment, PostedAt, password) VALUES (:name, :comment, :PostedAt, :password)");
	            $sql -> bindParam(':name', $name, PDO::PARAM_STR);
	            $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
	            $sql -> bindParam(':PostedAt', $PostedAt, PDO::PARAM_STR);
	            $sql -> bindParam(':password', $password, PDO::PARAM_STR);
	            $name = $addName;
	            $comment = $addComment;
	            $PostedAt = $PostedAt;
	            $password = $addPass;
	            $sql -> execute();
            } 

            
                      
            
                
                
               
            
            
          
                    

                    
             
             
               
                    
                
            
            
    
        
            
        }elseif(isset($_POST['delete'])) {
            $del_num = $_POST['del_num'];
            $del_pass = $_POST['pass2'];
            $sql = 'SELECT * FROM tbtest2';
	        $stmt = $pdo->query($sql);
	        $results = $stmt->fetchAll();
	        foreach ($results as $row){
		//$rowの中にはテーブルのカラム名が入る
		        if($row['id'] == $del_num && $row['password'] == $del_pass){
            //投稿内容削除
                $id = $del_num;
	            $sql = 'delete from tbtest2 where id=:id';
	            $stmt = $pdo->prepare($sql);
	            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
	            $stmt->execute();
                

                }elseif($del_num == null){
                    echo "削除番号を入力してください";
                }else {
                    
                }
                }
        }else {
        }    
            
             
        
    
    
    
    ?>
    <?php
       //ファイル表示
        echo "投稿内容<br>";
        $sql = 'SELECT * FROM tbtest2';
	    $stmt = $pdo->query($sql);
	    $results = $stmt->fetchAll();
	    foreach ($results as $row){
		//$rowの中にはテーブルのカラム名が入る
		    echo $row['id'].',';
		    echo $row['name'].',';
		    echo $row['comment'].',';
		    echo $row['PostedAt'].',';
		    echo $row['password'].'<br>';
	    echo "<hr>";
	    
	    }
	    
    ?>

</body>
</html>