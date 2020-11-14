<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="type", type="string", columnDefinition="param_type")
 * @ORM\DiscriminatorMap({
 *     "inline"="App\Entity\InlineStepParam",
 *     "multiline"="App\Entity\MultilineStepParam",
 *     "table"="App\Entity\TableStepParam"
 * })
 */
abstract class StepParam
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public int $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ScenarioStep", inversedBy="params")
     */
    public ScenarioStep $step;
}
