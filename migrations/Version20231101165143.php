<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231101165143 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cargo (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', name TINYTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event_event (id INT AUTO_INCREMENT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', body LONGTEXT NOT NULL, type VARCHAR(255) NOT NULL, occurred_on DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event_published_event (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', most_recent_event_id INT NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event_schedule_event (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', occurred TINYINT(1) NOT NULL, will_occur_on DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', type VARCHAR(255) NOT NULL, body LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mail_log (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', template VARCHAR(255) NOT NULL, recipient VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, sender VARCHAR(255) NOT NULL, sender_name VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', email VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, birthdate DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', gender VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_cargo (user_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', cargo_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_D92CAC29A76ED395 (user_id), INDEX IDX_D92CAC29813AC380 (cargo_id), PRIMARY KEY(user_id, cargo_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_cargo ADD CONSTRAINT FK_D92CAC29A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_cargo ADD CONSTRAINT FK_D92CAC29813AC380 FOREIGN KEY (cargo_id) REFERENCES cargo (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_cargo DROP FOREIGN KEY FK_D92CAC29A76ED395');
        $this->addSql('ALTER TABLE user_cargo DROP FOREIGN KEY FK_D92CAC29813AC380');
        $this->addSql('DROP TABLE cargo');
        $this->addSql('DROP TABLE event_event');
        $this->addSql('DROP TABLE event_published_event');
        $this->addSql('DROP TABLE event_schedule_event');
        $this->addSql('DROP TABLE mail_log');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_cargo');
    }
}
