<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Step
{
    const TYPE_GIVEN = 'given';
    const TYPE_WHEN = 'when';
    const TYPE_THEN = 'then';

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public int $id;

    /**
     * @ORM\Column(type="string", columnDefinition="step_type")
     */
    public string $type;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\StepPart", mappedBy="step", cascade={"all"}, orphanRemoval=true)
     */
    public iterable $parts;
}
