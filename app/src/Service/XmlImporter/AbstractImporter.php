<?php

namespace App\Service\XmlImporter;

use App\Helper\EntityFactory;
use Doctrine\ORM\EntityManagerInterface;

abstract class AbstractImporter
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;
    /**
     * @var EntityFactory
     */
    private EntityFactory $factory;

    public function __construct(
        EntityFactory $factory,
        EntityManagerInterface $entityManager
    )
    {
        $this->entityManager = $entityManager;
        $this->factory = $factory;
    }

    public function import($xmldata): int
    {
        $count = 0;
        foreach ($xmldata as $xmlPerson) {
            $person = $this->factory->createEntity($xmlPerson);
            $this->entityManager->persist($person);
            $count++;
        }

        $this->entityManager->flush();
        return $count;
    }
}