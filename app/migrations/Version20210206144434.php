<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210206144434 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE author (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE book (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, author_id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE book ADD CONSTRAINT FK_CBE5A331F675F31B FOREIGN KEY (author_id) REFERENCES author (id)');
        $this->addSql('CREATE INDEX IDX_CBE5A331F675F31B ON book (author_id)');
        // insert into author
        $this->addSql("INSERT INTO author (name)
            VALUES ('Лев Толстой'), ('Николай Гоголь'), ('Фёдор Достоевский'), ('Антон Чехов')
        ");
        // insert into book
        $this->addSql("INSERT INTO book (name, author_id)
            VALUES
                 ('War and peace|Война и мир', (SELECT id FROM author WHERE name = 'Лев Толстой'))
                ,('The Overcoat|Шинель', (SELECT id FROM author WHERE name = 'Николай Гоголь'))
                ,('Crime and Punishment|Преступление и Наказание', (SELECT id FROM author WHERE name = 'Фёдор Достоевский'))
                ,('Three sisters|Три сестры', (SELECT id FROM author WHERE name = 'Антон Чехов'));
        ");
        $faker = \Faker\Factory::create();
        $faker->addProvider(new \Faker\Provider\en_US\Text($faker));
        $authorValues = [];
        $bookValues = [];
        for ($i = 5; $i < 10000; $i++) {
            $words = $faker->randomElement([1, 2, 3, 4]);
            $author = str_replace("'", "`", $faker->name());
            $authorValues[] = "('{$author}')";
            $bookValues[] = "('{$faker->sentence($words)}|{$faker->sentence($words)}', {$i})";
        }
        $authorValuesString = implode(',', $authorValues);
        $this->addSql("INSERT INTO author (name) VALUES {$authorValuesString}");
        $bookValuesString = implode(',', $bookValues);
        $this->addSql("INSERT INTO book (name, author_id) VALUES {$bookValuesString}");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE book DROP FOREIGN KEY FK_CBE5A331F675F31B');
        $this->addSql('DROP TABLE author');
        $this->addSql('DROP TABLE book');
    }
}
