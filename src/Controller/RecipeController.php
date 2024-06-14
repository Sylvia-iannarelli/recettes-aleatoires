<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Repository\RecipeRepository;
use App\Service\RecipeService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RecipeController extends AbstractController
{
    #[Route('/liste', name: 'app_recipe_index', methods: "GET")]
    public function index(RecipeRepository $recipeRepository): Response
    {
        return $this->render('recipe/index.html.twig', [
            'recipes' => $recipeRepository->findAll(),
        ]);
    }

    #[Route('/', name: 'app_random_recipe', methods: "GET")]
    public function getRandomRecipe(RecipeService $recipeService): Response
    {
        // $recipe = $recipeRepository->findRandomRecipe();
        // // dd($recipe);

        // $ingredients = json_decode($recipe["ingredients"]);
        // // dd($ingredients);

        // return $this->render('recipe/random.html.twig', [
        //     'recipe' => $recipeRepository->findRandomRecipe(),
        //     'ingredients' => $ingredients
        // ]);

        // Correction
        try{
            $recipe = $recipeService->getRandomRecipeAsArray();
        } catch (Exception $e) {
            $this->addFlash('not-found', $e->getMessage());
            return $this->render('recipe/not-found.html.twig');
        }

        return $this->render('recipe/random.html.twig', [
            'recipe' => $recipe
        ]);
    }

    #[Route('/{id}', name: 'app_recipe_show', methods: "GET", requirements: ['id' => '\d+'])]
    public function show(Recipe $recipe): Response
    {
        return $this->render('recipe/random.html.twig', [
            'recipe' => $recipe
        ]);
    }
}
