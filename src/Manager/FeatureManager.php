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

    public function pull(Project $project, string $inlineParameterWrapper)
    {
        $features = $this->featureRepository->findPullableByRootProject($project);
        $this->featureToStringTransformer->setInlineParameterWrapper($inlineParameterWrapper);

        return array_map(
            fn (Feature $feature): array => $this->featureToPulledElement($feature, $inlineParameterWrapper),
            $features instanceof Collection ? $features->toArray() : $features
        );
    }

    private function featureToPulledElement(Feature $feature): array
    {
        return [
            'displayPath' => $feature->getDisplayRootPath(),
            'path' => $feature->getRootPath() . '.feature',
            'feature' => $this->featureToStringTransformer->transform($feature)
        ];
    }
}
