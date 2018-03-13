<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180313095541 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE component (id VARCHAR(255) NOT NULL, tabs_id VARCHAR(255) DEFAULT NULL, route_id VARCHAR(255) DEFAULT NULL, class_name VARCHAR(255) DEFAULT NULL, valid_components LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', type VARCHAR(255) NOT NULL, content LONGTEXT DEFAULT NULL, form_type VARCHAR(255) DEFAULT NULL, success_handler VARCHAR(255) DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, caption VARCHAR(255) DEFAULT NULL, file_path VARCHAR(255) DEFAULT NULL, subtitle VARCHAR(255) DEFAULT NULL, label VARCHAR(255) DEFAULT NULL, fragment VARCHAR(255) DEFAULT NULL, menu_label TINYINT(1) DEFAULT NULL, INDEX IDX_49FEA157459BC0C4 (tabs_id), INDEX IDX_49FEA15734ECB4E6 (route_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE route_content (id VARCHAR(255) NOT NULL, layout_id VARCHAR(255) DEFAULT NULL, type VARCHAR(255) NOT NULL, title VARCHAR(255) DEFAULT NULL, meta_description VARCHAR(255) DEFAULT NULL, parent_id VARCHAR(255) DEFAULT NULL, valid_components LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', subtitle VARCHAR(255) DEFAULT NULL, content LONGTEXT DEFAULT NULL, file_path VARCHAR(255) DEFAULT NULL, INDEX IDX_6966D31727ACA70 (parent_id), INDEX IDX_6966D318C22AA1A (layout_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE component_location (id VARCHAR(255) NOT NULL, content_id VARCHAR(255) DEFAULT NULL, component_id VARCHAR(255) DEFAULT NULL, sort INT NOT NULL, dtype VARCHAR(255) NOT NULL, INDEX IDX_1C9C1D2684A0A3ED (content_id), INDEX IDX_1C9C1D26E2ABAFFF (component_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE layout (id VARCHAR(255) NOT NULL, nav_bar_id VARCHAR(255) DEFAULT NULL, is_default TINYINT(1) NOT NULL, class_name VARCHAR(255) DEFAULT NULL, INDEX IDX_3A3A6BE261464C82 (nav_bar_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE route (route VARCHAR(255) NOT NULL, content_id VARCHAR(255) DEFAULT NULL, redirect VARCHAR(255) DEFAULT NULL, INDEX IDX_2C4207984A0A3ED (content_id), INDEX IDX_2C42079C30C9E2B (redirect), PRIMARY KEY(route)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE component ADD CONSTRAINT FK_49FEA157459BC0C4 FOREIGN KEY (tabs_id) REFERENCES component (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE component ADD CONSTRAINT FK_49FEA15734ECB4E6 FOREIGN KEY (route_id) REFERENCES route (route)');
        $this->addSql('ALTER TABLE route_content ADD CONSTRAINT FK_6966D318C22AA1A FOREIGN KEY (layout_id) REFERENCES layout (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE component_location ADD CONSTRAINT FK_1C9C1D2684A0A3ED FOREIGN KEY (content_id) REFERENCES route_content (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE component_location ADD CONSTRAINT FK_1C9C1D26E2ABAFFF FOREIGN KEY (component_id) REFERENCES component (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE layout ADD CONSTRAINT FK_3A3A6BE261464C82 FOREIGN KEY (nav_bar_id) REFERENCES component (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE route ADD CONSTRAINT FK_2C4207984A0A3ED FOREIGN KEY (content_id) REFERENCES route_content (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE route ADD CONSTRAINT FK_2C42079C30C9E2B FOREIGN KEY (redirect) REFERENCES route (route)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE component DROP FOREIGN KEY FK_49FEA157459BC0C4');
        $this->addSql('ALTER TABLE component_location DROP FOREIGN KEY FK_1C9C1D26E2ABAFFF');
        $this->addSql('ALTER TABLE layout DROP FOREIGN KEY FK_3A3A6BE261464C82');
        $this->addSql('ALTER TABLE component_location DROP FOREIGN KEY FK_1C9C1D2684A0A3ED');
        $this->addSql('ALTER TABLE route DROP FOREIGN KEY FK_2C4207984A0A3ED');
        $this->addSql('ALTER TABLE route_content DROP FOREIGN KEY FK_6966D318C22AA1A');
        $this->addSql('ALTER TABLE component DROP FOREIGN KEY FK_49FEA15734ECB4E6');
        $this->addSql('ALTER TABLE route DROP FOREIGN KEY FK_2C42079C30C9E2B');
        $this->addSql('DROP TABLE component');
        $this->addSql('DROP TABLE route_content');
        $this->addSql('DROP TABLE component_location');
        $this->addSql('DROP TABLE layout');
        $this->addSql('DROP TABLE route');
    }
}
