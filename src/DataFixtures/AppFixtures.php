<?php declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\ApiUser;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        /** @var UuidV4 $uid */
        $uid = UuidV4::fromString('197319f2-028f-47db-8ae1-43c162888490');
        $apiUser = (new ApiUser())
            ->setAuthenticationToken($uid)
            ->setUserName('Test Api User')
            ->setRoles([ApiUser::ROLE_DEFAULT])
        ;
        $manager->persist($apiUser);
        $manager->flush();
    }
}
