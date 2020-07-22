<?php
session_start();
include_once "Classes/Posts.php";
include_once "Classes/DatabaseCon.php";
$Condb=new DatabaseCon();
$link=$Condb->getlink();
if(!isset($_SESSION['CurUser']))
{
    header("Location:index.php");
}
if(isset($_POST['PostedPost'])){
    $title=$_POST['PostTheme'];
    $PostText=$_POST['PostText'];
    $FName=$_FILES['PostPictures']['name'];
   $FSize= $_FILES['PostPictures']['size'];
   $FType=$_FILES['PostPictures']['type'];
   $TmpFile=$_FILES['PostPictures']['tmp_name'];
$post=new Posts();
$RegError=$post->CreatePost($title,$PostText,$FName,$FSize,$FType,$TmpFile,$link);
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">  <link href="https://fonts.googleapis.com/css?family=Exo+2&display=swap" rel="stylesheet">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Добавить пост</title>
    <link rel="stylesheet" href="MainStyle.css">
</head>
<body>
<header><img src="img/forum.png" alt="logo" width="200" height="auto"><div class="navblock"><nav><a href="index.php">Главная</a><a
                href="PostAddPage.php">Добавить пост</a><a
                href="AccountPage.php">Личный кабинет</a> <a
                    href="LogOut.php">Выйти </a> </nav> </div>
</header>
<div class="contentPostAdd"> <div class="FormDiv">
    <form method="post" class="AddpostForm" enctype="multipart/form-data">

        <label for="PostTitle">Тема поста</label>
        <input type="text" id="PostTitle" class="PostTitleLable" name="PostTheme" placeholder="Тема"><br>
        <label for="PostText" class="PostLabel">Содержание поста</label><br>
        <textarea name="PostText"  id="PostText" class="PostTextArea" cols="60" rows="20"></textarea>
        <br>
        <label for="PostPictures">Прикрепите изображения</label><br>
        <input type="file" name="PostPictures" id="PostPictures"><br>
        <input type="submit" name="PostedPost">
    </form>
        <?php if($RegError){foreach ($RegError as $item) {echo $item;}}?>
    </div>
</div>
</body>
</html>
