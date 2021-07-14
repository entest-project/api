<?php

namespace App\Model\Request;

use Symfony\Component\Validator\Constraints as Assert;

class ResetPasswordRequestModel extends AbstractRequestModel
{
    /**
     * @Assert\Length(min=1, max=50)
     * @Assert\NotBlank(normalizer="trim")
     */
    public string $code;

    /**
     * @Assert\Length(min=1, max=100)
     * @Assert\NotBlank(normalizer="trim")
     */
    public string $newPassword;
}
