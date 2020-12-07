<?php

namespace App\Controller;

use App\Entity\User;
use App\Exception\UserAlreadyExistsException;
use App\Manager\UserManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/register", methods={"POST"})
 */
class Register extends Api
{
    private UserManager $userManager;

    public function __construct(UserManager $userManager)
    {
        $this->userManager = $userManager;
    }

    /**
     * @ParamConverter(
     *     name="user",
     *     class="App\Entity\User",
     *     converter="rollandrock_entity_converter"
     * )
     */
    public function __invoke(User $user): Response
    {
        try {
            $this->userManager->register($user);

            return $this->buildSerializedResponse($user);
        } catch (UserAlreadyExistsException $e) {
            throw new ConflictHttpException();
        }
    }
}
