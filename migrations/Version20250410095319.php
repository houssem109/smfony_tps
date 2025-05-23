<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250410095319 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article ALTER nom SET NOT NULL');
        $this->addSql('ALTER TABLE article ALTER nom TYPE VARCHAR(50)');
        $this->addSql('ALTER TABLE article ALTER prix TYPE NUMERIC(10, 2)');
        $this->addSql('ALTER TABLE article ALTER prix SET NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE article ALTER nom DROP NOT NULL');
        $this->addSql('ALTER TABLE article ALTER nom TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE article ALTER prix TYPE DOUBLE PRECISION');
        $this->addSql('ALTER TABLE article ALTER prix DROP NOT NULL');
    }
}
