<?php declare(strict_types=1);

namespace App\Security;

use App\Repository\ApiUserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Uid\UuidV4;

class ApiUserTokenAuthentication extends AbstractAuthenticator
{
    public function __construct(
        private readonly ApiUserRepository $apiUserRepository
    ){}

    public function supports(Request $request): ?bool
    {
        return true;
    }

    public function authenticate(Request $request): SelfValidatingPassport
    {
        $apiTokenString = $request->headers->get('X-AUTHENTICATION-TOKEN');
        if (!$apiTokenString) {
            // The token header is not present, authentication fails with HTTP Status Code 401 "Unauthorized"
            throw new CustomUserMessageAuthenticationException(message: 'No API token provided.');
        }
        if (!UuidV4::isValid($apiTokenString)) {
            // The token is not valid UuidV4, authentication fails with HTTP Status Code 401 "Unauthorized"
            throw new CustomUserMessageAuthenticationException(message: 'API token is invalid.');
        }
        $apiToken = UuidV4::fromString($apiTokenString);
        if (!$user = $this->apiUserRepository->findOneBy(criteria: [
            'authenticationToken' => $apiToken
        ])) {
            // The token in header was invalid, authentication fails with HTTP Status Code 401 "Unauthorized"
            throw new CustomUserMessageAuthenticationException(message: 'API token is invalid.');
        }

        return new SelfValidatingPassport(new UserBadge($user->getUserIdentifier()));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // on success, let the request continue
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return new JsonResponse(
            data: [
                'status_code' => Response::HTTP_UNAUTHORIZED,
                'status_name' => 'HTTP_UNAUTHORIZED',
                'message' => strtr($exception->getMessageKey(), $exception->getMessageData())
            ],
            status: Response::HTTP_UNAUTHORIZED
        );
    }
}