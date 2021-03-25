<?php
CModule::IncludeModule("disnuts");
global $DBType;

$arClasses=array(
    'Docking' =>'controller/Docking.php',
    'GetBonuce' => 'controller/GetBonuce.php',
    'DBConnect' => 'controller/DBConnect.php'
);

CModule::AddAutoloadClasses("disnuts",$arClasses);