<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240110084353 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add an ID to organization issue tracker configuration';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE organization_issue_tracker_configuration DROP CONSTRAINT organization_issue_tracker_configuration_pkey');
        $this->addSql('ALTER TABLE organization_issue_tracker_configuration ADD id UUID NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C17D40932C8A3DE99B6F4E ON organization_issue_tracker_configuration (organization_id, issue_tracker)');
        $this->addSql('ALTER TABLE organization_issue_tracker_configuration ADD PRIMARY KEY (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_C17D40932C8A3DE99B6F4E');
        $this->addSql('DROP INDEX organization_issue_tracker_configuration_pkey');
        $this->addSql('ALTER TABLE organization_issue_tracker_configuration DROP id');
        $this->addSql('ALTER TABLE organization_issue_tracker_configuration ALTER organization_id TYPE UUID');
        $this->addSql('ALTER TABLE organization_issue_tracker_configuration ALTER access_token TYPE VARCHAR(256)');
        $this->addSql('ALTER TABLE organization_issue_tracker_configuration ADD PRIMARY KEY (organization_id, issue_tracker)');
    }
}
