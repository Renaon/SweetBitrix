<?php
use Bitrix\Main;

require_once($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/classes/general/user.php");

class addUser {
    public static function add(array $data){
        $create = new CUser;
        $pass = $data[0].'12345';
        $params = array(
            "EMAIL"             => 'sample@mail.ru',
            "LOGIN"             => $data[0],
            "NAME"              => $data[1],
            "LAST_NAME"         =>$data[2],
            "ACTIVE"            => "Y",
            "GROUP_ID"          => array(6,10),
            "PASSWORD"          => $pass,
            "CONFIRM_PASSWORD"  => $pass,
        );
        $ID = $create->Add($params);
    }

}