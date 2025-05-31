<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

final class AthorizationController extends AbstractController
{
    #[Route('/api/athorization', name: 'app_athorization', methods: ['POST'])]
    public function login(Request $request, EntityManagerInterface $entity_manager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $user_email = htmlspecialchars(trim($data['user_email']));
        $user_password = htmlspecialchars(trim($data['user_password']));
        
        if (empty($user_email) || empty($user_password)) {
            return $this->json(['status' => 'Email и пароль обязательны для заполнения']);
        }

        $user = $entity_manager->getRepository(User::class)->findOneBy(['user_email' => $user_email]);
        
        if (!$user) {
            return $this->json(['status' => 'Пользователь с такими данными не найден']);
        }
        
        if (!password_verify($user_password, $user->getUserPassword())) {
            return $this->json(['status' => 'Неверный пароль']);
        }
        
        return $this->json([
            'status' => 'OK',
            'user_id' => $user->getId(),
            'user_email' => $user->getUserEmail(),
            'user_name' => $user->getUserName(),
            'user_role' => $user->getUserRole()
        ]);
    }

    #[Route('/api/user', name: 'app_user_data', methods: ['GET'])]
    public function getUserData(Request $request, EntityManagerInterface $entity_manager): JsonResponse
    {
        $email = $request->query->get('email');
        $user = $entity_manager->getRepository(User::class)->findOneBy(['user_email' => $email]);
        
        if (!$user) {
            return $this->json(['status' => 'Пользователь не найден'], 404);
        }
        
        return $this->json([
            'user_id' => $user->getId(),
            'user_name' => $user->getUserName(),
            'user_email' => $user->getUserEmail(),
            'user_role' => $user->getUserRole(),
            'user_avatar' => $user->getUserAvatar()
        ]);
    }
}