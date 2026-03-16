<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251204204235 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE level DROP FOREIGN KEY FK_9AEACC13946A32F1');
        $this->addSql('DROP INDEX IDX_9AEACC13946A32F1 ON level');
        $this->addSql('ALTER TABLE level CHANGE completions_id player_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE level ADD CONSTRAINT FK_9AEACC1399E6F5DF FOREIGN KEY (player_id) REFERENCES player (id)');
        $this->addSql('CREATE INDEX IDX_9AEACC1399E6F5DF ON level (player_id)');
        $this->addSql('ALTER TABLE player DROP completions, CHANGE name name VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE level DROP FOREIGN KEY FK_9AEACC1399E6F5DF');
        $this->addSql('DROP INDEX IDX_9AEACC1399E6F5DF ON level');
        $this->addSql('ALTER TABLE level CHANGE player_id completions_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE level ADD CONSTRAINT FK_9AEACC13946A32F1 FOREIGN KEY (completions_id) REFERENCES player (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_9AEACC13946A32F1 ON level (completions_id)');
        $this->addSql('ALTER TABLE player ADD completions VARCHAR(255) DEFAULT NULL, CHANGE name name VARCHAR(255) NOT NULL');
    }
}
