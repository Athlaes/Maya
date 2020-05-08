<?php

namespace App\Repository;

use App\Entity\Recette;
use App\Entity\Produit;
use App\Entity\RecetteRecherche;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Query;
use Doctrine\ORN\QueryBuilder;



/**
 * @method Recette|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recette|null findOneBy(array $criteria, array $orderBy = null)
 * @method Recette[]    findAll()
 * @method Recette[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecetteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recette::class);
    }

    // /**
    //  * @return Recette[] Returns an array of Recette objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Recette
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * @return Recette[] Returns an array of Recette objects
     */
    public function findAllRecetteMinTwo() : array
    {
        // $qb = $this->getEntityManager()->createQueryBuilder();
        // $qb->select('r')
        //     ->from(Recette::class, 'r')
        //     ->join(Produit::class, 'p')
        //     ->where('p > 2')
        //     ->getQuery()
        //     ->getResult()
        // ;
        
        // return $qb;
        
        // retourne un tableau d'objets de type Produit 
        // ce n'est pas du SQL mais du DQL : Doctrine Query Language
        // il s'agit en fait d'une requête classique mais qui référence l'objet au lieu de la table=
        return $this->getEntityManager()->createQuery(
            'select r.id, r.nom, count(p.id) as nbProduits
            from App\Entity\Recette r
            join r.produits p
            group by r.id
            having count(p.id) > 1'
        )->getResult();

        // return $this->getEntityManager()->createQuery(
        //     'select r
        //     from App\Entity\Recette r'
        // )->getResult();

        // return $this->createQueryBuilder('r')
        //     ->join(Produit::class, 'p')
        //     ->groupBy('r')
        //     // ->having('r.produits > 2')
        //     ->getQuery()
        //     ->getResult()
        // ;

        // $conn = $this->getEntityManager()->getConnection();

        // $sql = '
        //         SELECT id, nom, count(rp.produit_id) as nbproduits
        //         from recette as r 
        //             join recette_produit as rp on rp.recette_id = r.id
        //         group by r.id
        //         having count(rp.produit_id) > 2;
        //     ';
        // $stmt = $conn->prepare($sql);
        // $stmt->execute();

        // return $stmt->fetchAll();

    }


    /**
     * @return Query 
     */
    public function findAllByCriteria(RecetteRecherche $recetteRecherche) : Query
    {
        $qb =$this->createQueryBuilder('r')
            ->orderBy('r.nom', 'ASC')
        ;
        if($recetteRecherche->getNom()){
            $qb->andWhere('r.nom like :nom')
                ->setParameter('nom', '%'.$recetteRecherche->getNom().'%');
        }
        return $qb->getQuery();

    }

    
    /**
     * @return string[]
     */
    public function findNameByProduit($idProduit): array
    {
        // ce n'est pas du SQL mais du DQL : Doctrine Query Language
        // il s'agit en fait d'une requête classique mais qui référence l'objet au lieu de la table
        return $this->getEntityManager()->createQuery(
            'SELECT r.nom
            FROM App\Entity\Recette r
            JOIN r.produits p
            WHERE p.id = :idProduit
            ORDER BY r.nom ASC'
        )->setParameter('idProduit', $idProduit)->getResult();
    }
}
