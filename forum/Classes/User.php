<?php
class User
{
    private $CurDate;
    private $Username = "Unlogined";
    private $CurId;
    public function getCurId()
    {
        return $this->CurId;
    }
    public function setCurId($CurId)
    {
        $this->CurId = $CurId;
    }
    public function UserRegistration($username, $email, $password, $link) // Функция заноса пользователя в базу данных
    {
        $this->CurDate = date('Y-m-d H:i:s');

        $query = "INSERT INTO `users` ( `User_Name`, `User_Password`, `User_Role`, `User_SignUpDate`, `User_Img`, `User_email`) VALUES
                    ( '$username', '$password', '1', '$this->CurDate', NULL, '$email')";

        mysqli_query($link, $query);

        if (mysqli_errno($link) == 1062) {
            $Errors = array("");
            array_push($Errors, "<li>Пользователь с таким именем или почтой уже зарегистрирован! Теперь вы можете войти!</li>");

            return $Errors;
        }
        if (mysqli_errno($link) == "") {
            $this->setUsername($username);
            $this->setCurId(mysqli_insert_id($link));

        }

    }

    public function RegistrationCheck($login, $password, $ConfPass, $email, $link)//Функция проверки правильности вводимых пользователем данных на предмет кирилицы, адреса почты и тд.
    {
        $RegErrors[0] = '<ul>';
        if (preg_match('/\p{Cyrillic}/u', $login)) {
            array_push($RegErrors, '<li>Русские символы недоступны при регистрации!</li>');
        }
        if ($password != $ConfPass) {
            array_push($RegErrors, '<li>Пароли должны совпадать!</li>');
        }
        if (strlen($password) < 4) {
            array_push($RegErrors, '<li>Пароль слишком короткий! Введите более 5 символов для успешной проверки!</li>');
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            array_push($RegErrors, "<li>Адрес указан не правильно.</li>");
        }


        if (count($RegErrors) <= 1) {//Если массив с ошибками имеет более одного значения (первый элемент массива - тег открытия списка ошибок)

            include_once "Crypto.php";
            $CryptedPass = new Crypto();
            $CryptedPass->CryptoAction($password,"");//Шифрование пароля методом цезаря
            $password = $CryptedPass->getCryptedPass();


            $SuccesQuery = $this->UserRegistration($login, $email, $password, $link);//Вызов функции с заносом пользователя в базу данных

            if (isset($SuccesQuery)) {
                $RegErrors = array_merge($RegErrors, $SuccesQuery);
            }

        }
        array_push($RegErrors, '</ul>');
        return $RegErrors;//В конце функция возвращает массив ошибок.

    }


    public function setUsername($Username)
    {
        $this->Username = $Username;
    }


    public function getUsername()
    {

        return $this->Username;
    }

    public function UserLogIn($login,$password,$link){

        $query="Select * from users where User_Name ='$login'";


        $res=mysqli_query($link,$query);

        if(mysqli_num_rows($res)>0){
            include_once "CryptoLog.php";
            $UserInfo=mysqli_fetch_assoc($res);
           $SignUpDate=$UserInfo['User_SignUpDate'];
            $CryptoLog=new CryptoLog();
           $CryptoLog->CryptoAction($password,$SignUpDate);
            $CryptedPass=$CryptoLog->getCryptedPass();

            if($UserInfo['User_Password']==$CryptedPass){
                $_SESSION['CurUser']=$login;
                $_SESSION['CurUserId']=$UserInfo['Id_User'];

            }
        }
    }







}