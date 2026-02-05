<?php
// src/Repository/LigneOrdonnanceRepository.php

namespace App\Consultation_Online_Module_D_Repository\Repository;

use App\Consultation_Online_Module_D_Entity\Entity\LigneOrdonnance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class LigneOrdonnanceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LigneOrdonnance::class);
    }
}