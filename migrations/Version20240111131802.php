<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240111131802 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add issue';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE issue (id UUID NOT NULL, feature_id UUID NOT NULL, link VARCHAR(256) NOT NULL, issue_tracker VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_12AD233E60E4B879 ON issue (feature_id)');
        $this->addSql('ALTER TABLE issue ADD CONSTRAINT FK_12AD233E60E4B879 FOREIGN KEY (feature_id) REFERENCES feature (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE issue DROP CONSTRAINT FK_12AD233E60E4B879');
        $this->addSql('DROP TABLE issue');
    }
}
