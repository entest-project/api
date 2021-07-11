<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210711212856 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Added step tags';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('CREATE TABLE step_tag (step_id INT NOT NULL, tag_id UUID NOT NULL, PRIMARY KEY(step_id, tag_id))');
        $this->addSql('CREATE INDEX IDX_9197B26173B21E9C ON step_tag (step_id)');
        $this->addSql('CREATE INDEX IDX_9197B261BAD26311 ON step_tag (tag_id)');
        $this->addSql('ALTER TABLE step_tag ADD CONSTRAINT FK_9197B26173B21E9C FOREIGN KEY (step_id) REFERENCES step (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE step_tag ADD CONSTRAINT FK_9197B261BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('DROP TABLE step_tag');
    }
}
