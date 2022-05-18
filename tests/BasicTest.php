<?php declare(strict_types=1);

namespace App\Tests;

use App\Entity\ApiUser;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\UuidV4;

class BasicTest extends WebTestCase
{
    public function test_unauthorized_cannot_access(): void
    {
        $client = static::createClient();
        $client->request(method: Request::METHOD_GET, uri: '/');
        $this->assertResponseStatusCodeSame(expectedCode: Response::HTTP_UNAUTHORIZED);
    }

    public function test_authorized_can_access(): void
    {
        $container = static::getContainer();
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $container->get(id: EntityManagerInterface::class);
        $apiUser = $entityManager->getRepository(ApiUser::class)->findOneBy([
            'authenticationToken' => '197319f2-028f-47db-8ae1-43c162888490'
        ]);
        self::ensureKernelShutdown();
        $client = static::createClient(server: [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_X_AUTHENTICATION_TOKEN' => $apiUser->getAuthenticationToken()
        ]);
        $client->request(method: Request::METHOD_GET, uri: '/');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
}