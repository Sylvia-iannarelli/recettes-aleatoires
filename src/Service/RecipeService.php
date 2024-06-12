<?php

namespace App\Service;

use App\Entity\Recipe;
use Doctrine\ORM\EntityManagerInterface;

class RecipeService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function createRecipe(array $data)
    {
        $recipe = new Recipe();
        $recipe->setName($data['name']);
        $recipe->setIngredients($data['ingredients']);
        $recipe->setPreparationTime($data['preparationTime']);
        $recipe->setCookingTime($data['cookingTime']);
        $recipe->setServes($data['serves']);

        $this->entityManager->persist($recipe);
    }
}
