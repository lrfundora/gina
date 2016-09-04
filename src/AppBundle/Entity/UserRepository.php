<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

class UserRepository extends EntityRepository implements UserProviderInterface
{
    public function loadUserByUsername($username)
    {

        $user = $this->createQueryBuilder('u')
            ->where('u.user=:username')
            ->setParameter('username', $username)
            ->getQuery()
            ->getOneOrNullResult();

        if (null === $user) {
            $message = sprintf(
                'Unable to find an active admin AppBundle:User object identified by "%s".',
                $username
            );
            throw new UsernameNotFoundException($message);
        }
        return $user;
    }

    public function refreshUser(UserInterface $user)
    {

        $class = get_class($user);
        if (!$this->supportsClass($class)) {
            throw new UnsupportedUserException(
                sprintf(
                    'Instances of "%s" are not supported.',
                    $class
                )
            );
        }

        return $this->find($user->getId());

    }

    public function supportsClass($class)
    {

        return $this->getEntityName() === $class
        || is_subclass_of($class, $this->getEntityName());

    }

    public function findAllUsers()
    {
        return $this->getEntityManager()
            ->createQuery("SELECT u, r FROM AppBundle:User u JOIN u.roles r")
            ->getResult();
    }


    public function findUserById($id)
    {
        return $this->getEntityManager()
            ->createQuery("SELECT u FROM AppBundle:User u WHERE u.id=:id")
            ->setParameter('id', $id)
            ->getOneOrNullResult();
    }

    public function findUser($username)
    {
        return $this->getEntityManager()
            ->createQuery("SELECT u FROM AppBundle:User u WHERE u.user=:username")
            ->setParameter('username', $username)
            ->getOneOrNullResult();
    }

} 