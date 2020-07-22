<?php


class DatabaseCon
{
    public $link;
    public function getlink(){
        return $this->link;
    }
public function __construct()
{
 $this-> link=mysqli_connect('localhost','root','','forum_db');
}

}