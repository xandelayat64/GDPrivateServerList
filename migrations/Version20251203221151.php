<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251203221151 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE level ADD completions_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE level ADD CONSTRAINT FK_9AEACC13946A32F1 FOREIGN KEY (completions_id) REFERENCES player (id)');
        $this->addSql('CREATE INDEX IDX_9AEACC13946A32F1 ON level (completions_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE level DROP FOREIGN KEY FK_9AEACC13946A32F1');
        $this->addSql('DROP INDEX IDX_9AEACC13946A32F1 ON level');
        $this->addSql('ALTER TABLE level DROP completions_id');
    }
}
