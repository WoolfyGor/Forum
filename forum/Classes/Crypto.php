<?php


class Crypto
{
public function GetCurDate(){
    return $shift=strlen(date('l'))/2;

}
    private $cryptedPass;
public function CryptoAction($pass,$date){
    $shift=$this->GetCurDate();
    $arr_en=['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z'];
    $arr_num=['0','1','2','3','4','5','6','7','8','9'];
    $arr_symp=['!', '@', '#', '$', '%', '&', '?', '-', '+', '=', '~'];
    $pass= mb_convert_encoding($pass,"utf8");
    $pass_arr=str_split($pass);
    foreach ($pass_arr as $key=>$item) {
        foreach ($arr_en as $enkey=> $en){
            if(!strcasecmp($en,$item)){
                $newKey=$enkey+$shift;
                while($newKey>(count($arr_en)-1)){
                    $newKey-=count($arr_en);
                }
                if(ctype_upper($item)){
                    $NewPass[$key]=mb_strtoupper($arr_en[$newKey]);
                }
                else{
                    $NewPass[$key]=$arr_en[$newKey];
                }
            }
        }
        foreach ($arr_num as $numkey=> $num) {
            if(!strcasecmp($num,$item)){
                $newKey=$numkey+$shift;
                while($newKey>(count($arr_num)-1)){
                    $newKey-=count($arr_num);
                }
                $NewPass[$key]=$arr_num[$newKey];
            }
        }
        foreach ($arr_symp as $symkey=>$symp){

            if (!strcasecmp($item,$symp)){
                $newKey=$symkey+$shift;
                while($newKey>(count($arr_symp)-1)){
                    $newKey-=count($arr_symp);
                }
                $NewPass[$key]=$arr_symp[$newKey];
            }
        }
    }
    $newPassFull=implode($NewPass);
    $this->cryptedPass=md5(sha1($newPassFull));

}


    public function getCryptedPass()
    {
        return $this->cryptedPass;
    }




}