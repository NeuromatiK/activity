<?php

namespace App\CQRS\CommandHandler;

use App\CQRS\Command\VisitStore;
use App\Entity\Visit;
use App\Repository\VisitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class VisitStoreHandler implements CommandHandler
{
    private EntityManagerInterface $entityManager;
    private ValidatorInterface $validator;

    public function __construct(EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
    }

    public function __invoke(VisitStore $visitStore)
    {
        $visit = new Visit();
        $visit->setUrl($visitStore->getUrl());
        $visit->setDatetime(new \DateTime($visitStore->getDate()));
        $errors = $this->validator->validate($visit);
        if ($errors->count() === 0) {
            /* @var $repo VisitRepository */
            $repo = $this->entityManager->getRepository(Visit::class);
            $repo->add($visit);
        }
    }
}
