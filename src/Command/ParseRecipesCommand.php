<?php

namespace App\Command;

use App\Entity\Recipe;
use App\Service\RecipeService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;

// $datas = json_decode(file_get_contents("file:///var/www/html/Garage404/recettes-aleatoires/public/datas/recipes.json"));
// // dd($datas);

// $recipes = $datas->{'recipes'};
// // dd($recipes);

// foreach($recipes as $key => $recipe) {
//     $name = $recipes[$key]->{'name'};
//     echo $name."<br/>";
// }

#[AsCommand(
    name: 'app:parse-recipes',
    description: 'Analyse la liste de recettes et ajoute les nouvelles selon le titre',
)]
class ParseRecipesCommand extends Command
{
    private EntityManagerInterface $entityManager;
    private RecipeService $recipeService;

    public function __construct(EntityManagerInterface $entityManager, RecipeService $recipeService)
    {
        $this->entityManager = $entityManager;
        $this->recipeService = $recipeService;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Affichage stylisé
        $io = new SymfonyStyle($input, $output);

        // Récupération du fichier json
        $json_file = 'datas/recipes.json';

        $filesystem = new Filesystem();

        // Si le fichier json n'existe pas
        if (!$filesystem->exists($json_file)){
            $io->error('Failed to read the JSON file.');

            return Command::FAILURE;
        }

        // Sinon on en récupère le contenu
        $json = file_get_contents($json_file);

        // On transforme les données sous forme de tableau
        $data = json_decode($json, true);
        // Si on a pas récupéré de tableau
        if ($data === null) {
            $io->error('Failed to parse the JSON file.');

            return Command::FAILURE;
        }

        // Si on a récupéré un tableau, on vérifie qu'il a bien une entrée "recipes", sinon on renvoie une erreur
        if (!isset($data['recipes'])){
            $io->error('The "recipes" array does not exist in the provided JSON File');

            return Command::FAILURE;
        }

        // S'il y a bien une entrée "recipes", on vérifie que tous les champs attendus existent, sinon on ignore la recette concernée et on passe à la suivante
        foreach ($data['recipes'] as $recipe){
            if (!isset($recipe['name'], $recipe['ingredients'], $recipe['cookingTime'], $recipe['preparationTime'],$recipe['serves'])){
                $io->warning('A recipe is missing some required data and has been skipped');

                continue;
            }

            // On récupère la liste des noms des recettes déjà en BDD
            $existingRecipe = $this->entityManager->getRepository(Recipe::class)->findOneBy(['name' => $recipe['name']]);

            // Si la recette existe déjà, on l'ignore et on passe à la suivante
            if($existingRecipe){
                $io->note(sprintf("The recipe %s already exist", $recipe['name']));
                continue;
            }

            // On utilise le service qui crée les recettes en BDD
            $this->recipeService->createRecipe($recipe); 
        }

        $this->entityManager->flush();

        $io->success('Recipes have been successfully imported !');
        return Command::SUCCESS;

    }
}
