<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201114141039 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE feature_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE path_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE project_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE scenario_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE scenario_step_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE step_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE step_param_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE step_part_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE feature (id INT NOT NULL, project_id INT DEFAULT NULL, path_id INT DEFAULT NULL, slug VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_1FD77566166D1F9C ON feature (project_id)');
        $this->addSql('CREATE INDEX IDX_1FD77566D96C566B ON feature (path_id)');
        $this->addSql('CREATE TABLE inline_step_param (id INT NOT NULL, step_part_id INT DEFAULT NULL, content VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_13CFC238FC1ECD03 ON inline_step_param (step_part_id)');
        $this->addSql('CREATE TABLE multiline_step_param (id INT NOT NULL, content VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE path (id INT NOT NULL, parent_id INT DEFAULT NULL, path VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B548B0F727ACA70 ON path (parent_id)');
        $this->addSql('CREATE TABLE project (id INT NOT NULL, slug VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE scenario (id INT NOT NULL, feature_id INT DEFAULT NULL, type scenario_type, title VARCHAR(255) NOT NULL, examples JSON DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_3E45C8D860E4B879 ON scenario (feature_id)');
        $this->addSql('CREATE TABLE scenario_step (id INT NOT NULL, scenario_id INT DEFAULT NULL, step_id INT DEFAULT NULL, adverb step_adverb, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_23742800E04E49DF ON scenario_step (scenario_id)');
        $this->addSql('CREATE INDEX IDX_2374280073B21E9C ON scenario_step (step_id)');
        $this->addSql('CREATE TABLE step (id INT NOT NULL, type step_type, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE step_param (id INT NOT NULL, step_id INT DEFAULT NULL, type param_type, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B8D88B7673B21E9C ON step_param (step_id)');
        $this->addSql('CREATE TABLE step_part (id INT NOT NULL, step_id INT DEFAULT NULL, type step_part_type, content VARCHAR(255) NOT NULL, priority INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_799ED9773B21E9C ON step_part (step_id)');
        $this->addSql('CREATE TABLE table_step_param (id INT NOT NULL, content JSON NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE feature ADD CONSTRAINT FK_1FD77566166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE feature ADD CONSTRAINT FK_1FD77566D96C566B FOREIGN KEY (path_id) REFERENCES path (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE inline_step_param ADD CONSTRAINT FK_13CFC238FC1ECD03 FOREIGN KEY (step_part_id) REFERENCES step_part (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE inline_step_param ADD CONSTRAINT FK_13CFC238BF396750 FOREIGN KEY (id) REFERENCES step_param (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE multiline_step_param ADD CONSTRAINT FK_9C00174BF396750 FOREIGN KEY (id) REFERENCES step_param (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE path ADD CONSTRAINT FK_B548B0F727ACA70 FOREIGN KEY (parent_id) REFERENCES path (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE scenario ADD CONSTRAINT FK_3E45C8D860E4B879 FOREIGN KEY (feature_id) REFERENCES feature (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE scenario_step ADD CONSTRAINT FK_23742800E04E49DF FOREIGN KEY (scenario_id) REFERENCES scenario (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE scenario_step ADD CONSTRAINT FK_2374280073B21E9C FOREIGN KEY (step_id) REFERENCES step (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE step_param ADD CONSTRAINT FK_B8D88B7673B21E9C FOREIGN KEY (step_id) REFERENCES scenario_step (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE step_part ADD CONSTRAINT FK_799ED9773B21E9C FOREIGN KEY (step_id) REFERENCES step (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE table_step_param ADD CONSTRAINT FK_E536D31BBF396750 FOREIGN KEY (id) REFERENCES step_param (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE scenario DROP CONSTRAINT FK_3E45C8D860E4B879');
        $this->addSql('ALTER TABLE feature DROP CONSTRAINT FK_1FD77566D96C566B');
        $this->addSql('ALTER TABLE path DROP CONSTRAINT FK_B548B0F727ACA70');
        $this->addSql('ALTER TABLE feature DROP CONSTRAINT FK_1FD77566166D1F9C');
        $this->addSql('ALTER TABLE scenario_step DROP CONSTRAINT FK_23742800E04E49DF');
        $this->addSql('ALTER TABLE step_param DROP CONSTRAINT FK_B8D88B7673B21E9C');
        $this->addSql('ALTER TABLE scenario_step DROP CONSTRAINT FK_2374280073B21E9C');
        $this->addSql('ALTER TABLE step_part DROP CONSTRAINT FK_799ED9773B21E9C');
        $this->addSql('ALTER TABLE inline_step_param DROP CONSTRAINT FK_13CFC238BF396750');
        $this->addSql('ALTER TABLE multiline_step_param DROP CONSTRAINT FK_9C00174BF396750');
        $this->addSql('ALTER TABLE table_step_param DROP CONSTRAINT FK_E536D31BBF396750');
        $this->addSql('ALTER TABLE inline_step_param DROP CONSTRAINT FK_13CFC238FC1ECD03');
        $this->addSql('DROP SEQUENCE feature_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE path_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE project_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE scenario_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE scenario_step_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE step_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE step_param_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE step_part_id_seq CASCADE');
        $this->addSql('DROP TABLE feature');
        $this->addSql('DROP TABLE inline_step_param');
        $this->addSql('DROP TABLE multiline_step_param');
        $this->addSql('DROP TABLE path');
        $this->addSql('DROP TABLE project');
        $this->addSql('DROP TABLE scenario');
        $this->addSql('DROP TABLE scenario_step');
        $this->addSql('DROP TABLE step');
        $this->addSql('DROP TABLE step_param');
        $this->addSql('DROP TABLE step_part');
        $this->addSql('DROP TABLE table_step_param');
    }
}
