<?php

declare(strict_types=1);

namespace EcPhp\EuLoginBundle\Security\Core\User;

use EcPhp\CasBundle\Security\Core\User\CasUserInterface;
use EcPhp\CasLib\Introspection\Introspector;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;

use function get_class;

/**
 * Class EuLoginUserProvider.
 */
class EuLoginUserProvider implements EuLoginUserProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function loadUserByResponse(ResponseInterface $response): CasUserInterface
    {
        /** @var \EcPhp\CasLib\Introspection\Contract\ServiceValidate $introspect */
        $introspect = Introspector::detect($response);

        return new EuLoginUser($introspect->getParsedResponse()['serviceResponse']['authenticationSuccess']);
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByUsername(string $username)
    {
        throw new UnsupportedUserException(sprintf('Username "%s" does not exist.', $username));
    }

    /**
     * {@inheritdoc}
     */
    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof EuLoginUserInterface) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }

        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsClass(string $class)
    {
        return EuLoginUser::class === $class;
    }
}