<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface as HasherUserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SecurityController extends AbstractController
{
    private $entityManager;
    private $passwordHasher;
    private $security;
    private $jwtManager; 
   private $userRepository;
     // Ajouter le service JWTTokenManagerInterface

    // Correction du constructeur, utilisation de __construct avec deux underscores
    public function __construct(
        EntityManagerInterface $entityManager,
        HasherUserPasswordHasherInterface $passwordHasher,
        Security $security,
        JWTTokenManagerInterface $jwtManager ,
        UserRepository $userRepository
        // Injecter le service JWTTokenManagerInterface
    )
    {
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
        $this->security = $security;
        $this->jwtManager = $jwtManager; 
        $this->userRepository = $userRepository; // Assignation du service JWTTokenManagerInterface
    }

    // Route pour l'enregistrement d'un utilisateur
    #[Route('/api/register', name: 'api_register', methods: ['POST'])]
    public function register(Request $request, ValidatorInterface $validator): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $user = new User();
        $user->setUsername($data["username"]);
        $user->setEmail($data["email"]);
        $user->setPhoneNumber($data["phonenumber"]);
        $user->setAddress($data["address"]);
        $user->setPassword($data["password"]);




         // Validation des données de l'utilisateur
    $errors = $validator->validate($user);

    if (count($errors) > 0) {
        $errorMessages = [];
        foreach ($errors as $error) {
            $errorMessages[] = $error->getMessage();
        }
        return $this->json(['errors' => $errorMessages], 400);
    }

        // Hash du mot de passe
        $hashedPassword = $this->passwordHasher->hashPassword($user, $data["password"]);
        $user->setPassword($hashedPassword);

        // Sauvegarde en base de données
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'User registered successfully'], Response::HTTP_CREATED);
    }

    // Route de connexion
    #[Route('/api/login', name: 'api_login', methods: ['POST'])]
    public function login(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // Vérification des informations de connexion
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['username' => $data['username']]);

       if (!$user || !$this->passwordHasher->isPasswordValid($user, $data['password'])) {
            return new JsonResponse(['error' => 'Invalid credentials'], Response::HTTP_UNAUTHORIZED);
        }

        $token = $this->jwtManager->create($user);

        return new JsonResponse(['token' => $token], Response::HTTP_OK);
    }




    #[Route('/api/add-admin-role', name: 'add_admin_role', methods: ['POST'])]
    public function addAdminRole(
        Security $security, 
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $user = $security->getUser();

        if (!$user) {
            return $this->json(['error' => 'User not logged in'], 401);
        }

        $user = $this->userRepository->findOneBy(['username' => $user->getUserIdentifier()]);

      
        $roles = $user->getRoles();
        if (!in_array('ROLE_ADMIN', $roles)) {
            $roles[] = 'ROLE_ADMIN';
            $user->setRoles($roles);

            $entityManager->persist($user);
            $entityManager->flush();
        }

        return $this->json([
            'message' => 'Role ROLE_ADMIN added successfully!',
            'roles' => $user->getRoles(),
        ]);
    }

    



}
