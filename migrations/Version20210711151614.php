<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210711151614 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Add scenario tags';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('CREATE TABLE scenario_tag (scenario_id INT NOT NULL, tag_id UUID NOT NULL, PRIMARY KEY(scenario_id, tag_id))');
        $this->addSql('CREATE INDEX IDX_7ED15E1CE04E49DF ON scenario_tag (scenario_id)');
        $this->addSql('CREATE INDEX IDX_7ED15E1CBAD26311 ON scenario_tag (tag_id)');
        $this->addSql('ALTER TABLE scenario_tag ADD CONSTRAINT FK_7ED15E1CE04E49DF FOREIGN KEY (scenario_id) REFERENCES scenario (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE scenario_tag ADD CONSTRAINT FK_7ED15E1CBAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('DROP TABLE scenario_tag');
    }
}
