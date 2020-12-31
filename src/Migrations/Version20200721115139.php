<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200721115139 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE items (id INT AUTO_INCREMENT NOT NULL, items_group_id INT DEFAULT NULL, icon_url VARCHAR(255) DEFAULT NULL, name VARCHAR(255) NOT NULL, price DOUBLE PRECISION DEFAULT NULL, weapon_condition VARCHAR(255) DEFAULT NULL, tradable TINYINT(1) DEFAULT NULL, INDEX IDX_E11EE94D892F6989 (items_group_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE items_group (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE items ADD CONSTRAINT FK_E11EE94D892F6989 FOREIGN KEY (items_group_id) REFERENCES items_group (id)');
        $this->addSql('ALTER TABLE craft ADD slot1_id INT DEFAULT NULL, ADD slot2_id INT DEFAULT NULL, ADD slot3_id INT DEFAULT NULL, ADD slot4_id INT DEFAULT NULL, ADD weapon_id INT DEFAULT NULL, ADD gloves_id INT DEFAULT NULL, CHANGE description description VARCHAR(255) DEFAULT NULL, CHANGE cost cost DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE craft ADD CONSTRAINT FK_F45C4A842F8C17ED FOREIGN KEY (slot1_id) REFERENCES items (id)');
        $this->addSql('ALTER TABLE craft ADD CONSTRAINT FK_F45C4A843D39B803 FOREIGN KEY (slot2_id) REFERENCES items (id)');
        $this->addSql('ALTER TABLE craft ADD CONSTRAINT FK_F45C4A848585DF66 FOREIGN KEY (slot3_id) REFERENCES items (id)');
        $this->addSql('ALTER TABLE craft ADD CONSTRAINT FK_F45C4A841852E7DF FOREIGN KEY (slot4_id) REFERENCES items (id)');
        $this->addSql('ALTER TABLE craft ADD CONSTRAINT FK_F45C4A8495B82273 FOREIGN KEY (weapon_id) REFERENCES items (id)');
        $this->addSql('ALTER TABLE craft ADD CONSTRAINT FK_F45C4A844892748A FOREIGN KEY (gloves_id) REFERENCES items (id)');
        $this->addSql('CREATE INDEX IDX_F45C4A842F8C17ED ON craft (slot1_id)');
        $this->addSql('CREATE INDEX IDX_F45C4A843D39B803 ON craft (slot2_id)');
        $this->addSql('CREATE INDEX IDX_F45C4A848585DF66 ON craft (slot3_id)');
        $this->addSql('CREATE INDEX IDX_F45C4A841852E7DF ON craft (slot4_id)');
        $this->addSql('CREATE INDEX IDX_F45C4A8495B82273 ON craft (weapon_id)');
        $this->addSql('CREATE INDEX IDX_F45C4A844892748A ON craft (gloves_id)');
        $this->addSql('ALTER TABLE giveaway CHANGE winner winner VARCHAR(255) DEFAULT NULL, CHANGE winner_tradeurl winner_tradeurl VARCHAR(2083) DEFAULT NULL');
        $this->addSql('ALTER TABLE tchat CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE primary_clan_id primary_clan_id BIGINT DEFAULT NULL, CHANGE join_date join_date DATETIME DEFAULT NULL, CHANGE country_code country_code VARCHAR(255) DEFAULT NULL, CHANGE tchat_ban tchat_ban DATETIME DEFAULT NULL, CHANGE site_ban site_ban DATETIME DEFAULT NULL, CHANGE tchat_ban_counter tchat_ban_counter INT DEFAULT NULL, CHANGE site_ban_counter site_ban_counter INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE craft DROP FOREIGN KEY FK_F45C4A842F8C17ED');
        $this->addSql('ALTER TABLE craft DROP FOREIGN KEY FK_F45C4A843D39B803');
        $this->addSql('ALTER TABLE craft DROP FOREIGN KEY FK_F45C4A848585DF66');
        $this->addSql('ALTER TABLE craft DROP FOREIGN KEY FK_F45C4A841852E7DF');
        $this->addSql('ALTER TABLE craft DROP FOREIGN KEY FK_F45C4A8495B82273');
        $this->addSql('ALTER TABLE craft DROP FOREIGN KEY FK_F45C4A844892748A');
        $this->addSql('ALTER TABLE items DROP FOREIGN KEY FK_E11EE94D892F6989');
        $this->addSql('DROP TABLE items');
        $this->addSql('DROP TABLE items_group');
        $this->addSql('DROP INDEX IDX_F45C4A842F8C17ED ON craft');
        $this->addSql('DROP INDEX IDX_F45C4A843D39B803 ON craft');
        $this->addSql('DROP INDEX IDX_F45C4A848585DF66 ON craft');
        $this->addSql('DROP INDEX IDX_F45C4A841852E7DF ON craft');
        $this->addSql('DROP INDEX IDX_F45C4A8495B82273 ON craft');
        $this->addSql('DROP INDEX IDX_F45C4A844892748A ON craft');
        $this->addSql('ALTER TABLE craft DROP slot1_id, DROP slot2_id, DROP slot3_id, DROP slot4_id, DROP weapon_id, DROP gloves_id, CHANGE description description VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE cost cost DOUBLE PRECISION DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE giveaway CHANGE winner winner VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE winner_tradeurl winner_tradeurl VARCHAR(2083) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE tchat CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE tchat_ban tchat_ban DATETIME DEFAULT \'NULL\', CHANGE site_ban site_ban DATETIME DEFAULT \'NULL\', CHANGE tchat_ban_counter tchat_ban_counter INT DEFAULT NULL, CHANGE site_ban_counter site_ban_counter INT DEFAULT NULL, CHANGE primary_clan_id primary_clan_id BIGINT DEFAULT NULL, CHANGE join_date join_date DATETIME DEFAULT \'NULL\', CHANGE country_code country_code VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
    }
}
