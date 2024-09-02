<?php

declare(strict_types=1);

namespace App\Serializer\Normalizer;

use App\Entity\Feature;
use App\Repository\ProjectRepository;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerInterface;

readonly class FeatureNormalizer implements NormalizerInterface, SerializerAwareInterface
{
    public function __construct(
        private ProjectRepository $projectRepository,
        private ObjectNormalizer $objectNormalizer
    ) {}

    public function normalize(mixed $object, string $format = null, array $context = [])
    {
        if (!$object instanceof Feature) {
            return $this->objectNormalizer->normalize($object, $format, $context);
        }

        $object->rootProject = $this->projectRepository->findFeatureRootProject($object);

        return $this->objectNormalizer->normalize($object, $format, $context);
    }

    public function supportsNormalization(mixed $data, string $format = null): bool
    {
        return $data instanceof Feature;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            Feature::class => true
        ];
    }

    public function setSerializer(SerializerInterface $serializer): void
    {
        $this->objectNormalizer->setSerializer($serializer);
    }
}
