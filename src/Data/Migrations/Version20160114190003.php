<?php

namespace App\Data\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use App\Entity;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160114190003 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $app = new \App\Application();

        $admin1 = new Entity\Administrator();
        $admin1->setName('Администратор 1');
        $app['db.orm.em']->persist($admin1);

        $admin2 = new Entity\Administrator();
        $admin2->setName('Администратор 2');
        $app['db.orm.em']->persist($admin2);

        $admin3 = new Entity\Administrator();
        $admin3->setName('Администратор 3');
        $app['db.orm.em']->persist($admin3);


        $client1 = new Entity\Client();
        $client1->setName('Иванов Иван')
                ->setEmail('ivanov@email.com')
                ->setPhone('+7 (912) 12-34-567')
                ->setStatus('active');
        $app['db.orm.em']->persist($client1);

        $client2 = new Entity\Client();
        $client2->setName('Петров Петр')
                ->setEmail('petrov@email.com')
                ->setPhone('+7 (912) 12-34-567')
                ->setStatus('possible');
        $app['db.orm.em']->persist($client2);

        $client3 = new Entity\Client();
        $client3->setName('Сидоров Дмитрий')
                ->setEmail('sidorov@email.com')
                ->setPhone('+7 (912) 12-34-567')
                ->setStatus('old');
        $app['db.orm.em']->persist($client3);

        $client4 = new Entity\Client();
        $client4->setName('Коловорот Евпатий')
                ->setEmail('kolovorot@email.com')
                ->setPhone('+7 (912) 12-34-567')
                ->setStatus('old');
        $app['db.orm.em']->persist($client4);

        $client5 = new Entity\Client();
        $client5->setName('Ермак Тимофеевич')
                ->setEmail('ermak@email.com')
                ->setPhone('+7 (912) 12-34-567')
                ->setStatus('old');
        $app['db.orm.em']->persist($client5);

        $client6 = new Entity\Client();
        $client6->setName('Крузенштерн Федор')
                ->setEmail('fedor@email.com')
                ->setPhone('+7 (912) 12-34-567')
                ->setStatus('old');
        $app['db.orm.em']->persist($client6);


        $task1 = new Entity\Task();
        $task1->setName('Написать клиенту')
              ->setStatus('new')
              ->setDescription('Необходимо Написать клиенту')
              ->setAdministrator($admin1);
        $app['db.orm.em']->persist($task1);

        $task2 = new Entity\Task();
        $task2->setName('Выставить счет')
              ->setDescription('Необходимо Выставить счет')
              ->setStatus('progress')
              ->setAdministrator($admin2);
        $app['db.orm.em']->persist($task2);

        $task3 = new Entity\Task();
        $task3->setName('Составить список задач за неделю')
              ->setDescription('Необходимо Составить список задач за неделю')
              ->setStatus('close')
              ->setAdministrator($admin3);
        $app['db.orm.em']->persist($task3);


        $doc1 = new Entity\Document();
        $doc1->setName('Счет.doc')
             ->setAdministrator($admin1)
             ->setClient($client1)
             ->setTask($task1);
        $app['db.orm.em']->persist($doc1);

        $doc2 = new Entity\Document();
        $doc2->setName('Акт.doc')
             ->setAdministrator($admin2)
             ->setClient($client1)
             ->setTask($task2);
        $app['db.orm.em']->persist($doc2);

        $doc3 = new Entity\Document();
        $doc3->setName('Коммерческое предложениеие.doc')
             ->setAdministrator($admin2)
        ;
        $app['db.orm.em']->persist($doc3);


        $app['db.orm.em']->flush();
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
