<?php

namespace App\Repository;

use App\Entity\Fournisseur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use App\Entity\FournisseurRecherche;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

/**
 * @method Fournisseur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Fournisseur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Fournisseur[]    findAll()
 * @method Fournisseur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FournisseurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Fournisseur::class);
    }

    // /**
    //  * @return Fournisseur[] Returns an array of Fournisseur objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Fournisseur
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * @return Query
     */
    public function findAllByCriteria(FournisseurRecherche $fournisseurRecherche): Query
    {
        // le "p" est un alias utilisé dans la requête
        $qb = $this->createQueryBuilder('f')
            ->orderBy('f.id', 'DESC');

        if ($fournisseurRecherche->getNom()) {
            $qb->andWhere('f.nom LIKE :nom')
                ->setParameter('nom', '%'.$fournisseurRecherche->getNom().'%');
        }

        if ($fournisseurRecherche->getEmail()) {
            $qb->andWhere('f.email like :email')
                ->setParameter('email', '%'.$fournisseurRecherche->getEmail().'%');
        }

        if ($fournisseurRecherche->getDateEnRelation()) {
            $qb->andWhere('f.dateEnRelation = :dateEnRelation')
                ->setParameter('dateEnRelation', $fournisseurRecherche->getDateEnRelation());
        }

        $query = $qb->getQuery();
        // return $query->execute(); // Avant la création de la pagination
        return $query;
    }
}
