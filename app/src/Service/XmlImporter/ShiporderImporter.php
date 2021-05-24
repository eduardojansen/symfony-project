<?php

namespace App\Service\XmlImporter;

use App\Helper\ShiporderFactory;
use Doctrine\ORM\EntityManagerInterface;

class ShiporderImporter extends AbstractImporter
{
    public function __construct(
        ShiporderFactory $factory,
        EntityManagerInterface $entityManager
    )
    {
        parent::__construct($factory, $entityManager);
    }

}