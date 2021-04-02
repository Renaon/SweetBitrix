<?php namespace discount_nuts;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

require('controller/Docking.php');
require('controller/GetBonuce.php');
require('service/DBConnect.php');


use Bitrix\Main\Config\Configuration;
use Bitrix\Main\DB\Connection;
use Bitrix\Main\DB\SqlHelper;
use Bitrix\Main\DB\Result;
use Bitrix\Main\Application;

class Main
{
    private $logins;
    private $card_IDs;
    private $arr_balance;
    private $card_names;
    private $phone_numbers;
    /**
     * @var int
     */
    private $nums_users;

    public function __construct()
    {

    $connect = new Docking();
    $pain = new GetBonuce($connect->connect1C('http://172.16.20.113:80/testing_sailing/ws/ITFDiscountService.1cws'));

    $pain->getAllData();
    $querr = new DBConnect();
    $this->setLocData($pain);
    $this->putDatabase($querr);


    }

    private function setLocData($pain){
        $this->nums_users = $pain->getSize();
        $this->logins = $pain->getLogin();
        $this->card_IDs = $pain->getCadrIDs();
        $this->arr_balance = $pain->getCardBal();
        $this->card_names = $pain->getCardNames();
        $this->phone_numbers = $pain->getPhoneNumbers();

    }

    private function putDatabase($querr){
        for($i = 0; $i<$this->nums_users-1; $i++){
            $id = $this->card_IDs[$i];
            $querr->createUser($this->logins[$i]);
            $querr->createCard($this->card_IDs[$i], $this->arr_balance[$id],
                $this->card_names[$i], $i, $this->phone_numbers[$i]);
            $querr->putDiscount($this->card_IDs[$i], $this->arr_balance[$id], $this->phone_numbers[$i]);
        }
    }

    private function discActions(){
        $gd = new getDiscount();
    }
}