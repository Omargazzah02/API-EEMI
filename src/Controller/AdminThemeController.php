<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Theme;
use App\Repository\ThemeRepository;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AdminThemeController extends AbstractController
{

    
  
    #[Route('/api/admin/create', name: 'create_theme', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')] 
    public function create(Request $request, EntityManagerInterface $entityManager , ValidatorInterface $validator): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['name'], $data['price'], $data['numberOfUsers'])) {
            return $this->json(['error' => 'Invalid data'], 400);
        }

        $theme = new Theme();
        $theme->setName($data['name']);
        $theme->setPrice((float)$data['price']);
        $theme->setNumberOfUsers((int)$data['numberOfUsers']);
        $theme->setDescription($data['description'] ?? null);

           // Validation des données du thème
    $errors = $validator->validate($theme);

    if (count($errors) > 0) {
        $errorMessages = [];
        foreach ($errors as $error) {
            $errorMessages[] = $error->getMessage();
        }
        return $this->json(['errors' => $errorMessages], 400);
    }

        $entityManager->persist($theme);
        $entityManager->flush();

        return $this->json(['message' => 'Theme created successfully', 'theme' => $theme], 201);
    }





    #[Route('/api/admin/delete/{id}', name: 'delete_theme', methods: ['DELETE'])]
    #[IsGranted('ROLE_ADMIN')] 
    public function delete(int $id, ThemeRepository $themeRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        $theme = $themeRepository->find($id);

        if (!$theme) {
            return $this->json(['error' => 'Theme not found'], 404);
        }

        $entityManager->remove($theme);
        $entityManager->flush();

        return $this->json(['message' => 'Theme deleted successfully'], 200);
    }











    #[Route('/api/admin/update/{id}', name: 'update_theme', methods: ['PUT'])]
    #[IsGranted('ROLE_ADMIN')] 
    public function update(int $id, Request $request, ThemeRepository $themeRepository, EntityManagerInterface $entityManager, ValidatorInterface $validator): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $theme = $themeRepository->find($id);

        if (!$theme) {
            return $this->json(['error' => 'Theme not found'], 404);
        }

        if (isset($data['name'])) {
            $theme->setName($data['name']);
        }
        if (isset($data['price'])) {
            $theme->setPrice((float)$data['price']);
        }
        if (isset($data['numberOfUsers'])) {
            $theme->setNumberOfUsers((int)$data['numberOfUsers']);
        }
        if (isset($data['description'])) {
            $theme->setDescription($data['description']);
        }


        $errors = $validator->validate($theme);

        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            return $this->json(['errors' => $errorMessages], 400);
        }



        $entityManager->flush();

        return $this->json(['message' => 'Theme updated successfully', 'theme' => $theme], 200);
    }

}
