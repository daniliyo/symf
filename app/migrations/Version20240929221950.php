<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240929221950 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE result (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', first_team_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', second_team_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', first_team_goal INT NOT NULL, second_team_goal INT NOT NULL, first_team_point INT NOT NULL, second_team_point INT NOT NULL, division VARCHAR(1) DEFAULT NULL, INDEX IDX_136AC1133AE0B452 (first_team_id), INDEX IDX_136AC1133E2E58C3 (second_team_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE statistics (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', team_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', goal INT NOT NULL, point INT NOT NULL, division VARCHAR(1) DEFAULT NULL, INDEX IDX_E2D38B22296CD8AE (team_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE team (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(255) NOT NULL, division VARCHAR(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE result ADD CONSTRAINT FK_136AC1133AE0B452 FOREIGN KEY (first_team_id) REFERENCES team (id)');
        $this->addSql('ALTER TABLE result ADD CONSTRAINT FK_136AC1133E2E58C3 FOREIGN KEY (second_team_id) REFERENCES team (id)');
        $this->addSql('ALTER TABLE statistics ADD CONSTRAINT FK_E2D38B22296CD8AE FOREIGN KEY (team_id) REFERENCES team (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE result DROP FOREIGN KEY FK_136AC1133AE0B452');
        $this->addSql('ALTER TABLE result DROP FOREIGN KEY FK_136AC1133E2E58C3');
        $this->addSql('ALTER TABLE statistics DROP FOREIGN KEY FK_E2D38B22296CD8AE');
        $this->addSql('DROP TABLE result');
        $this->addSql('DROP TABLE statistics');
        $this->addSql('DROP TABLE team');
    }
}
