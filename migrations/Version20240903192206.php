<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240903192206 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create real unique index on projects slugs';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('DROP INDEX uniq_2fb3d0ee32c8a3de989d9b62');
        $this->addSql('CREATE UNIQUE INDEX uniq_project_slug ON project (COALESCE(organization_id, \'00000000-0000-0000-0000-000000000000\'), slug)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX uniq_project_slug');
        $this->addSql('CREATE UNIQUE INDEX uniq_2fb3d0ee32c8a3de989d9b62 ON project (organization_id, slug)');
    }
}
