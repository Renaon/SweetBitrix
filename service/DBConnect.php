<?php
use Bitrix\Main\Entity;
use Bitrix\Main\Application;
require ('/home/bitrix/www/bitrix/.settings.php');
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

class DBConnect extends Entity\DataManager {
    private $connect;

    public function __construct(){

        $connect = Application::getConnection();
        $this->connect = $connect;
        $sqlHelper = $connect->getSqlHelper();

    }

    public function createUser($login, $email = null){
        $login = trim($login);
        $login = explode(" ", $login);
        $login = $this->clearArr($login);
        $name = $login[1];
        $lastname = $login[0];
        $enter = $lastname.$name;
        if(!$this->searchUser($login)) {
            $sql = "INSERT INTO b_user(LOGIN,NAME,LAST_NAME, EMAIL) VALUES('$enter', '$name', '$lastname', '$email');";
            $recordset = $this->connect->query($sql);
        }
        else return;
    }

    private function clearArr($array){
        $tmp = array();
        for($i = 0; $i<count($array); $i++){
            if($array[$i] != "") $tmp[] = $array[$i];
        }
        return $tmp;
    }

    public function dropUser($login){
        $sql = "DELETE FROM b_user WHERE LOGIN='$login';";
        $recordset = $this->connect->query($sql);
    }

    public function putDiscount($card_id, $balance, $name){
        $sql = "UPDATE b_loyality SET SALE_ID = '$card_id', VALUE = '$balance', NAME = '$name'";
        $recordset = $this->connect->query($sql);
    }

    private function searchCard($card_id){
        $sql = "SELECT ID FROM b_loyality WHERE CARD_ID = '$card_id';";
        $recordset = $this->connect->query($sql);
        while ($record = $recordset->fetch(\Bitrix\Main\Text\Converter::getHtmlConverter()))
        {
            $data[] = $record;
        }
        if($data != null) return true;
        else return false;
    }

    private function searchUser($login){
        $name = $login[1];
        $lastname = $login[0];
        $enter = $lastname.$name;
        $sql = "SELECT ID FROM b_user WHERE LOGIN = '$enter';";
        $recordset = $this->connect->query($sql);
        $data = array();
        while ($record = $recordset->fetch(\Bitrix\Main\Text\Converter::getHtmlConverter()))
        {
            $data[] = $record;
        }
        if($data != null) return true;
        else return false;
    }

    public function createCard($card_id, $balance = 0,$name){
        $sql = "INSERT INTO b_loyality(SALE_ID, NAME, VALUE) VALUES('$card_id','$name','$balance');";
        $recordset = $this->connect->query($sql);
    }


}