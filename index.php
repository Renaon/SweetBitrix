<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

require('controller/Docking.php');
require('controller/GetBonuce.php');
require('service/DBConnect.php');
require('service/Struct.php');


use Bitrix\Main\Config\Configuration;
use Bitrix\Main\DB\Connection;
use Bitrix\Main\DB\SqlHelper;
use Bitrix\Main\DB\Result;
use Bitrix\Main\Application;

class Main
{

    public function __construct()
    {

    $connect = new Docking();
    $pain = new GetBonuce($connect->connect1C('http://172.16.20.113:80/testing_sailing/ws/ITFDiscountService.1cws'));

    $pain->getAllData();
    $querr = new DBConnect();
    $nums_users = $pain->getSize();
    $logins = $pain->getLogin();
    $card_IDs = $pain->getCadrIDs();
    $arr_balance = $pain->getCardBal();
    $card_names = $pain->getCardNames();

    for($i = 0; $i<$nums_users-1; $i++){
        $id = $card_IDs[$i];
        $querr->createUser($logins[$i]);
        $querr->createCard($card_IDs[$i], $arr_balance[$id], $card_names[$i], $i);
        $querr->putDiscount($card_IDs[$i], $arr_balance[$id]);
    }

    }
}