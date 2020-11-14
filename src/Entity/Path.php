<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Path
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public int $id;

    /**
     * @ORM\Column(type="string")
     */
    public string $path;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Path", inversedBy="children")
     */
    public ?Path $parent;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Path", mappedBy="parent", cascade={"all"}, orphanRemoval=true)
     */
    public ?iterable $children;
}
