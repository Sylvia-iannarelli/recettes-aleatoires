<?php

namespace App\Repository;

use App\Entity\Recipe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Recipe>
 */
class RecipeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recipe::class);
    }

    // public function findRandomRecipe()
    // {
    //     // On recupère la connexion à la BDD
    //     $conn = $this->getEntityManager()->getConnection();

    //     // On recherche les recettes, on les trie aléatoirement et on en garde qu'une 
    //     $sql = '
    //         SELECT * FROM recipe r
    //         ORDER BY RAND() LIMIT 1
    //     ';

    //     // On execute la requête
    //     $stmt = $conn->prepare($sql);
    //     $resultSet = $stmt->executeQuery();

    //     // On récupère un tableau
    //     return $resultSet->fetchAssociative();
    // }

    // Correction
    public function findRandomRecipe(): ?Recipe
    {
        return $this->createQueryBuilder('r')
            ->orderBy('RAND()')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    //    /**
    //     * @return Recipe[] Returns an array of Recipe objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Recipe
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
