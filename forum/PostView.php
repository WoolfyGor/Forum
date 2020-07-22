<?php
session_start();
include_once "Classes/Posts.php";
include_once "Classes/DatabaseCon.php";
$Condb=new DatabaseCon();
$link=$Condb->getlink();
if(isset($_POST['IdInput'])){
    $idpost=$_POST['IdInput'];
    $post=new Posts();
    $PostArray=$post->ViewPost($idpost,$link);
}
if(isset( $_SESSION['CreatedPost'])){
    $post=new Posts();
   $id=$_SESSION['CreatedPost'];
    $PostArray=$post->ViewPost($id,$link);
    unset($_SESSION['CreatedPost']);
}
if(isset($_POST['like'])){
$IdPost=$_POST['like'];
    $IdUser=$_SESSION['CurUserId'];
mysqli_query($link,"INSERT INTO `likes` (`Id_Like`, `Id_User`, `Id_Post`) VALUES (NULL, '$IdUser', '$IdPost')");
mysqli_error($link);
    $post=new Posts();
    $PostArray=$post->ViewPost($IdPost,$link);
}

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">  <link href="https://fonts.googleapis.com/css?family=Exo+2&display=swap" rel="stylesheet">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="MainStyle.css">
    <title>Пост</title>
</head>
<body>
<header><img src="img/forum.png" alt="logo" width="200" height="auto"><div class="navblock"><nav><a href="index.php">Главная</a><a
                href="PostAddPage.php">Добавить пост</a><a
                href="AccountPage.php">Личный кабинет</a>
            <a href="LogOut.php">Выйти</a></nav> </div> </header>
<div class="PostBody">
    <?php if(isset($PostArray)){foreach ($PostArray as $item) { echo $item;}}?>
</div>
</body>
</html>