<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TagRepository")
 */
class Tag
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="\Doctrine\ORM\Id\UuidGenerator")
     *
     * @Serializer\Groups({"LIST_TAGS", "READ_FEATURE", "READ_PATH", "READ_STEP", "READ_TAG"})
     * @Serializer\Type("string")
     */
    public $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Project")
     * @ORM\JoinColumn(onDelete="CASCADE")
     *
     * @Serializer\Exclude
     */
    public Project $project;

    /**
     * @ORM\Column(type="string", length=50)
     *
     * @Serializer\Groups({"LIST_TAGS", "READ_FEATURE", "READ_PATH", "READ_STEP", "READ_TAG"})
     *
     * @Assert\Length(min=1, max=50, normalizer="trim")
     * @Assert\NotBlank(normalizer="trim")
     */
    public string $name;

    /**
     * @ORM\Column(type="string", length=7)
     *
     * @Serializer\Groups({"LIST_TAGS", "READ_FEATURE", "READ_PATH", "READ_STEP", "READ_TAG"})
     */
    public string $color;
}
