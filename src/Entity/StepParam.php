<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @Serializer\Discriminator(
 *     field="type",
 *     map={
 *      "inline": "App\Entity\InlineStepParam",
 *      "multiline": "App\Entity\MultilineStepParam",
 *      "table": "App\Entity\TableStepParam"
 *     },
 *     groups={"READ_FEATURE"}
 * )
 */
#[ORM\Entity]
#[ORM\InheritanceType('JOINED')]
#[ORM\DiscriminatorColumn(name: 'type', type: 'string', columnDefinition: 'param_type')]
#[ORM\DiscriminatorMap([
    'inline' => InlineStepParam::class,
    'multiline' => MultilineStepParam::class,
    'table' => TableStepParam::class
])]
abstract class StepParam
{
    /**
     * @Serializer\Groups({"READ_FEATURE"})
     * @Serializer\Type("string")
     */
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    public string $id;

    #[ORM\ManyToOne(targetEntity: ScenarioStep::class, inversedBy: 'params')]
    public ScenarioStep $step;
}
