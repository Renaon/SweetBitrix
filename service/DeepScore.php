<?php

use Bitrix\Main\Localization\Loc;
use Bitrix\Sale;
use Bitrix\Sale\Internals\DiscountCouponTable;

CModule::IncludeModule("sale");
CModule::IncludeModule("catalog");
CModule::IncludeModule("iblock");

class DeepScore extends DBConnect{
    private $USER_ID;
    private $USER_PHONE;

    public function __construct($Userid, $phone){
        parent::__construct();
        $this->USER_ID = $Userid;
        $this->USER_PHONE = $phone;
        $this->putMoney();
    }

    private function putMoney(){
            $curBal = 0;
            $scoreParam = $this->getDeepScore();
            $scoreBal = $scoreParam["CURRENT_BUDGET"];
            try {
                $curBal = $this->getBalance($this->USER_PHONE);
            }catch( Bitrix\Main\Db\SqlQueryException $e){echo($e->getMessage());}
                if($curBal<$scoreBal){
                    (new CSaleUserAccount)->Pay(
                        $this->USER_ID,
                        $scoreBal-$curBal,
                        "RUB",
                        "",
                        false
                    );
                }
                if($curBal>$scoreBal){
                    $this->privateScore($curBal-$scoreBal);
                }

    }

    private function privateScore($curBal){
        (new CSaleUserAccount)->UpdateAccount(
            $this->USER_ID,
            $curBal,
            "RUB",
            "MANUAL",
            "",
            Loc::getMessage("1CScore")
        );
    }

    public function updateDiscount($balance, $phone_number)
    {
        parent::updateDiscount($balance, $phone_number); // TODO: Change the autogenerated stub
    }

    public function getDeepScore(){
        return CSaleUserAccount::GetByUserID(
            $this->USER_ID,
            "RUB"
        );
    }
}
