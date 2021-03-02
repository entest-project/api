<?php

namespace App\Manager;

use App\Entity\Feature;
use App\Entity\Project;
use App\Repository\FeatureRepository;
use App\Transformer\FeatureToStringTransformer;
use Doctrine\Common\Collections\Collection;

class FeatureManager
{
    private FeatureRepository $featureRepository;

    private FeatureToStringTransformer $featureToStringTransformer;

    public function __construct(FeatureRepository $featureRepository, FeatureToStringTransformer $featureToStringTransformer)
    {
        $this->featureRepository = $featureRepository;
        $this->featureToStringTransformer = $featureToStringTransformer;
    }

    public function pull(Project $project)
    {
        $features = $this->featureRepository->findByRootProject($project);

        return array_map(
            fn (Feature $feature): array => ['path' => $feature->getRootPath(), 'feature' => $this->featureToStringTransformer->transform($feature)],
            $features instanceof Collection ? $features->toArray() : $features
        );
    }
}
