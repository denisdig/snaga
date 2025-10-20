<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250509174019 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE commentaires (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, products_id INT DEFAULT NULL, text LONGTEXT NOT NULL, is_valable TINYINT(1) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', activation TINYINT(1) DEFAULT NULL, INDEX IDX_D9BEC0C4A76ED395 (user_id), INDEX IDX_D9BEC0C46C8A81A9 (products_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE commentaires ADD CONSTRAINT FK_D9BEC0C4A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE commentaires ADD CONSTRAINT FK_D9BEC0C46C8A81A9 FOREIGN KEY (products_id) REFERENCES products (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE commentaires DROP FOREIGN KEY FK_D9BEC0C4A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE commentaires DROP FOREIGN KEY FK_D9BEC0C46C8A81A9
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE commentaires
        SQL);
    }
}
