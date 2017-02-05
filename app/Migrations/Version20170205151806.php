<?php

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170205151806 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE editor (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', name VARCHAR(40) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE paper ADD insight_author_refusal_comment VARCHAR(255) DEFAULT NULL, ADD insight_editor VARCHAR(255) DEFAULT NULL, DROP insight_refusal_comment, DROP editor');
    }

    /**
     * Initial data
     *
     * @param Schema $schema
     */
    public function postUp(Schema $schema)
    {
        $this->connection->beginTransaction();

        $this->connection->executeQuery('INSERT INTO editor (id, name) VALUES (?, ?)', ['47b40772-632a-46e8-b8c2-cc95c18575ae','Emma']);
        $this->connection->executeQuery('INSERT INTO editor (id, name) VALUES (?, ?)', ['78d3e5cb-04b3-4195-91c9-fb5cee5de9e3','Helga']);
        $this->connection->executeQuery('INSERT INTO editor (id, name) VALUES (?, ?)', ['14e7ce47-9462-4fbd-8c11-e8d408acbe16','Peter']);
        $this->connection->executeQuery('INSERT INTO editor (id, name) VALUES (?, ?)', ['ea7e6bd7-0662-44ab-8351-d7fdcd36f61e','Sarah']);
        $this->connection->executeQuery('INSERT INTO editor (id, name) VALUES (?, ?)', ['6b0372ce-6810-4fe0-b269-eed672f3874a','Stuart']);


        $this->connection->commit();
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE editor');
        $this->addSql('ALTER TABLE paper ADD insight_refusal_comment VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, ADD editor VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, DROP insight_author_refusal_comment, DROP insight_editor');
    }
}
