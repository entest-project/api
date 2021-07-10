<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210710132343 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Add feature tags';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('CREATE TABLE feature_tag (feature_id UUID NOT NULL, tag_id UUID NOT NULL, PRIMARY KEY(feature_id, tag_id))');
        $this->addSql('CREATE INDEX IDX_41E4F23060E4B879 ON feature_tag (feature_id)');
        $this->addSql('CREATE INDEX IDX_41E4F230BAD26311 ON feature_tag (tag_id)');
        $this->addSql('ALTER TABLE feature_tag ADD CONSTRAINT FK_41E4F23060E4B879 FOREIGN KEY (feature_id) REFERENCES feature (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE feature_tag ADD CONSTRAINT FK_41E4F230BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('DROP TABLE feature_tag');
    }
}
