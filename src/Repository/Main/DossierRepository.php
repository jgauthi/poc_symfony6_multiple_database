<?php
namespace App\Repository\Main;

use App\Entity\Enum\DossierStatusEnum;
use App\Entity\Main\Dossier;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Dossier|null find($id, $lockMode = null, $lockVersion = null)
 * @method Dossier|null findOneBy(array $criteria, array $orderBy = null)
 * @method Dossier[]    findAll()
 * @method Dossier[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DossierRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Dossier::class);
    }

    public function findByStatus(DossierStatusEnum $status): Query
    {
        return $this->createQueryBuilder('dossier')
            ->where('dossier.status = :status')
            ->setParameter('status', $status->value)
            ->getQuery()
        ;
    }
}
