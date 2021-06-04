<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

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
     * @Serializer\Groups({"LIST_TAGS", "READ_FEATURE", "READ_PATH", "READ_TAG"})
     * @Serializer\Type("string")
     */
    public $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Project")
     *
     * @Serializer\Exclude
     */
    public Project $project;

    /**
     * @ORM\Column(type="string", length=50)
     *
     * @Serializer\Groups({"LIST_TAGS", "READ_FEATURE", "READ_PATH", "READ_TAG"})
     */
    public string $name;

    /**
     * @ORM\Column(type="string", length=7)
     *
     * @Serializer\Groups({"LIST_TAGS", "READ_FEATURE", "READ_PATH", "READ_TAG"})
     */
    public string $color;
}
