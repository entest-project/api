<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20211030213538 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Add cascade deletion on project->tag';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE tag DROP CONSTRAINT FK_389B783166D1F9C');
        $this->addSql('ALTER TABLE tag ADD CONSTRAINT FK_389B783166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE tag DROP CONSTRAINT fk_389b783166d1f9c');
        $this->addSql('ALTER TABLE tag ADD CONSTRAINT fk_389b783166d1f9c FOREIGN KEY (project_id) REFERENCES project (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
