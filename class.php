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

class HoneySales extends \CBitrixComponent
{
    public function onPrepareComponentParams($params)
    {
        return $params;
    }

    public function executeComponent()
    {
        $this->includeComponentTemplate();
    }

    public function __construct(){
        $connect = new Docking();
        $pain = new GetBonuce($connect->connect1C('http://172.16.20.117:81/testing_sailing/ws/DiscountService.1cws'));

        (int) $balance = $pain->getResult();
        $identifer = $pain->getID();
        $opData = $pain->getData();

        $info = new Struct($identifer,$balance);

        $db = new DBConnect();
    }
}