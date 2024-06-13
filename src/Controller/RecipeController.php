<?php

namespace App\Controller;

use App\Repository\RecipeRepository;
use App\Service\RandomService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RecipeController extends AbstractController
{
    #[Route('/', name: 'app_recipe_index', methods: "GET")]
    public function index(RecipeRepository $recipeRepository): Response
    {
        return $this->render('recipe/index.html.twig', [
            'recipes' => $recipeRepository->findAll(),
        ]);
    }

    #[Route('/random', name: 'app_random_recipe', methods: "GET")]
    public function getRandomRecipe(RecipeRepository $recipeRepository): Response
    {
        $recipe = $recipeRepository->findRandomRecipe();
        // dd($recipe);

        $ingredients = json_decode($recipe["ingredients"]);
        // dd($ingredients);

        return $this->render('recipe/random.html.twig', [
            'recipe' => $recipeRepository->findRandomRecipe(),
            'ingredients' => $ingredients
        ]);
    }
}
