<?php

namespace App\Repository;

use App\Entity\Path;
use App\Entity\Project;
use Doctrine\ORM\EntityRepository;

class PathRepository extends EntityRepository
{
    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(Path $path): void
    {
        $this->_em->remove($path);
        $this->_em->flush();
    }

    public function findFullSlugsByProject(Project $project): array
    {
        $query = <<<SQL
WITH RECURSIVE paths(id, full_path, full_slug_path, project_id) AS (
    SELECT p.id, '', '', pro.id, (SELECT COUNT(1) FROM path p2 WHERE p2.parent_id = p.id) AS sub_paths_count FROM path p JOIN project pro ON pro.root_path_id = p.id 
  UNION ALL
    SELECT p.id, pr.full_path || '/' || p.path, pr.full_slug_path || '/' || p.slug, pr.project_id, (SELECT COUNT(1) FROM path p2 WHERE p2.parent_id = p.id) AS sub_paths_count FROM path p JOIN paths pr ON p.parent_id = pr.id
)
SELECT full_slug_path AS path FROM paths WHERE project_id = :project_id AND sub_paths_count = 0 AND full_slug_path <> '';
SQL;

        $result = $this->_em->getConnection()->fetchAllAssociative($query, ['project_id' => $project->id]);

        return array_map(fn (array $item): string => $item['path'], $result);
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Path $path): void
    {
        $this->_em->persist($path);
        $this->_em->flush();
    }
}
