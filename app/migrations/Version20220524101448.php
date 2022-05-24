<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220524101448 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE favorite_games (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_49AB0015A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE favorite_games_game (favorite_games_id INT NOT NULL, game_id INT NOT NULL, INDEX IDX_22981FA83DA8DE82 (favorite_games_id), INDEX IDX_22981FA8E48FD905 (game_id), PRIMARY KEY(favorite_games_id, game_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE favorite_games ADD CONSTRAINT FK_49AB0015A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE favorite_games_game ADD CONSTRAINT FK_22981FA83DA8DE82 FOREIGN KEY (favorite_games_id) REFERENCES favorite_games (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE favorite_games_game ADD CONSTRAINT FK_22981FA8E48FD905 FOREIGN KEY (game_id) REFERENCES game (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE favorite_game_game');
        $this->addSql('DROP TABLE favorite_movies');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE favorite_games_game DROP FOREIGN KEY FK_22981FA83DA8DE82');
        $this->addSql('CREATE TABLE favorite_game_game (favorite_game_id INT NOT NULL, game_id INT NOT NULL, INDEX IDX_22EC832B9DB776B (favorite_game_id), INDEX IDX_22EC832E48FD905 (game_id), PRIMARY KEY(favorite_game_id, game_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE favorite_movies (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_FAAB81CAA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE favorite_game_game ADD CONSTRAINT FK_22EC832E48FD905 FOREIGN KEY (game_id) REFERENCES game (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE favorite_movies ADD CONSTRAINT FK_FAAB81CAA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('DROP TABLE favorite_games');
        $this->addSql('DROP TABLE favorite_games_game');
    }
}
