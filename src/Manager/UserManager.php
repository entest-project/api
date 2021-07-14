<?php

namespace App\Manager;

use App\Entity\User;
use App\Exception\UserAlreadyExistsException;
use App\Exception\UserNotFoundException;
use App\Model\Request\UpdateMeRequestModel;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Uid\Uuid;

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

    /**
     * @throws UserAlreadyExistsException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function update(User $user, UpdateMeRequestModel $model): void
    {
        if (null !== $this->userRepository->findOtherByEmailOrUsername($user, $model->email, $model->username)) {
            throw new UserAlreadyExistsException();
        }

        $user->username = $model->username;
        $user->email = $model->email;

        if ('' !== trim($model->password)) {
            $user->password = $this->encoderFactory->getEncoder(User::class)->encodePassword($model->password, '');
        }

        $this->userRepository->save($user);
    }

    /**
     * @throws UserNotFoundException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function resetPassword(string $code, string $newPassword): User
    {
        /** @var User $user */
        $user = $this->userRepository->findOneBy(['resetPasswordCode' => $code]);

        if (null === $user) {
            throw new UserNotFoundException();
        }

        $user->resetPasswordCode = null;
        $user->password = $this->encoderFactory->getEncoder(User::class)->encodePassword($newPassword, '');
        $this->userRepository->save($user);

        return $user;
    }

    /**
     * @throws UserNotFoundException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function resetPasswordRequest(string $email): User
    {
        $user = $this->userRepository->findOneByEmailForResetPassword($email);

        if (null === $user) {
            throw new UserNotFoundException();
        }

        $user->resetPasswordCode = str_replace('-', '', Uuid::v4()->toRfc4122());
        $user->lastResetPasswordRequest = new \DateTime();

        $this->userRepository->save($user);

        return $user;
    }
}
