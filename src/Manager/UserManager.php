<?php

namespace App\Manager;

use App\Entity\User;
use App\Exception\UserAlreadyExistsException;
use App\Exception\UserNotFoundException;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class UserManager
{
    private UserRepository $userRepository;

    private EncoderFactoryInterface $encoderFactory;

    public function __construct(UserRepository $userRepository, EncoderFactoryInterface $encoderFactory)
    {
        $this->userRepository = $userRepository;
        $this->encoderFactory = $encoderFactory;
    }

    /**
     * @throws UserAlreadyExistsException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function register(User $user): User
    {
        if (null !== $this->userRepository->findOneByEmailOrUsername($user)) {
            throw new UserAlreadyExistsException();
        }

        $user->password = $this->encoderFactory->getEncoder(User::class)->encodePassword($user->password, '');
        $this->userRepository->save($user);

        return $user;
    }

    public function resetPasswordRequest(string $email): User
    {
        $user = $this->userRepository->findOneByEmailForResetPassword($email);

        if (null === $user) {
            throw new UserNotFoundException();
        }

        $user->resetPasswordCode = uniqid();
        $user->lastResetPasswordRequest = new \DateTime();

        $this->userRepository->save($user);

        return $user;
    }
}
