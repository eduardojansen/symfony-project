<?php


namespace App\Helper;


use App\Entity\Item;
use App\Entity\Shiporder;
use App\Repository\PersonRepository;

class ShiporderFactory implements EntityFactory
{
    /**
     * @var PersonRepository
     */
    private PersonRepository $personRepository;

    public function __construct(PersonRepository $personRepository)
    {
        $this->personRepository = $personRepository;
    }

    public function createEntity($xml): Shiporder
    {
        $orderId = (int)$xml->orderid;
        $personId = (int)$xml->orderperson;
        $shipto = (array)$xml->shipto;
        $person = $this->personRepository->findOneBy(['code' => $personId]);

        $shipOrder = new Shiporder();

        if (!$person) $this->throwException("Pessoa com id {$personId} não está cadastrada no sistema");
        $shipOrder->setPerson($person);

        if (!$orderId) $this->throwException("Campo orderId é obrigatório");
        $shipOrder->setCode($orderId);

        if (!isset($shipto['name'])) $this->throwException("Campo name é obrigatório");
        $shipOrder->setName($shipto['name']);

        if (!isset($shipto['address'])) $this->throwException("Campo address é obrigatório");
        $shipOrder->setAddress($shipto['address']);

        if (!isset($shipto['city'])) $this->throwException("Campo city é obrigatório");
        $shipOrder->setCity($shipto['city']);

        if (!isset($shipto['country'])) $this->throwException("Campo country é obrigatório");
        $shipOrder->setCountry($shipto['country']);

        $items = (array)$xml->items;

        foreach ($items['item'] as $xmlItem) {
            $title = (string)$xmlItem->title;
            $note = (string)$xmlItem->note;
            $price = (float)$xmlItem->price;
            $quantity = (float)$xmlItem->quantity;

            if (empty($title)) $this->throwException("Campo title do item é obrigatório");
            if (empty($price)) $this->throwException("Campo price do item  é obrigatório");
            if (empty($quantity)) $this->throwException("Campo quantity do item é obrigatório");

            $item = (new Item())
                ->setTitle($title)
                ->setNote($note)
                ->setPrice($price)
                ->setQuantity($quantity);
            $shipOrder->addItem($item);
        }

        return $shipOrder;
    }

    private function throwException($message)
    {
        throw new \Exception($message);
    }

}