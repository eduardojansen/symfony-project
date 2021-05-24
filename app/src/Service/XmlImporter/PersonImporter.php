<?php

namespace App\Service\XmlImporter;

use App\Helper\PersonFactory;
use Doctrine\ORM\EntityManagerInterface;

class PersonImporter extends AbstractImporter
{
    public function __construct(
        PersonFactory $factory,
        EntityManagerInterface $entityManager
    )
    {
        parent::__construct($factory, $entityManager);
    }

}