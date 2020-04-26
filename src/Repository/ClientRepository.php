<?php

namespace App\Repository;

use App\Entity\Client;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use App\Entity\ClientRecherche;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

/**
 * @method Client|null find($id, $lockMode = null, $lockVersion = null)
 * @method Client|null findOneBy(array $criteria, array $orderBy = null)
 * @method Client[]    findAll()
 * @method Client[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Client::class);
    }

    // /**
    //  * @return Client[] Returns an array of Client objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Client
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * @return Query
     */
    public function findAllByCriteria(ClientRecherche $clientRecherche): Query
    {
        // le "p" est un alias utilisé dans la requête
        $qb = $this->createQueryBuilder('c')
            ->orderBy('c.id', 'DESC');

        if ($clientRecherche->getNom()) {
            $qb->andWhere('c.nom LIKE :nom')
                ->setParameter('nom', $clientRecherche->getNom().'%');
        }

        if ($clientRecherche->getEmail()) {
            $qb->andWhere('c.email like :email')
                ->setParameter('email', '%'.$clientRecherche->getEmail().'%');
        }

        if ($clientRecherche->getDateEnRelation()) {
            $qb->andWhere('c.dateEnRelation = :dateEnRelation')
                ->setParameter('dateEnRelation', $clientRecherche->getDateEnRelation());
        }

        $query = $qb->getQuery();
        // return $query->execute(); // Avant la création de la pagination
        return $query;
    }
}
