<?php

namespace App\Entity;

use App\Serializer\Groups;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation as Serializer;

#[ORM\Entity]
class MultilineStepParam extends StepParam
{
    #[Serializer\Groups([Groups::ReadFeature->value])]
    #[ORM\Column(type: 'text')]
    public string $content;
}
