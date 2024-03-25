<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240325134220 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Estimate and EstimateData tables';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE estimate (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', user_client_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', integration JSON NOT NULL, cms VARCHAR(255) NOT NULL, page SMALLINT NOT NULL, complexity VARCHAR(255) NOT NULL, result SMALLINT NOT NULL, INDEX IDX_D2EA4607190BE4C5 (user_client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE estimate_data (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', experiment VARCHAR(255) NOT NULL, prepayment DOUBLE PRECISION NOT NULL, profit DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE estimate ADD CONSTRAINT FK_D2EA4607190BE4C5 FOREIGN KEY (user_client_id) REFERENCES user_client (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE estimate DROP FOREIGN KEY FK_D2EA4607190BE4C5');
        $this->addSql('DROP TABLE estimate');
        $this->addSql('DROP TABLE estimate_data');
    }
}
