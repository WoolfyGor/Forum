<?php

include_once "Crypto.php";
class CryptoLog extends Crypto
{

public  function GetNeededShift($Date){
    $shift=strlen(date('l',$Date))/2;
    return $shift;
}
public function CryptoAction($pass, $Date)
{

    $shift=$this->GetNeededShift($Date);

    return parent::CryptoAction($pass,$Date);
}

}