<?php

namespace App\CQRS\QueryHandler;

use App\CQRS\Query\VisitList;
use App\Entity\Visit;
use App\Repository\VisitRepository;
use Doctrine\ORM\EntityManagerInterface;
use JetBrains\PhpStorm\ArrayShape;

class VisitListHandler implements QueryHandler
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[ArrayShape(['records' => "mixed", 'pages' => "float"])]
    public function __invoke(VisitList $visitList)
    {
        /* @var $repo VisitRepository */
        $repo = $this->entityManager->getRepository(Visit::class);
        return $repo->getStatsForUrl($visitList->getPage(), $visitList->getRecordsPerPage());
    }
}
