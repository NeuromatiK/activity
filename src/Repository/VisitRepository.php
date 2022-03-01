<?php

namespace App\Repository;

use App\Entity\Visit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Visit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Visit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Visit[]    findAll()
 * @method Visit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VisitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Visit::class);
    }

    public function add(Visit $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function remove(Visit $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function getStatsForUrl($page = 0, $recordsPerPage = 10)
    {
        return $this->createQueryBuilder('m')->select('m.url,max(m.datetime) as last_time,count(m.url) as visit_count')->groupBy(
            'm.url'
        )->setFirstResult($page * $recordsPerPage)->setMaxResults($recordsPerPage)->getQuery()->getResult(
            AbstractQuery::HYDRATE_ARRAY
        );
    }
}
