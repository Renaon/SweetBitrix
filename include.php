<?php
CModule::IncludeModule("disnuts");
global $DBType;

$arClasses=array(
    'discount_nuts\Docking' =>'controller/Docking.php',
    'discount_nuts\GetBonuce' => 'controller/GetBonuce.php',
    'discount_nuts\DBConnect' => 'service/DBConnect.php',
    'discount_nuts\addUser' => 'service/addUser.php',
    'discount_nuts\getDiscount' => '/controller/getDiscount.php',
    'discount_nuts\Disnuts' => 'index.php'
);

CModule::AddAutoloadClasses("disnuts",$arClasses);