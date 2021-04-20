<?php
Class Disnuts extends CModule
{
    var $MODULE_ID = "disnuts";
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
    var $MODULE_CSS;

    function Disnuts()
    {
        $arModuleVersion = array();

        $path = str_replace("\\", "/", __FILE__);
        $path = substr($path, 0, strlen($path) - strlen("/index.php"));
        include($path . "/version.php");
        if (is_array($arModuleVersion) && array_key_exists("VERSION", $arModuleVersion)) {
            $this->MODULE_VERSION = $arModuleVersion["VERSION"];
            $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        }
        $this->MODULE_NAME = "Модуль использования дисконтных карт бонусов из 1С Розница";
        $this->MODULE_DESCRIPTION = "Альфа версия";
    }

    function DoInstall()
    {
        global $DOCUMENT_ROOT, $APPLICATION;
        // Install events
        RegisterModuleDependences("iblock", "OnAfterIBlockElementUpdate", "disnuts", "cMainDisnuts", "onBeforeElementUpdateHandler");
        RegisterModule($this->MODULE_ID);
        $APPLICATION->IncludeAdminFile("Установка модуля disnuts", $DOCUMENT_ROOT . "/bitrix/modules/disnuts/install/step.php");
        return true;
    }

    function DoUninstall()
    {
        global $DOCUMENT_ROOT, $APPLICATION;
        UnRegisterModuleDependences("iblock", "OnAfterIBlockElementUpdate", "disnuts", "cMainDisnuts", "onBeforeElementUpdateHandler");
        UnRegisterModule($this->MODULE_ID);
        $APPLICATION->IncludeAdminFile("Деинсталляция модуля disnuts", $DOCUMENT_ROOT . "/bitrix/modules/disnuts/install/unstep.php");
        return true;
    }
}