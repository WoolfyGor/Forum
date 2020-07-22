<?php
session_start();
include_once "Classes/DatabaseCon.php";
include_once "Classes/User.php";
include_once"Classes/Posts.php";
$UserSet=new User();
$UserPost=new Posts();
$link=new DatabaseCon();
$link=$link->getlink();
if(!isset($_SESSION['CurUser']))
{
header("Location:index.php");
}
if(isset($_POST['Setting'])){
    if($_POST['Setting']){
        $PostSelectForm=$UserPost->ShowPostList($_SESSION['CurUserId'],$link,$_POST['Setting']);
    }
}
if(($_POST['Action']=="Удалить посты")){
    $UserPost->DeletePostAction($_POST['PostId'],$link);
    echo mysqli_error($link);
}
if(($_POST['Action']=="Изменить посты")){
    $UpdateForm=$UserPost->ShowUpdatePostForm($_POST['PostId'],$link,"");
}
if(isset($_POST['PostUpd'])){
$UserPost->UpdatePost($_POST['postId'],$_POST['PostTitle'],$_POST['PostText'],$link);
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
    <title>Личный кабинет</title>
</head>
<body>
<header><img src="img/forum.png" alt="logo" width="200" height="auto"><div class="navblock"><nav><a href="index.php">Главная</a><a
                href="PostAddPage.php">Добавить пост</a><a
                href="AccountPage.php">Личный кабинет</a> <a
                href="LogOut.php">Выйти </a> </nav> </div> </header>

<div class="contentAc">
    <form action="" method="post" name="ChooseSetting">
        <input type="submit" name="Setting"value="Обновить аккаунт">
        <input type="submit" name="Setting" value="Изменить посты">
        <input type="submit" name="Setting" value="Удалить посты">
    </form>
    <?php if(isset($PostSelectForm)){echo $PostSelectForm;} if(isset($UserPost)){echo $UpdateForm;}?>
</div>
</body>
</html>
