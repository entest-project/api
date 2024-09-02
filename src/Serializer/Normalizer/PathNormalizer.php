<?php

declare(strict_types=1);

namespace App\Serializer\Normalizer;

use App\Entity\Path;
use App\Helper\ExtractSerializationGroupHelper;
use App\Repository\ProjectRepository;
use App\Serializer\Groups;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerInterface;

readonly class PathNormalizer implements NormalizerInterface, SerializerAwareInterface
{
    public function __construct(
        private ProjectRepository $projectRepository,
        private ObjectNormalizer $objectNormalizer
    ) {}

    public function normalize(mixed $object, string $format = null, array $context = [])
    {
        if (array_intersect(ExtractSerializationGroupHelper::extractGroup($context), [Groups::ListProjects->value, Groups::ReadFeature->value, Groups::ReadPath->value]) === []) {
            return $this->objectNormalizer->normalize($object, $format, $context);
        }

        if (!$object instanceof Path) {
            return $this->objectNormalizer->normalize($object, $format, $context);
        }

        if ($object->parent !== null) {
            $object->parent->children = [];
        }

        foreach ($object->children as $child) {
            $child->parent = null;
        }

        $object->rootProject = $this->projectRepository->findPathRootProject($object);

        return $this->objectNormalizer->normalize($object, $format, $context);
    }

    public function supportsNormalization(mixed $data, string $format = null): bool
    {
        return $data instanceof Path;
    }

    public function setSerializer(SerializerInterface $serializer): void
    {
        $this->objectNormalizer->setSerializer($serializer);
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            Path::class => true
        ];
    }
}
