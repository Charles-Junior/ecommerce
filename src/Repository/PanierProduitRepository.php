<?php

namespace App\Repository;

use App\Entity\PanierProduit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PanierProduit|null find($id, $lockMode = null, $lockVersion = null)
 * @method PanierProduit|null findOneBy(array $criteria, array $orderBy = null)
 * @method PanierProduit[]    findAll()
 * @method PanierProduit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PanierProduitRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PanierProduit::class);
    }

    // /**
    //  * @return PanierProduit[] Returns an array of PanierProduit objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PanierProduit
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findDetailsCommandes($value1, $value2)
    {
        return $this->createQueryBuilder('Panier','PanierProduit')
            ->andWhere('Panier.user = :val1 AND PanierProduit.panier = :val2')
            ->setParameter(['val1' => $value1, 'val2' => $value2])
           // ->orderBy('Produit.name', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
}
