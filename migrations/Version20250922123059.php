<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250922123059 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE cart_item (id INT AUTO_INCREMENT NOT NULL, cart_id INT NOT NULL, product_id INT NOT NULL, quantity INT NOT NULL, INDEX IDX_F0FE25271AD5CDBF (cart_id), INDEX IDX_F0FE25274584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cart_item ADD CONSTRAINT FK_F0FE25271AD5CDBF FOREIGN KEY (cart_id) REFERENCES cart (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cart_item ADD CONSTRAINT FK_F0FE25274584665A FOREIGN KEY (product_id) REFERENCES products (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE panier DROP FOREIGN KEY FK_24CC0DF2FB88E14F
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE adresse_facturation
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE panier
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE detail_commande
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE adresse_livraison ADD user_id INT NOT NULL, ADD adresse VARCHAR(255) NOT NULL, DROP name
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE adresse_livraison ADD CONSTRAINT FK_B0B09C9A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_B0B09C9A76ED395 ON adresse_livraison (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cart DROP FOREIGN KEY FK_BA388B74584665A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cart DROP FOREIGN KEY FK_BA388B7A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_BA388B74584665A ON cart
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cart ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL, DROP product_id, DROP quantity, CHANGE user_id user_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cart ADD CONSTRAINT FK_BA388B7A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user DROP FOREIGN KEY FK_8D93D6495BBD1224
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user DROP FOREIGN KEY FK_8D93D649BE2F0A35
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_8D93D6495BBD1224 ON user
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_8D93D649BE2F0A35 ON user
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user DROP adresse_livraison_id, DROP adresse_facturation_id
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE adresse_facturation (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE panier (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT NOT NULL, produit LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT '(DC2Type:array)', total INT NOT NULL, quantity INT NOT NULL, INDEX IDX_24CC0DF2FB88E14F (utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE detail_commande (id INT AUTO_INCREMENT NOT NULL, quantite INT DEFAULT NULL, prix_total INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE panier ADD CONSTRAINT FK_24CC0DF2FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cart_item DROP FOREIGN KEY FK_F0FE25271AD5CDBF
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cart_item DROP FOREIGN KEY FK_F0FE25274584665A
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE cart_item
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE adresse_livraison DROP FOREIGN KEY FK_B0B09C9A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_B0B09C9A76ED395 ON adresse_livraison
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE adresse_livraison ADD name VARCHAR(255) DEFAULT NULL, DROP user_id, DROP adresse
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cart DROP FOREIGN KEY FK_BA388B7A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cart ADD product_id INT NOT NULL, ADD quantity INT NOT NULL, DROP created_at, DROP updated_at, CHANGE user_id user_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cart ADD CONSTRAINT FK_BA388B74584665A FOREIGN KEY (product_id) REFERENCES products (id) ON UPDATE NO ACTION ON DELETE NO ACTION
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE cart ADD CONSTRAINT FK_BA388B7A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_BA388B74584665A ON cart (product_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user ADD adresse_livraison_id INT DEFAULT NULL, ADD adresse_facturation_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user ADD CONSTRAINT FK_8D93D6495BBD1224 FOREIGN KEY (adresse_facturation_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user ADD CONSTRAINT FK_8D93D649BE2F0A35 FOREIGN KEY (adresse_livraison_id) REFERENCES adresse_livraison (id) ON UPDATE NO ACTION ON DELETE NO ACTION
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_8D93D6495BBD1224 ON user (adresse_facturation_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_8D93D649BE2F0A35 ON user (adresse_livraison_id)
        SQL);
    }
}
