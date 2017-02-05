<?php

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170201205800 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE paper ADD insight_commissioned TINYINT(1) DEFAULT NULL, ADD insight_author VARCHAR(255) DEFAULT NULL, ADD insight_commissioning_decision_comment VARCHAR(255) DEFAULT NULL, ADD insight_refusal_comment VARCHAR(255) DEFAULT NULL, ADD insight_acknowledged TINYINT(1) DEFAULT NULL, ADD insight_author_checking TINYINT(1) DEFAULT NULL, ADD editor VARCHAR(255) DEFAULT NULL, ADD insight_manuscript_no INT DEFAULT NULL, ADD insight_due_date DATETIME DEFAULT NULL, ADD insight_edits_due_date DATETIME DEFAULT NULL, ADD insight_signed_off TINYINT(1) DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE paper DROP insight_commissioned, DROP insight_author, DROP insight_commissioning_decision_comment, DROP insight_refusal_comment, DROP insight_acknowledged, DROP insight_author_checking, DROP editor, DROP insight_manuscript_no, DROP insight_due_date, DROP insight_edits_due_date, DROP insight_signed_off');
    }
}
