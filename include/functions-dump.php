<?php
session_start();

CModule::AddAutoloadClasses(
    '', // не указываем имя модуля
    array(
        // ключ - имя класса, значение - путь относительно корня сайта к файлу с классом
        'Docking' =>'/home/bitrix/www/bitrix/modules/disnuts/controller/Docking.php',
        'GetBonuce' => '/home/bitrix/www/bitrix/modules/disnuts/controller/GetBonuce.php',
        'DBConnect' => '/home/bitrix/www/bitrix/modules/disnuts/service/DBConnect.php',
        'addUser' => '/home/bitrix/www/bitrix/modules/disnuts/service/addUser.php',
        'getDiscount' => '/home/bitrix/www/bitrix/modules/disnuts/controller/getDiscount.php',
        'Disnuts' => '/home/bitrix/www/bitrix/modules/disnuts/index.php'
    )
);

AddEventHandler("main", "OnAfterUserRegister", "OnBeforeUserRegisterHandler");

function OnBeforeUserRegisterHandler(&$arFields)
{
    //создаём профиль
    //PERSON_TYPE_ID - идентификатор типа плательщика, для которого создаётся профиль
    $arProfileFields = array(
        "NAME" => "Профиль покупателя (".$arFields['LOGIN'].')',
        "USER_ID" => $arFields['USER_ID'],
        "PERSON_TYPE_ID" => 1
    );
    $PROFILE_ID = CSaleOrderUserProps::Add($arProfileFields);

    //если профиль создан
    if ($PROFILE_ID)
    {
        //формируем массив свойств
        $PROPS=Array(
            array(
                "USER_PROPS_ID" => $PROFILE_ID,
                "ORDER_PROPS_ID" => 3,
                "NAME" => "Телефон",
                "VALUE" => $arFields['WORK_PHONE']
            ),
            array(
                "USER_PROPS_ID" => $PROFILE_ID,
                "ORDER_PROPS_ID" => 1,
                "NAME" => "Ф.И.О.",
                "VALUE" => $arFields['LAST_NAME'].' '.$arFields['NAME'].' '.$arFields['SECOND_NAME']
            )
        );
        //добавляем значения свойств к созданному ранее профилю
        foreach ($PROPS as $prop)
            CSaleOrderUserPropsValue::Add($prop);
    }
}
