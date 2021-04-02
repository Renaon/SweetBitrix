<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
use Bitrix\Sale;
CModule::IncludeModule("sale");
Sale\Compatible\DiscountCompatibility::stopUsageCompatible();


class getDiscount
{
    private $card_num;

//    public function __construct($card_num){
//        $this->card_num = $card_num;
//    }

    private function takeABascet(){
//        $oBasket = Sale\Basket::loadItemsForFUser(
//            Sale\Fuser::getId(),
//            \Bitrix\Main\Context::getCurrent()->getSite()
//        );
//        $price = \Bitrix\Sale\BasketBase::getPrice();
//        var_dump($price);


    }

    public function discCalc($balance){
        $this->takeABascet();
    }



}

Sale\Compatible\DiscountCompatibility::revertUsageCompatible();