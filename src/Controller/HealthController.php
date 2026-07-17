<?php

namespace App\Controller;

use Doctrine\DBAL\Connection;
use Redis;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

readonly class HealthController
{
    public function __construct(
        private Connection                                      $db,
        #[Autowire('%env(REDIS_URL)%')] private readonly string $redisUrl
    )
    {
    }

    #[Route('/health', name: 'health', methods: ['GET'])]
    public function index(): JsonResponse
    {
        return new JsonResponse([
            'success' => true,
            'isRedisAlive' => $this->pingRedis(),
            'isDbAlive' => $this->pingDb(),
        ]);
    }

    private function pingRedis(): bool
    {
        $url = parse_url($this->redisUrl);
        $redis = new Redis();
        $redis->connect($url['host'], $url['port'] ?? 6379, 1.0);
        return (bool)$redis->ping();
    }

    private function pingDb(): bool
    {
        return (bool)$this->db->executeQuery('SELECT 1');
    }
}
