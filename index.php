<?php namespace disnuts;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

require('controller/Docking.php');
require('controller/GetBonuce.php');
require('service/DBConnect.php');
require('controller/getDiscount.php');
require('service/DeepScore.php');


use Bitrix\Main\Config\Configuration;
use Bitrix\Main\DB\Connection;
use Bitrix\Main\DB\SqlHelper;
use Bitrix\Main\DB\Result;
use Bitrix\Main\Application;
use Bitrix\Main\Localization\Loc;
use Bitrix\Sale;
use CModule;
use CUser;
use DBConnect;
use DeepScore;
use Docking;
use ErrorException;
use GetBonuce;
use getDiscount;

CModule::IncludeModule("disnuts");
CModule::IncludeModule("sale");
CModule::IncludeModule("catalog");

set_error_handler('exceptions_error_handler');

class Disnuts
{
    private $logins;
    private $card_IDs;
    private $arr_balance;
    private $card_names;
    private $phone_numbers;
    private $BASKET_PRICE;
    /**
     * @var int
     */
    private $nums_users;
    private $PHONE_CARD_NUMBER;


    public function __construct($price)
    {
        global $USER;
        $this->BASKET_PRICE = $price;
        $connect = new Docking();
        $pain = new GetBonuce($connect->connect1C('http://172.16.20.113:80/testing_sailing/ws/ITFDiscountService.1cws'));

        $pain->getAllData();
        $querr = new DBConnect();
        $this->setLocData($pain);
        $this->putDatabase($querr);
        $this->getUserPhone();
        $this->showData($querr);
    }

    private function showData($querr){
        echo(Loc::getMessage("YOUR BALANCE").  $querr->getBalance($this->PHONE_CARD_NUMBER).
            Loc::getMessage("AUTOMATIC WRITE-OFF"));
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

    /**
     * @return float|int
     * @throws \Bitrix\Main\Db\SqlQueryException
     */
    public function discActions(){
        try {
            $gd = new getDiscount($this->PHONE_CARD_NUMBER, $this->BASKET_PRICE);
            return $gd->calculateDisc();
        }catch (\ErrorException $e){
            echo($e->getMessage());
            return 0;
        }
    }

    private function getUserPhone(){
        $iContact = (new \CUser)->GetID();
        $curUser = CUser::GetByID($iContact)->fetch();
        $this->PHONE_CARD_NUMBER = $curUser["PERSONAL_PHONE"];
        $score = new DeepScore($iContact, $curUser["PERSONAL_PHONE"]);
    }

    /**
     * @throws ErrorException
     */
    function exceptions_error_handler($severity, $message, $filename, $lineno) {
        throw new ErrorException($message, 0, $severity, $filename, $lineno);
    }

}