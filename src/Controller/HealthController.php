<?php

declare(strict_types=1);

namespace App\Controller;

use Doctrine\DBAL\Connection;
use AMQPConnection;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

readonly class HealthController
{
    public function __construct(
        private Connection                                      $db,
        #[Autowire('%env(MESSENGER_TRANSPORT_DSN)%')] private string $rabbitUrl
    )
    {
    }

    #[Route('/health', name: 'health', methods: ['GET'])]
    public function index(): JsonResponse
    {
        return new JsonResponse([
            'success' => true,
            'isRabbitAlive' => $this->pingRedis(),
            'isDbAlive' => $this->pingDb(),
        ]);
    }

    private function pingRedis(): bool
    {
        try {
            $url = parse_url($this->rabbitUrl);
            $conn = new AMQPConnection([
                'host'            => $url['host'],
                'port'            => $url['port'] ?? 5672,
                'login'           => $url['user'] ?? 'guest',
                'password'        => $url['pass'] ?? 'guest',
                'vhost'           => '/',
                'connect_timeout' => 1.0,
            ]);
            $conn->connect();
            return $conn->isConnected();
        } catch (\Throwable) {
            return false;
        }
    }

    private function pingDb(): bool
    {
        try {
            $this->db->executeQuery('SELECT 1');
            return true;
        } catch (\Throwable) {
            return false;
        }
    }
}
