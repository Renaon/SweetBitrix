<?php
CModule::IncludeModule("disnuts");
global $DBType;

$arClasses=array(
    'Docking' =>'controller/Docking.php',
    'GetBonuce' => 'controller/GetBonuce.php',
    'DBConnect' => 'service/DBConnect.php',
    'addUser' => 'service/addUser.php',
    'getDiscount' => '/controller/getDiscount.php',
    'Disnuts' => 'index.php'
);

CModule::AddAutoloadClasses("disnuts",$arClasses);