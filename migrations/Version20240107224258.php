<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240107224258 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Added organization issue tracker configuration';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE organization_issue_tracker_configuration (issue_tracker VARCHAR(255) NOT NULL, organization_id UUID NOT NULL, access_token VARCHAR(256) NOT NULL, PRIMARY KEY(organization_id, issue_tracker))');
        $this->addSql('CREATE INDEX IDX_C17D40932C8A3DE ON organization_issue_tracker_configuration (organization_id)');
        $this->addSql('ALTER TABLE organization_issue_tracker_configuration ADD CONSTRAINT FK_C17D40932C8A3DE FOREIGN KEY (organization_id) REFERENCES organization (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE organization_issue_tracker_configuration DROP CONSTRAINT FK_C17D40932C8A3DE');
        $this->addSql('DROP TABLE organization_issue_tracker_configuration');
    }
}
