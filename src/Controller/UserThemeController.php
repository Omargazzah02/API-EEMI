<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Service\UserThemeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\SecurityBundle\Security;



class UserThemeController extends AbstractController
{

   private  $userThemeService;
   private $userRepository;





   public function __construct(
    UserThemeService $userThemeService,
    UserRepository $userRepository

)
{
    $this->userThemeService = $userThemeService;
    $this->userRepository = $userRepository;
  
}






    #[Route('/api/user/addtheme/{themeId}', name: 'add_user_theme' , methods: ["POST"])]
    public function addTheme (int $themeId): Response {

        try {
            // Appel de la méthode pour ajouter un thème à l'utilisateur
            $this->userThemeService->addThemeToUser($themeId);
            
            // Réponse de succès
            return new Response('Le thème a été ajouté avec succès');
        } catch (\Exception $e) {
            // Gestion des erreurs
            return new Response('Erreur : ' . $e->getMessage());
        }

    }



    

    #[Route('/api/user/getthemes', name: 'get_themes', methods: ["GET"])]
public function getThemes(Security $security ): JsonResponse
{
    try {
        // Appel au service pour obtenir les thèmes de l'utilisateur
        $themesData = $this->userThemeService->getUserThemes();

        // Retourner les données sous forme de réponse JSON
        return new JsonResponse($themesData, 200);
    } catch (\Exception $e) {
        // Gestion des erreurs
        return new JsonResponse(['error' => $e->getMessage()], 404);
    }
}
}

   
