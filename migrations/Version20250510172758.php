<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250510172758 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE user ADD adresse_livraison_id INT DEFAULT NULL, ADD adresse_facturation_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user ADD CONSTRAINT FK_8D93D649BE2F0A35 FOREIGN KEY (adresse_livraison_id) REFERENCES adresse_livraison (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user ADD CONSTRAINT FK_8D93D6495BBD1224 FOREIGN KEY (adresse_facturation_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_8D93D649BE2F0A35 ON user (adresse_livraison_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_8D93D6495BBD1224 ON user (adresse_facturation_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE user DROP FOREIGN KEY FK_8D93D649BE2F0A35
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user DROP FOREIGN KEY FK_8D93D6495BBD1224
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_8D93D649BE2F0A35 ON user
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_8D93D6495BBD1224 ON user
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user DROP adresse_livraison_id, DROP adresse_facturation_id
        SQL);
    }
}
