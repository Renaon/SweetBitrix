<?php

use Bitrix\Main\Localization\Loc;

ini_set('soap.wsdl_cache_enabled', 0 );
ini_set('soap.wsdl_cache_ttl', 0);

class Docking{
    protected  $options = array();
    private $client;
    protected $sauce;



    private function getWSDL($url): string
    {
        $wsdl = $url. '?wsdl';
        $this->sauce = $url;
        return $wsdl;
    }

    private function getOptions(): array
    {
        $this->options =array('location'=>$this->sauce,'login' => 'admin', 'password' => "Shop12345");
        return $this->options;
    }

    public function connect1C($url): ?SoapClient
    {

        try {
            $this->client = new SoapClient($this->getWSDL($url), $this->getOptions());
        }catch(SoapFault $e) {
            trigger_error(Loc::getMessage("SERVER_ERROR"), E_USER_ERROR);
        }

        if (is_soap_fault($this->client)){
            trigger_error(Loc::getMessage("SERVER_ERROR"), E_ERROR);
            return null;
        }
        return $this->client;
    }

}
?>
