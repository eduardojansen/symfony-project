<?php


namespace App\Helper;


use App\Entity\Person;

class PersonFactory implements EntityFactory
{
    public function createEntity($xml): Person
    {
        $code = (string)$xml->personid;
        $name = (string)$xml->personname;
        $phones = (array)$xml->phones->phone;

        if (empty($code)) $this->throwException("Campo id é obrigatório");
        if (empty($name)) $this->throwException("Campo name é obrigatório");
        if (empty($phones)) $this->throwException("Pelo menos um phone deve ser informado");

        $person = new Person();
        $person->setCode($code);
        $person->setName($name);
        $person->setPhones($phones);

        return $person;
    }

    private function throwException($message)
    {
        throw new \Exception($message);
    }

}