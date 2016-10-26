<?php

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Initial migration
 */
class Version20161018154411 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE article_type (code VARCHAR(3) NOT NULL, PRIMARY KEY(code)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE digest_writer (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', name VARCHAR(40) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE paper (id VARCHAR(100) NOT NULL, article_type VARCHAR(3) NOT NULL, subject_area1 INT NOT NULL, subject_area2 INT DEFAULT NULL, digest_written_by CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', manuscript_no INT NOT NULL, date_added DATETIME DEFAULT NULL, corresponding_author VARCHAR(100) NOT NULL, revision INT NOT NULL, had_appeal TINYINT(1) NOT NULL, insight_decision VARCHAR(10) DEFAULT NULL, insight_comment VARCHAR(1500) DEFAULT NULL, no_digest_status VARCHAR(30) DEFAULT NULL, answers_status VARCHAR(20) DEFAULT NULL, answers_in_digest_form TINYINT(1) DEFAULT NULL, digest_due_date DATETIME DEFAULT NULL, digest_received TINYINT(1) DEFAULT NULL, digest_signed_off TINYINT(1) DEFAULT NULL, _version INT DEFAULT NULL, UNIQUE INDEX UNIQ_4E1A601678C46C96 (manuscript_no), INDEX IDX_4E1A60163C9CD028 (article_type), INDEX IDX_4E1A6016D1F15338 (subject_area1), INDEX IDX_4E1A601648F80282 (subject_area2), INDEX IDX_4E1A601629648E4B (digest_written_by), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE subject_area (id INT NOT NULL, description VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE paper_event (paper_id VARCHAR(100) NOT NULL, sequence INT NOT NULL, payload JSON DEFAULT NULL, time DATETIME NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(paper_id, sequence)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE paper ADD CONSTRAINT FK_4E1A60163C9CD028 FOREIGN KEY (article_type) REFERENCES article_type (code)');
        $this->addSql('ALTER TABLE paper ADD CONSTRAINT FK_4E1A6016D1F15338 FOREIGN KEY (subject_area1) REFERENCES subject_area (id)');
        $this->addSql('ALTER TABLE paper ADD CONSTRAINT FK_4E1A601648F80282 FOREIGN KEY (subject_area2) REFERENCES subject_area (id)');
        $this->addSql('ALTER TABLE paper ADD CONSTRAINT FK_4E1A601629648E4B FOREIGN KEY (digest_written_by) REFERENCES digest_writer (id)');
    }

    /**
     * Initial data
     *
     * @param Schema $schema
     */
    public function postUp(Schema $schema)
    {
        $this->connection->beginTransaction();

        $this->connection->executeQuery('INSERT INTO article_type (code) VALUES (?)', ['ADV']);
        $this->connection->executeQuery('INSERT INTO article_type (code) VALUES (?)', ['RE']);
        $this->connection->executeQuery('INSERT INTO article_type (code) VALUES (?)', ['RA']);
        $this->connection->executeQuery('INSERT INTO article_type (code) VALUES (?)', ['TR']);
        $this->connection->executeQuery('INSERT INTO article_type (code) VALUES (?)', ['SR']);

        $this->connection->executeQuery('INSERT INTO digest_writer (id, name) VALUES (?, ?)', ['1903bf64-954c-11e6-9bb2-7dc97904a942','Jane I']);
        $this->connection->executeQuery('INSERT INTO digest_writer (id, name) VALUES (?, ?)', ['1903f236-954c-11e6-9bb2-7dc97904a942','Bridget']);
        $this->connection->executeQuery('INSERT INTO digest_writer (id, name) VALUES (?, ?)', ['19041608-954c-11e6-9bb2-7dc97904a942','Deepa']);
        $this->connection->executeQuery('INSERT INTO digest_writer (id, name) VALUES (?, ?)', ['19043796-954c-11e6-9bb2-7dc97904a942','Charvy']);
        $this->connection->executeQuery('INSERT INTO digest_writer (id, name) VALUES (?, ?)', ['19045a82-954c-11e6-9bb2-7dc97904a942','Victoria']);
        $this->connection->executeQuery('INSERT INTO digest_writer (id, name) VALUES (?, ?)', ['19047c9c-954c-11e6-9bb2-7dc97904a942','Features team']);

        $this->connection->executeQuery('INSERT INTO subject_area (id, description) VALUES (?, ?)', [15,'Biochemistry']);
        $this->connection->executeQuery('INSERT INTO subject_area (id, description) VALUES (?, ?)', [16,'Biophysics and structural biology']);
        $this->connection->executeQuery('INSERT INTO subject_area (id, description) VALUES (?, ?)', [17,'Cell biology']);
        $this->connection->executeQuery('INSERT INTO subject_area (id, description) VALUES (?, ?)', [18,'Developmental biology and stem cells']);
        $this->connection->executeQuery('INSERT INTO subject_area (id, description) VALUES (?, ?)', [19,'Genes and chromosomes']);
        $this->connection->executeQuery('INSERT INTO subject_area (id, description) VALUES (?, ?)', [20,'Genomics and evolutionary biology']);
        $this->connection->executeQuery('INSERT INTO subject_area (id, description) VALUES (?, ?)', [21,'Human biology and medicine']);
        $this->connection->executeQuery('INSERT INTO subject_area (id, description) VALUES (?, ?)', [22,'Immunology']);
        $this->connection->executeQuery('INSERT INTO subject_area (id, description) VALUES (?, ?)', [23,'Microbiology and infectious disease']);
        $this->connection->executeQuery('INSERT INTO subject_area (id, description) VALUES (?, ?)', [24,'Neuroscience']);
        $this->connection->executeQuery('INSERT INTO subject_area (id, description) VALUES (?, ?)', [25,'Plant biology']);
        $this->connection->executeQuery('INSERT INTO subject_area (id, description) VALUES (?, ?)', [63,'Ecology']);
        $this->connection->executeQuery('INSERT INTO subject_area (id, description) VALUES (?, ?)', [64,'Epidemiology and global health']);
        $this->connection->executeQuery('INSERT INTO subject_area (id, description) VALUES (?, ?)', [65,'Computational systems biology']);
        $this->connection->executeQuery('INSERT INTO subject_area (id, description) VALUES (?, ?)', [78,'Cancer biology']);

        $this->connection->commit();
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE paper DROP FOREIGN KEY FK_4E1A60163C9CD028');
        $this->addSql('ALTER TABLE paper DROP FOREIGN KEY FK_4E1A601629648E4B');
        $this->addSql('ALTER TABLE paper DROP FOREIGN KEY FK_4E1A6016D1F15338');
        $this->addSql('ALTER TABLE paper DROP FOREIGN KEY FK_4E1A601648F80282');
        $this->addSql('DROP TABLE article_type');
        $this->addSql('DROP TABLE digest_writer');
        $this->addSql('DROP TABLE paper');
        $this->addSql('DROP TABLE subject_area');
        $this->addSql('DROP TABLE paper_event');
    }
}
