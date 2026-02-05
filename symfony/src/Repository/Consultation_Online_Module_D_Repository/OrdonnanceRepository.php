<?php
// src/Repository/OrdonnanceRepository.php

namespace App\Consultation_Online_Module_D_Repository\Repository;

use App\Consultation_Online_Module_D_Entity\Entity\Ordonnance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class OrdonnanceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ordonnance::class);
    }
}