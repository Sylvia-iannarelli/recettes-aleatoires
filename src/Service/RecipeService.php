<?php

namespace App\Service;

use App\Entity\Recipe;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class RecipeService
{
    private EntityManagerInterface $entityManager;
    private RecipeRepository $recipeRepository;

    public function __construct(EntityManagerInterface $entityManager, RecipeRepository $recipeRepository)
    {
        $this->entityManager = $entityManager;
        $this->recipeRepository = $recipeRepository;
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

    public function getRandomRecipeAsArray()
    {
        $recipe = $this->recipeRepository->findRandomRecipe();

        if ($recipe === null) {
            throw new Exception('Aucune recette trouvée');
        }

        return $recipe->toArray();
        
    }
}
