<?php

namespace App\CQRS\QueryHandler;

use App\CQRS\Query\VisitList;
use App\Entity\Visit;
use App\Repository\VisitRepository;
use Doctrine\ORM\EntityManagerInterface;

class VisitListHandler implements QueryHandler
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function __invoke(VisitList $visitList)
    {
        /* @var $repo VisitRepository */
        $repo = $this->entityManager->getRepository(Visit::class);
        return $repo->getStatsForUrl($visitList->getPage(), $visitList->getRecordsPerPage());
    }
}
