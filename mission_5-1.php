<?php 



//MySQLに接続 
$dsn = 'データベース名'; 
$user = 'ユーザー名'; 
$password = 'パスワード'; 
$pdo=new PDO($dsn, $user, $password);  

//テーブルを作る tb　CREATE TABLE文 
//テーブルの中身　id(投稿番号) name（名前）comment(コメント) date(日付) pass(パスワード) 
$sql="CREATE TABLE tb" #tbというテーブルを作成
."(" 
."id INT not null auto_increment primary key,"//投稿番号　id(カラム名) int(整数)　NOT NULL制約（空白を入れない）AUTO_INCREMENT文（自動採番）PRIMARY KEY 制約（重複なし） 
."name char(32)," //名前　CHAR型　32文字まで 
."comment TEXT,"//コメント　TEXT型 
."date DATETIME,"//投稿日時　日時 
."pass TEXT" //パスワード　TEXT型 
.");"; 
$stmt=$pdo->query($sql);//接続実行　入力を使わないから簡易版＝queryのみでquery、prepare、executeの一連の動作を実行　 



?> 


<!DOCTYPE html> 
<html lang="ja"> 
<head> 
<meta charset ="UTF-8"> 

</head> 
<body> 

<?php 

//新規投稿と編集実行（投稿フォーム） 
if (!empty($_POST['name']) && !empty($_POST['comment']) && !empty($_POST['passcode'])) { 

/* 
txt読み込み時の新規投稿と編集機能（投稿フォーム）　大枠　変数定義 
    $filename = "Mission3-5.txt";   
if (!empty($_POST['name']) && !empty($_POST['comment']) && !empty($_POST['passcode'])) { 

    $name = $_POST['name']; 
    $comment = $_POST['comment']; 
    $date = date("Y/m/d H:i:s"); 
    $password=$_POST['passcode']; 
*/ 

  //新規投稿　SQLにデータを追加する機能 
  if(isset($_POST['submit']) && empty($_POST['edit-number'])){     
  //MySQlに接続 
  $dsn = 'mysql:dbname=tb220838db;host=localhost'; 
  $user = 'tb-220838'; 
  $password = 'tNAHs9m3YD'; 
  $pdo=new PDO($dsn, $user, $password);  
        $pdo=new PDO($dsn, $user, $password);  
        //変数定義(入力フォームの新規投稿時) 
        $name = $_POST['name']; 
        $comment = $_POST['comment']; 
        $date = date("Y/m/d H:i:s"); 
        $password=$_POST['passcode']; 
  //テーブルに投稿内容挿入 
      //接続実行　入力情報を使う＝prepare文　prepare⇒bindValue⇒executeでデータの追加＝INSERT文 INSERT INTO テーブル名（カラム１）VALUE（データの値ここではパラメーター） 
      //テーブルの中身に値を入れていく 
        $sql=$pdo->prepare("INSERT INTO tb(name, comment,date,pass)VALUES (:name,:comment,:date,:pass)");  
        //パラメーターに値を与える　$sql(デーブルを実行した変数)->bindParam　パラメーター,値（入力の変数）,PDO::PARAM_STR(文字列で型を指定) 
        $sql->bindParam(':name', $name, PDO::PARAM_STR);  //名前 
        $sql->bindParam(':comment', $comment, PDO::PARAM_STR); //コメント 
        $sql->bindParam(':date', $date, PDO::PARAM_STR);//日時 
        $sql->bindParam(':pass', $password, PDO::PARAM_STR);//パスワード 
   
        $sql-> execute();//prepareを実行（）＝execute関数 
        //SQLインジェクション対策として、データ入力の際にはprepare文を経てexcuteで実行  
  } 

/* 
txt読み込み時の新規投稿機能 
 if (empty($_POST['edit-number'])) { 
       
        if (file_exists($filename)) { 
                $data = file($filename); 
                $max = 0; 
                foreach ($data as $line) { 
                    list($number, $name, $comment, $timestamp, $password) = explode("<>", $line); 
                    if (intval($number) > $max) { 
                        $max = intval($number); 
                    } 
                } 
                $number = $max + 1; 
            } else { 
                $number = 1; 
            } 
} 
 $list = $number . "<>" . $name . "<>" . $comment . "<>" . $date."<>".$password."<>";   
      $fp = fopen($filename,"a"); 
      fwrite($fp,$list . "\n"); 
      fclose($fp); 

*/ 

  //編集実行　SQLのidが一致したデータを上書きする機能 
  else{  
    //変数定義(入力フォームの編集選択された時) 
    $edit_number=$_POST['edit-number'];         
    $pass2=$_POST['passcode'];                   
          
         //MySQLに接続 
        $dsn = 'データベース名'; 
　　　　　$user = 'ユーザー名'; 
        $password = 'パスワード'; 
         $pdo=new PDO($dsn, $user, $password);  

          //全てのデータ取り出し　SELECT*FROM　テーブル名 
         $sql='SELECT*FROM tb';  
         //queryを変数で定義して、その変数を使ってループ 
         $results= $pdo->query($sql); 
           foreach ($results as $row){   
             //CREATE TABLE文で定義したid,passを使用 
             //投稿番号が編集番号と同じ（見えない）でかつパスワードが投稿フォームで入力したものと同じ時 
               if($row['id']==$edit_number && $row['pass']==$pass2){   
                 //変数定義 
                  $id2=$edit_number; 
                  $name2=$_POST['name']; 
                  $comment2=$_POST['comment']; 
                  
                  $date2 = date("Y/m/d H:i:s"); 
                  //UPDATE文　既に入ってるデータを上書きする update テーブル名　データ='値'　WHEREで特定のレコードを検索　 
                  $sql="update tb set name='$name2', comment='$comment2', date='$date2', pass='$pass2' where id ='$id2'"; 
                  //実行 
                  $results=$pdo->query($sql); 
                }  
            }  
  } 
}  
/* 
txt読み込み時の編集実行 
else{ 

    $edit_number = $_POST['edit-number']; //読み込んだファイルの中身を配列に格納する 
    $ret_array = file($filename); 
    $fp = fopen($filename,"w");     //ファイルを開き、中身を空に  

    foreach ($ret_array as $line) {   //配列の数だけループ 
    $edit_date= explode("<>",$line); 

        //新稿番号と編集番号が一致・不一致で分ける 
      if ($edit_date[0] == $edit_number) { 

       //編集のフォームで送信された値で上書きする（$edit_numberが$numberと変わっている） 
      fwrite($fp, $edit_number. "<>" . $name . "<>" . $comment . "<>" . $date . "\n"); 


      } else { 
        fwrite($fp,$line);  //不一致なら書き込む 
     } 
     } 
        fclose($fp); 

      }　編集実行閉じ 
  }  大枠閉じ 
*/ 
?> 

<?php 

//削除(削除フォーム)　SQLからidが一致するデータを消去する機能 
//削除ボタンを押した 
if(isset($_POST['delete'])){   

  //削除番号とパスワードが入力されている 
  if(!empty($_POST['deleteNo']) && !empty($_POST['Delpasscode'])){    
      //変数定義（削除フォーム）　 
    $Delpassword=$_POST['Delpasscode'] ; 
    $delete=$_POST['deleteNo']; 
    
   //MySQLに接続 
        $dsn = 'データベース名'; 
　　　　　$user = 'ユーザー名'; 
        $password = 'パスワード';  
   $pdo=new PDO($dsn, $user, $password);   
   
  //全てのデータを取り出す 
    $sql='SELECT*FROM tb';  
    //query を利用してループ 
    $results= $pdo->query($sql); 
    foreach ($results as $row){   
        //CREATE TABLE文で定義したidと削除番号が一致して、CREATE TABLE文で定義したpassと削除フォームで入力したパスワードと一致 
     if($row['id']== $delete and $row['pass']== $Delpassword){  
     $id=$delete; 
     //delete文　既に入っているデータを消去　WHERE句で対象レコードを指定し、その行の内容全て消去(id,name,comment,date,pass) 
     $sql="delete from tb where id=$id";  
     //実行 
     $result=$pdo->query($sql); 
     } 
    } 
 } 
} 
/* 
txt読み込み時の削除機能 
if(!empty($_POST['deleteNo']) && !empty($_POST['Delpasscode'])){   


    $Delpassword=$_POST['Delpasscode'] ; 
    $delete=$_POST['deleteNo'];  
    $delcon=file("Mission3-5.txt");  
    $fp=fopen("Mission3-5.txt","w"); 


    for($j=0; $j<count($delcon); $j++){  
    $deldata=explode("<>",$delcon[$j]);  


    if($deldata[0] != $delete){  
    fwrite($fp,$delcon[$j]);  

    }else{ 
    fwrite($fp, "");  
    } 
    } 

    fclose($fp);  
} 
*/ 
?> 

<?php 

//編集選択(編集フォーム)　投稿フォームに表示する機能 
//編集ボタンを押したとき 
if(isset($_POST['edit2'])){   

//編集番号とパスワードを入力してある 
 if(!empty($_POST['edit']) && !empty($_POST['Editpasscode'])){   
     //変数定義（編集フォーム）  
    $editN=$_POST['edit'];         
    $pass_edit=$_POST['Editpasscode'];    
    
   //MySQLに接続 
        $dsn = 'データベース名'; 
　　　　　$user = 'ユーザー名'; 
        $password = 'パスワード'; 
   $pdo=new PDO($dsn, $user, $password);   
   
  //全データの取り出し 
    $sql='SELECT*FROM tb';  
    //queryを使ったループ 
    $results= $pdo->query($sql); 
    foreach ($results as $row){ 
        //CREATE TABLE文で定義したidと編集番号があっていて、CREATE TABLE文で定義した編集フォームでpassと入力したパスワードと一致   
     if($row['id']==$editN and $row['pass']==$pass_edit){     
         //編集選択の変数を定義して、入力フォームに表示する  
      $editname=$row['name']; 
      $editnumber=$row['id'];            
          $editcomment=$row['comment'];          
          $editpassword=$row['pass'];              
      } 
     
    } 
  } 
} 

/* 
txt読み込み時の編集選択機能 
if (!empty($_POST['edit']) &&!empty($_POST['Editpasscode'])) { 

    
        $Editpassword= $_POST['Editpasscode']; 
        $edit = $_POST['edit']; 

    
        $editCon = file($filename); 

      foreach ($editCon as $line) {   
      $editdata = explode("<>",$line); 

       
        if ($edit == $editdata[0]) { 

        
        $editnumber = $editdata[0]; 
        $editname = $editdata[1]; 
        $editcomment = $editdata[2]; 

     
      } 
        } 
} 
*/ 

?> 

<!--投稿フォーム--> 
<form action="" method="post"> 
<!--valueから各種変数が定義されると表示される--> 
<input type="text" name="name" placeholder="名前" value="<?php if(isset($editname)) {echo $editname;} ?>"><br> 
<input type="text" name="comment" placeholder="コメント" value="<?php if(isset($editcomment)) {echo $editcomment;} ?>"> 
<!--見えない,編集番号によって新規投稿か、編集実行か分かれる--> 
<input type="hidden" name="edit-number" value="<?php if(isset($editnumber)) {echo $editnumber;} ?>"> 
<!--見えない--> 
<input id="password" type="password" name="passcode" value="<?php if(isset($editpassword)) {echo $editpassword;} ?>" placeholder="パスワードを入力してください"> 
<input type="submit" name="submit" value="送信" > 
</form> 

<!--削除フォーム--> 
<form action="" method="post"> 
<input type="text" name="deleteNo" placeholder="削除対象番号"> 
<input id="password" type="password" name="Delpasscode"  value="" placeholder="パスワードを入力してください"> 
<input type="submit" name="delete" value="削除"> 
</form> 
<!--編集フォーム--> 
<form action="" method="post"> 
<input type="text" name="edit" placeholder="編集対象番号"> 
<input id="password" type="password" name="Editpasscode"  value="" placeholder="パスワードを入力してください"> 
<input type="submit" value="編集" name="edit2"> 
</form> 

<?php 


//表示 
//送信か削除か編集か押す 
if(isset($_POST['submit']) || isset($_POST['delete']) ||isset($_POST['edit2'])){ 
  //接続 
  $dsn = 'mysql:dbname=tb220838db;host=localhost'; 
  $user = 'tb-220838'; 
  $password = 'tNAHs9m3YD'; 
  $pdo=new PDO($dsn, $user, $password);  

//全データ取り出し 
$sql='SELECT*FROM tb';  
//queryによるループ、一つずつ表示 
$results= $pdo->query($sql); 
foreach ($results as $row){  
 echo $row['id'].'<>'.$row['name'].'<>'.$row['comment'].'<>'. $row['date'].'<br>';//pass以外 
}  
} 
/* 
txt読み込み時の表示 
$filename = "Mission3-5.txt"; 
      if (file_exists($filename)) {  
           $data = file($filename);  


        if(isset($_POST['submit']) || isset($_POST['delete']) ||isset($_POST['edit2'])) 
      {  
         foreach ($data as $line) {  
         $showdata = explode("<>",$line);   
         echo $showdata[0] . "<>" . $showdata[1] . "<>" . $showdata[2] . "<>" . $showdata[3]  ."<br>";  
         } 
      } しし
      } 
*/ 

?> 


</body> 
</html>
