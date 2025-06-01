<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\DBAL\Connection;

final class AthorizationController extends AbstractController
{
    public function __construct(
        private Connection $connection
    ) {}

    #[Route('/api/athorization', name: 'app_athorization', methods: ['POST'])]
    public function login(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $user_email = htmlspecialchars(trim($data['user_email'] ?? ''));
        $user_password = htmlspecialchars(trim($data['user_password'] ?? ''));
        
        if (empty($user_email) || empty($user_password)) {
            return $this->json(['status' => 'Email и пароль обязательны для заполнения'], 400);
        }

        $user = $this->connection->executeQuery(
            "SELECT user_id, user_name, user_email, user_password, user_role, user_avatar 
             FROM \"user\" 
             WHERE user_email = ?",
            [$user_email]
        )->fetchAssociative();
        
        if (!$user) {
            return $this->json(['status' => 'Пользователь с такими данными не найден'], 404);
        }
        
        if (!password_verify($user_password, $user['user_password'])) {
            return $this->json(['status' => 'Неверный пароль'], 401);
        }
        
        return $this->json([
            'status' => 'OK',
            'client_id' => $user['user_id'],
            'user_email' => $user['user_email'],
            'user_name' => $user['user_name'],
            'user_role' => $user['user_role'],
            'user_avatar' => $user['user_avatar']
        ]);
    }

    #[Route('/api/user/{client_id}', name: 'app_user_data', methods: ['GET'])]
    public function getUserData(int $client_id): JsonResponse
    {
        $user = $this->connection->executeQuery(
            "SELECT user_id, user_name, user_email, user_role, user_avatar 
             FROM \"user\" 
             WHERE user_id = ?",
            [$client_id]
        )->fetchAssociative();
        
        if (!$user) {
            return $this->json([
                'status' => 'error',
                'message' => 'Пользователь не найден'
            ], 404);
        }

        $projects = $this->connection->executeQuery(
            "SELECT project_id, project_name, project_stack, project_time, project_spent_time 
             FROM project"
        )->fetchAllAssociative();

        return $this->json([
            "client" => [
                'client_id' => $user['user_id'],
                'user_name' => $user['user_name'],
                'user_email' => $user['user_email'],
                'user_role' => $user['user_role'],
                'user_avatar' => $user['user_avatar'],
            ],
            'projects' => $projects
        ]);
    }
}