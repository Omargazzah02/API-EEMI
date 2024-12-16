<?php 
// UserService.php

namespace App\Service;

use App\Entity\User;
use App\Entity\Theme;
use App\Entity\UserTheme;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class UserThemeService
{
    private EntityManagerInterface $entityManager;
    private Security $security;
    private UserRepository $userRepository;

    public function __construct(EntityManagerInterface $entityManager, Security $security, UserRepository $userRepository)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
        $this->userRepository = $userRepository;
        
    }

   
    public function addThemeToUser(int $themeId): void
    {
        $user = $this->security->getUser();
        
        if (!$user) {
            throw new \Exception('Utilisateur non connecté');
        }

        $user = $this->userRepository->findOneBy(['username' => $user->getUserIdentifier()]);

        $theme = $this->entityManager->getRepository(Theme::class)->find($themeId);

        if (!$theme) {
            throw new \Exception('Thème non trouvé');
        }

        foreach ($user->getUserThemes() as $userTheme) {
            if ($userTheme->getTheme() === $theme) {
                throw new \Exception('L\'utilisateur a déjà ce thème');
            }
        }

        $userTheme = new UserTheme();
        $userTheme->setUser($user);
        $userTheme->setTheme($theme);
        $userTheme->setSubscriptionDate(new \DateTime());



        

        $this->entityManager->persist($userTheme);
        $this->entityManager->flush();
    }


   






    public function getUserThemes(): array
    {
        $user = $this->security->getUser();

        if (!$user) {
            throw new \Exception('Utilisateur non trouvé');
        }

        $user = $this->userRepository->findOneBy(['username' => $user->getUserIdentifier()]);

        if (!$user) {
            throw new \Exception('Utilisateur non trouvé en base');
        }

        $userThemes = $user->getUserThemes();

        $themesData = [];

        foreach ($userThemes as $userTheme) {
            $themesData[] = [
                'id' => $userTheme->getId(),
                'subscriptionDate' => $userTheme->getSubscriptionDate()->format('Y-m-d H:i:s'),
                'theme' => [
                    'id' => $userTheme->getTheme()->getId(),
                    'name' => $userTheme->getTheme()->getName(),
                    'description' => $userTheme->getTheme()->getDescription(),
                    'price' => $userTheme->getTheme()->getPrice(),
                ],
            ];
        }

        return $themesData;
    }

}
