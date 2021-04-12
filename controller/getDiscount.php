<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
require_once($_SERVER["DOCUMENT_ROOT"] .'/bitrix/modules/disnuts/service/DBConnect.php');
use Bitrix\Sale;
CModule::IncludeModule("sale");
Sale\Compatible\DiscountCompatibility::stopUsageCompatible();

class getDiscount
{
    private $PHONE_NUMBER;
    private $BASKET_PRICE;
    private $balance;
    private $db_connect;

    /**
     * @throws \Bitrix\Main\Db\SqlQueryException
     */
    public function __construct($PHONE_NUMBER, $BASKET_PRICE)
    {
        $this->PHONE_NUMBER = $PHONE_NUMBER;
        $this->$BASKET_PRICE = $BASKET_PRICE;
        $this->db_connect = new DBConnect();
        $this->balance =$this->db_connect->getBalance($PHONE_NUMBER);
    }

    /**
     * @return float|int
     * @throws \Bitrix\Main\Db\SqlQueryException
     */
    public function calculateDisc(){
        $perhaps_percent = 30;
        $maxSale = $this->BASKET_PRICE/100*$perhaps_percent;
        if(($this->balance - $maxSale) < 0){
            $this->balance = 0;
            return $this->BASKET_PRICE - $this->balance;
        }else{
            $this->balance -= $maxSale;
            $this->db_connect->updateDiscount($this->balance,$this->PHONE_NUMBER);
            return $this->BASKET_PRICE - $maxSale;
        }
    }

}

Sale\Compatible\DiscountCompatibility::revertUsageCompatible();