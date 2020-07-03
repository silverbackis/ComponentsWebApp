<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200703125234 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE refresh_tokens_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, enabled BOOLEAN NOT NULL, roles TEXT NOT NULL, password_reset_confirmation_token VARCHAR(255) DEFAULT NULL, password_requested_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, new_username VARCHAR(255) DEFAULT NULL, username_confirmation_token VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON "user" (username)');
        $this->addSql('COMMENT ON COLUMN "user".roles IS \'(DC2Type:array)\'');
        $this->addSql('CREATE TABLE refresh_tokens (id INT NOT NULL, refresh_token VARCHAR(128) NOT NULL, username VARCHAR(255) NOT NULL, valid TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9BACE7E1C74F2195 ON refresh_tokens (refresh_token)');
        $this->addSql('CREATE TABLE route (id VARCHAR(36) NOT NULL, dynamic_content_id VARCHAR(36) DEFAULT NULL, static_page_id VARCHAR(36) DEFAULT NULL, redirect VARCHAR(36) DEFAULT NULL, route VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2C420792C42079 ON route (route)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2C420795E237E06 ON route (name)');
        $this->addSql('CREATE INDEX IDX_2C42079D9D0CD7 ON route (dynamic_content_id)');
        $this->addSql('CREATE INDEX IDX_2C4207995C43776 ON route (static_page_id)');
        $this->addSql('CREATE INDEX IDX_2C42079C30C9E2B ON route (redirect)');
        $this->addSql('CREATE TABLE layout (id VARCHAR(36) NOT NULL, nav_bar_id VARCHAR(36) DEFAULT NULL, is_default BOOLEAN NOT NULL, class_name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_3A3A6BE261464C82 ON layout (nav_bar_id)');
        $this->addSql('CREATE TABLE component (id VARCHAR(36) NOT NULL, route_id VARCHAR(36) DEFAULT NULL, child_component_group_id VARCHAR(36) DEFAULT NULL, class_name VARCHAR(255) DEFAULT NULL, component_name VARCHAR(255) DEFAULT NULL, valid_components TEXT NOT NULL, type VARCHAR(255) NOT NULL, title VARCHAR(255) DEFAULT NULL, content TEXT DEFAULT NULL, form_type VARCHAR(255) DEFAULT NULL, success_handler VARCHAR(255) DEFAULT NULL, caption VARCHAR(255) DEFAULT NULL, file_path VARCHAR(255) DEFAULT NULL, subtitle VARCHAR(255) DEFAULT NULL, url VARCHAR(255) DEFAULT NULL, description TEXT DEFAULT NULL, reverse BOOLEAN DEFAULT NULL, button_text VARCHAR(255) DEFAULT NULL, button_class VARCHAR(255) DEFAULT NULL, columns INT DEFAULT NULL, label VARCHAR(255) DEFAULT NULL, fragment VARCHAR(255) DEFAULT NULL, roles TEXT DEFAULT NULL, exclude_roles TEXT DEFAULT NULL, menu_label BOOLEAN DEFAULT NULL, resource VARCHAR(255) DEFAULT NULL, per_page INT DEFAULT NULL, default_query_string VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_49FEA15734ECB4E6 ON component (route_id)');
        $this->addSql('CREATE INDEX IDX_49FEA1573BAA7F26 ON component (child_component_group_id)');
        $this->addSql('COMMENT ON COLUMN component.valid_components IS \'(DC2Type:json_array)\'');
        $this->addSql('COMMENT ON COLUMN component.roles IS \'(DC2Type:array)\'');
        $this->addSql('COMMENT ON COLUMN component.exclude_roles IS \'(DC2Type:array)\'');
        $this->addSql('CREATE TABLE component_location (id VARCHAR(36) NOT NULL, content_id VARCHAR(36) DEFAULT NULL, component_id VARCHAR(36) DEFAULT NULL, sort INT NOT NULL, dtype VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_1C9C1D2684A0A3ED ON component_location (content_id)');
        $this->addSql('CREATE INDEX IDX_1C9C1D26E2ABAFFF ON component_location (component_id)');
        $this->addSql('CREATE TABLE content (id VARCHAR(36) NOT NULL, layout_id VARCHAR(36) DEFAULT NULL, parent_route_id VARCHAR(36) DEFAULT NULL, created TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, modified TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, security_roles TEXT DEFAULT NULL, type VARCHAR(255) NOT NULL, dynamic BOOLEAN DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, meta_description VARCHAR(255) DEFAULT NULL, parent_id VARCHAR(36) DEFAULT NULL, nested BOOLEAN DEFAULT \'false\', dynamic_page_class VARCHAR(255) DEFAULT NULL, sort INT DEFAULT NULL, valid_components TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_FEC530A9727ACA70 ON content (parent_id)');
        $this->addSql('CREATE INDEX IDX_FEC530A98C22AA1A ON content (layout_id)');
        $this->addSql('CREATE INDEX IDX_FEC530A9AB211837 ON content (parent_route_id)');
        $this->addSql('COMMENT ON COLUMN content.created IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN content.security_roles IS \'(DC2Type:json_array)\'');
        $this->addSql('COMMENT ON COLUMN content.valid_components IS \'(DC2Type:json_array)\'');
        $this->addSql('CREATE TABLE dynamic_content (id VARCHAR(36) NOT NULL, parent_route_id VARCHAR(36) DEFAULT NULL, layout_id VARCHAR(36) DEFAULT NULL, nested BOOLEAN DEFAULT \'false\' NOT NULL, published BOOLEAN DEFAULT NULL, published_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, sort INT NOT NULL, created TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, modified TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, title VARCHAR(255) NOT NULL, meta_description VARCHAR(255) DEFAULT NULL, dtype VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_20B9DEB2AB211837 ON dynamic_content (parent_route_id)');
        $this->addSql('CREATE INDEX IDX_20B9DEB28C22AA1A ON dynamic_content (layout_id)');
        $this->addSql('COMMENT ON COLUMN dynamic_content.created IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE article_page (id VARCHAR(36) NOT NULL, subtitle VARCHAR(255) DEFAULT NULL, content TEXT NOT NULL, image_caption VARCHAR(255) DEFAULT NULL, file_path VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE route ADD CONSTRAINT FK_2C42079D9D0CD7 FOREIGN KEY (dynamic_content_id) REFERENCES dynamic_content (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE route ADD CONSTRAINT FK_2C4207995C43776 FOREIGN KEY (static_page_id) REFERENCES content (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE route ADD CONSTRAINT FK_2C42079C30C9E2B FOREIGN KEY (redirect) REFERENCES route (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE layout ADD CONSTRAINT FK_3A3A6BE261464C82 FOREIGN KEY (nav_bar_id) REFERENCES component (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE component ADD CONSTRAINT FK_49FEA15734ECB4E6 FOREIGN KEY (route_id) REFERENCES route (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE component ADD CONSTRAINT FK_49FEA1573BAA7F26 FOREIGN KEY (child_component_group_id) REFERENCES content (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE component_location ADD CONSTRAINT FK_1C9C1D2684A0A3ED FOREIGN KEY (content_id) REFERENCES content (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE component_location ADD CONSTRAINT FK_1C9C1D26E2ABAFFF FOREIGN KEY (component_id) REFERENCES component (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE content ADD CONSTRAINT FK_FEC530A98C22AA1A FOREIGN KEY (layout_id) REFERENCES layout (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE content ADD CONSTRAINT FK_FEC530A9AB211837 FOREIGN KEY (parent_route_id) REFERENCES route (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE dynamic_content ADD CONSTRAINT FK_20B9DEB2AB211837 FOREIGN KEY (parent_route_id) REFERENCES route (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE dynamic_content ADD CONSTRAINT FK_20B9DEB28C22AA1A FOREIGN KEY (layout_id) REFERENCES layout (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE article_page ADD CONSTRAINT FK_A4483121BF396750 FOREIGN KEY (id) REFERENCES dynamic_content (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE route DROP CONSTRAINT FK_2C42079C30C9E2B');
        $this->addSql('ALTER TABLE component DROP CONSTRAINT FK_49FEA15734ECB4E6');
        $this->addSql('ALTER TABLE content DROP CONSTRAINT FK_FEC530A9AB211837');
        $this->addSql('ALTER TABLE dynamic_content DROP CONSTRAINT FK_20B9DEB2AB211837');
        $this->addSql('ALTER TABLE content DROP CONSTRAINT FK_FEC530A98C22AA1A');
        $this->addSql('ALTER TABLE dynamic_content DROP CONSTRAINT FK_20B9DEB28C22AA1A');
        $this->addSql('ALTER TABLE layout DROP CONSTRAINT FK_3A3A6BE261464C82');
        $this->addSql('ALTER TABLE component_location DROP CONSTRAINT FK_1C9C1D26E2ABAFFF');
        $this->addSql('ALTER TABLE route DROP CONSTRAINT FK_2C4207995C43776');
        $this->addSql('ALTER TABLE component DROP CONSTRAINT FK_49FEA1573BAA7F26');
        $this->addSql('ALTER TABLE component_location DROP CONSTRAINT FK_1C9C1D2684A0A3ED');
        $this->addSql('ALTER TABLE route DROP CONSTRAINT FK_2C42079D9D0CD7');
        $this->addSql('ALTER TABLE article_page DROP CONSTRAINT FK_A4483121BF396750');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('DROP SEQUENCE refresh_tokens_id_seq CASCADE');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE refresh_tokens');
        $this->addSql('DROP TABLE route');
        $this->addSql('DROP TABLE layout');
        $this->addSql('DROP TABLE component');
        $this->addSql('DROP TABLE component_location');
        $this->addSql('DROP TABLE content');
        $this->addSql('DROP TABLE dynamic_content');
        $this->addSql('DROP TABLE article_page');
    }
}
