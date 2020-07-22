<?php

class Posts
{
    public function CreatePost($title,$text,$fName,$FSize,$FType,$FTmpFile,$link){

$RegErrors=$this->PostValidation($title,$text,$fName,$FSize,$FType,$FTmpFile);

if(count($RegErrors)<=2){
    $user=$_SESSION['CurUserId'];
    move_uploaded_file($FTmpFile, "files/$fName");
$query="INSERT INTO `posts` ( `Post_Title`, `Post_Text`, `Post_Author`, `Post_CreationDate`, `Post_Img`) VALUES ( '$title', '$text', '$user', current_timestamp(), 'files/$fName')";
    mysqli_query($link,$query);
    $_SESSION['CreatedPost']=mysqli_insert_id($link);
 header("Location:PostView.php");
}
else {
return $RegErrors;
}
}
public function PostValidation($title,$text,$fName,$FSize,$FType,$FTmpFile){
    $WhiteList=array('png','jpg','jpeg');
    $RegErrors[0] = '<ul>';

    $FCheck=false;
 if(strlen($title)>100|strlen($title)<5){
array_push($RegErrors,"<li>Тема поста должна быть длинне 5 но меньше 50 символов!</li>");
 }
 if(strlen($text)>10000|strlen($text)<10){

     array_push($RegErrors,"<li>Пост слишком маленький (менее 10 символов) или слишком большой (больше 100000) Сократите или увеличьте название.</li>");
 }
 if($FSize>2097152){
     array_push($RegErrors,"<li>Картинка слишком большая! загрузить файл размером меньше</li>");
 }
 if($FTmpFile!=""){
     $mimeCheck=mime_content_type($FTmpFile);
 foreach ($WhiteList as $item) {
     if ("image/".$item == $mimeCheck) {
     $FCheck=true;
         break;
     }}
 if(!$FCheck){
     array_push($RegErrors, "<li>Загруженный файл не картинка!</li>");
 }}
array_push($RegErrors,"</ul>");
return $RegErrors;
}
public function ViewPost($id,$link){
$UserLikes="";
$LikesCount="";
        $query="SELECT Id_Post,Post_Title,Post_Text,users.User_Name,Post_CreationDate,Post_Img
FROM `posts`,users
WHERE posts.Post_Author=users.Id_User and posts.Id_Post='$id'";
        $res=mysqli_query($link,$query);
        $query2="   Select count(DISTINCT Id_User) as'TotalLikes' from likes  where id_post='$id' 
                    UNION(
                    SELECT users.User_Name
                    FROM users,likes
                    WHERE users.Id_User=likes.Id_User AND likes.Id_Post='$id'
                    ORDER BY users.User_Name DESC
                    LIMIT 11)";
        $res2=mysqli_query($link,$query2);
       while($result=mysqli_fetch_assoc($res)){
           $title=$result['Post_Title'];
           $text=$result['Post_Text'];
           $author=$result['User_Name'];
           $CreationDate=date('d.m.Y H:i',strtotime($result['Post_CreationDate']));
           $img=$result['Post_Img'];
        while ($result2=mysqli_fetch_assoc($res2)){
          if($LikesCount==0){
              $LikesCount=array_shift($result2);
          }
          else{$UserLikes.=array_shift($result2).", ";}
}
       }
       $PostArray[0]='<div>';

       if($img!="files/"){
           $img="<img src=$img alt='Картинка пользователя' class='PostImg'>";
       }
       else{
           $img="";
       }
       $likeform="<form method='post'><input type='submit' width='30px' height='30px' value='+' name='like'><input type='hidden' name='like' value='$id'></form>";
       array_push($PostArray,"<h2>$title</h2><p>$text</p> <p>$author,$CreationDate</p>
 Понравилось: $LikesCount пользователям, а в том числе:$UserLikes $likeform<br> $img</div>");
return $PostArray;
}
public function PostsList($link){
        $PostsArray[0]="";
        $query="SELECT id_Post,Post_Title,Post_Text,users.User_Name,Post_CreationDate 
FROM posts,users
WHERE users.Id_User=Posts.Post_Author
order by id_Post DESC";
        $res=mysqli_query($link,$query);

        while ($result=mysqli_fetch_assoc($res)){
        $id=$result['id_Post'];
           $title=$result['Post_Title'];
           $text=$result['Post_Text'];
           $Author=$result['User_Name'];
           $date=$result['Post_CreationDate'];

            $NormalDate=date('d.m.Y H:i',strtotime($date));
            array_push($PostsArray,"<div class='Post'><input type='submit' class='IdInput' value='$id' name='IdInput'>
<h4>$title </h4> <div class=\"PostInfo\"> <span class=\"info\">".substr($text,0,300)."...</div>
<span class=\"AuthorSpan\">$Author <span class=\"PostDate\">$NormalDate</span></span> </div>" );
        }

        return$PostsArray;
}
public function ShowPostList($id,$link,$action){
        $query="Select * from posts where Post_Author='$id'";
        $res=mysqli_query($link,$query);
    $form="<form method='post'> <select name='PostId' id='PostId'>";
        while ($result=mysqli_fetch_assoc($res))
        {
            $idPost=$result['Id_Post'];
            $PostTitle=$result['Post_Title'];
            $form.="<option value=$idPost>$PostTitle</option> ";
            }
            $form.="</select> <input type='hidden' name='Action' value='$action'> <input type='submit' name='SelectedPost' value='Выберите пост для действия'></form>";
        return $form;
}
public function DeletePostAction($id,$link){
        $query="Delete from posts where Id_Post='$id'";
        mysqli_query($link,$query);

}
public function ShowUpdatePostForm($id,$link,$idPost){
    $query="Select * from posts where Id_Post ='$id'";
$res=mysqli_query($link,$query);
while($result=mysqli_fetch_assoc($res)){
$PostTitle=$result['Post_Title'];
$PostText=$result['Post_Text'];
$EditForm="<form method='post' name='SendPost''><input type='hidden' name='postId' value='$id'> 
<label for=\"PostTitle\">Тема поста:</label> <br><input type='text' id='PostTitle'name='PostTitle' value='$PostTitle'><br>
<label for=\"PostText\" class=\"PostLabel\">Содержание поста:</label><br><textarea  id='PostText' name='PostText' cols='60' rows='20'>$PostText</textarea>
 <br><input type='submit' name='PostUpd' value ='Отправить пост'>";

}

    return $EditForm;}

    public function UpdatePost($id,$title,$text,$link){
        $query="UPDATE `posts` SET `Post_Title` = '$title', `Post_Text` = '$text' WHERE `posts`.`Id_Post` = '$id'";
        mysqli_query($link,$query);
    }
}