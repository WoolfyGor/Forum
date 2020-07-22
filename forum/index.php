<?php
session_start();
include_once "Classes/User.php";
include_once "Classes/DatabaseCon.php";
include_once "Classes/Posts.php";
$Condb=new DatabaseCon();
$link=$Condb->getlink();
if(isset($_POST['SendedReg'])){
    $login=$_POST['RegLog'];
    $pass=$_POST['RegPass'];
    $ConfPass=$_POST['RegPassConf'];
    $email=$_POST['e-mail'];
    $UserRegAlgor=new user;
    $Errors=$UserRegAlgor->RegistrationCheck($login,$pass,$ConfPass,$email,$link);
    if (count($Errors)==2){
        $_SESSION['CurUser']=$UserRegAlgor->getUsername();
    }
}

if(isset($_POST['SendedLog']))
{
    $login=$_POST['LogLog'];
    $pass=$_POST['LogPass'];
    $UserAuthorization= new User;
    $UserAuthorization->UserLogIn($login,$pass,$link);

}
?>
<!doctype html>
<html lang="en">
<head>
    <link href="https://fonts.googleapis.com/css?family=Exo+2&display=swap" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport"

          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="MainStyle.css">
    <title>Главная</title>
</head>
<body>
<header><img src="img/forum.png" alt="logo" class="logo" width="200" height="auto"><div class="navblock"><nav><a href="index.php">Главная</a><a
                    href="PostAddPage.php">Добавить пост</a><a
                    href="AccountPage.php">Личный кабинет</a> <a
                    href="LogOut.php">Выйти </a> </nav> </div> </header>
<div class="CurPage"><h2>Добро пожаловать, <?php if(isset($_SESSION['CurUser'])) { echo $_SESSION['CurUser'];} else{echo "Гость";}?></h2></div>
<div class="content">
    <div class="ForumBlock">
        <form action="PostView.php" name="SelectedPost" method="post">
<?php $posts=new Posts();
$postsArray=$posts->PostsList($link);
foreach ($postsArray as $item){echo $item;}
?>
        </form>
        </div>
    <div class="LoginBLock" <?  if(!isset($_SESSION['CurUser'])){echo "style=\"display:block\"";}?>><h3>Войдите в свой аккаунт!</h3>
    <form method="post">
        <label for="LogLog">Ваш логин:</label><br>
        <input type="text" id="LogLog" name="LogLog" placeholder="Логин"><br>
        <label for="LogPass">Ваш пароль:</label><br>
        <input type="password" id="LogPass" name="LogPass" placeholder="Пароль"> <br>
        <br>
        <input type="submit" name="SendedLog" value="Войти!">
    </form>
        <br>
        <br>
        <hr>
        <?
        if(count($Errors)>=3) {
            foreach ($Errors as $item){echo $item;}  unset($Errors);
        }
        elseif(count($Errors)==2){
            echo "Регистрация успешна!";
            $_SESSION['CurUser']=$UserRegAlgor->getUsername();
            $_SESSION['CurUserId']=$UserRegAlgor->getCurId();
            echo $_SESSION['CurUserId'];
        }
      ?>
        <form  method="post">
            <label for="RegLog">Ваш логин:</label><br>
            <input type="text" id="RegLog" name="RegLog" placeholder="Логин"><br>
            <label for="RegPass">Ваш пароль:</label><br>
            <input type="password" id="RegPass" name="RegPass" placeholder="Пароль"> <br>
            <label for="RegPassConf">Подтвердите ваш пароль:</label><br>
            <input type="password" id="RegPassConf" name="RegPassConf" placeholder="Пароль"> <br>
            <br>
            <label for="e-mail">Введите вашу почту:</label><br>
            <input type="text" id="e-mail" name="e-mail" placeholder="Адрес почты"> <br>
            <br>
            <input type="submit" name="SendedReg" value="Присоедеинитесь к нам!">
        </form>



    </div>

</body>
</html>
