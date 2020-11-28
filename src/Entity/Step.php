<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StepRepository")
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
     *
     * @Serializer\Groups({"READ_FEATURE", "READ_STEP"})
     */
    public int $id;

    /**
     * @ORM\Column(type="string", columnDefinition="step_type")
     *
     * @Serializer\Groups({"READ_FEATURE", "READ_STEP"})
     */
    public string $type;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\StepPart", mappedBy="step", cascade={"all"}, orphanRemoval=true)
     *
     * @Serializer\Groups({"READ_FEATURE", "READ_STEP"})
     */
    public iterable $parts = [];

    public function getId(): int
    {
        return $this->id;
    }
}
