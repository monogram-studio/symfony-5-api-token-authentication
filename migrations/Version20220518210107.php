<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220518210107 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE api_user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_name VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , authentication_token BLOB NOT NULL --(DC2Type:uuid)
        )');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_AC64A0BA24A232CF ON api_user (user_name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_AC64A0BAB54C4ADD ON api_user (authentication_token)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE api_user');
    }
}
