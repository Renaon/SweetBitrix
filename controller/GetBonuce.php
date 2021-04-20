<?php

use Bitrix\Main\Localization\Loc;

class GetBonuce
    {
        private $result; //result of 1C request
        private $client; //SOAP client of connection
        //data about discount cards:
        private $arr_id = array();
        private $prog_id = array();
        private $owners = array();
        private $periods = array();
        private $card_names = array();
        private $phone_numbers = array();
        private $cards_balance = array();
        private $arr_size;

        public function __construct($client)
        {
            $this->client = $client;
        }

        public function getAllData(){
            $params = array(
                Loc::getMessage("QUERRY_DATE") => date('Y-m-d H:i:s')
            );
            $this->result = $this->client->ПолучитьДанныеОКартах($params);
            $this->setAllData();
        }

        final function getBalance(): array
        {
            $tmp_arr = array_map();
            foreach($this->arr_id as $key){
                $params = array(
                    Loc::getMessage("CARDID") => $key,
                    Loc::getMessage("QUERRY_DATE") => date('Y-m-d H:i:s')
                );
                $result = $this->client->ПолучитьОстатокБонусов($params);
                $tmp = $result->{'return'}->{Loc::getMessage("QUERRY_RESULT")}->{Loc::getMessage("BALANCE")};
                $tmp_arr[$key] = $tmp;
                //Доступ к бонусам по ИД:
                //$tmp_arr[$key];
            }
            return $tmp_arr;
        }

        private function setBalance(){
            $tmp_arr = $this->getBalance();
            $this->cards_balance = $tmp_arr;
        }

        private function setAllData(){
            $this->setIDs();
            $this->setProgID();
            $this->setCardNames();
            $this->setOwners();
            $this->setPeriods();
            $this->setPhoneNumbers();
            $this->setBalance();
        }

        private function setIDs(){
            $tmp_arr = $this->result->{'return'}->{Loc::getMessage("TABLE")};
            $i = 0;
            foreach($tmp_arr as $key){
                $this->arr_id[$i] = $key->{Loc::getMessage("CARDID")};
                $i++;
            }

        }

        private function setProgID(){
            $tmp_arr = $this->result->{'return'}->{Loc::getMessage("TABLE")};
            $i = 0;
            foreach($tmp_arr as $key){
                $this->prog_id[$i] = $key->{Loc::getMessage("LOYALITY_PROGID")};
                $i++;
            }
        }

        private function setOwners(){
            $tmp_arr = $this->result->{'return'}->{Loc::getMessage("TABLE")};
            $i = 0;
            foreach($tmp_arr as $key){
                $this->owners[$i] = $key->{Loc::getMessage("CARD_OWNER")};
                $i++;
            }
        }

        private function setPeriods(){
            $tmp_arr = $this->result->{'return'}->{Loc::getMessage("TABLE")};
            $i = 0;
            foreach($tmp_arr as $key){
                $this->periods[$i] = $key->{Loc::getMessage("PERIOD")};
                $i++;
            }
        }

        private function setCardNames(){
            $tmp_arr = $this->result->{'return'}->{Loc::getMessage("TABLE")};
            $i = 0;
            foreach($tmp_arr as $key){
                $this->card_names[$i] = $key->{Loc::getMessage("CARDNAME")};
                $i++;
            }
        }

        private function setPhoneNumbers(){
            $tmp_arr = $this->result->{'return'}->{Loc::getMessage("TABLE")};
            foreach($tmp_arr as $key){
                $this->phone_numbers[] = $key->{Loc::getMessage("PHONE")};
            }
        }

        /**
         * @return array
         */
        public function getPhoneNumbers(): array
        {
            return $this->phone_numbers;
        }

        public function getLogin(): array
        {
            return $this->owners;
        }

        public function getSize(): int
        {
            return count($this->owners);
        }

        public function getCadrIDs(): array
        {
            return $this->arr_id;
        }

        public function getCardBal(): array
        {
            return $this->cards_balance;
        }

        public function getCardNames(): array
        {
            return $this->card_names;
        }

    }