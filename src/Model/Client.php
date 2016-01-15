<?php

namespace App\Model;

use App\Entity;
use App\Application;
use App\Exception\BadRequestException;
use Doctrine\ORM\Query;
use App\Exception\NotFoundException;

class Client
{
    use Behavior\Find;

    private $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function findOneWithDocs($client_id)
    {
        $qb = $this->app['em']->getRepository('App\Entity\Client')
                              ->createQueryBuilder('c')
                              ->select('partial c.{id,name,email,phone,status},partial d.{id,name}')
                              ->join('c.documents', 'd')
                              ->where('c.id = :client_id')
                              ->setParameter(':client_id', $client_id);

        $result = $qb->getQuery()
//                     ->setHint(Query::HINT_INCLUDE_META_COLUMNS, true)
                     ->getResult(Query::HYDRATE_ARRAY);

        if (empty($result)) {
            throw new NotFoundException();
        }

        return $result[0];
    }

    public function editClient($data)
    {
        if (isset($data['id'])) {

            /**
             * @var $client \App\Entity\Client
             */
            $client = $this->app['em']->getRepository('App\Entity\Client')
                                      ->findOneById($data['id']);

            if (empty($client)) {
                throw new NotFoundException();
            }
        } else {
            $client = new Entity\Client();
        }

        $client->setName($data['name']);
        $client->setPhone($data['phone']);
        $client->setEmail($data['email']);
        $client->setStatus($data['status']);

        $this->app['em']->persist($client);
        $this->app['em']->flush();

        return [
            'id' => $client->getId(),
            'name' => $client->getName(),
            'email' => $client->getEmail(),
            'phone' => $client->getPhone(),
            'status' => $client->getStatus()
        ];

    }

    public function deleteDocument($client_id, $document_id)
    {
        $document = $this->app['em']->getRepository('App\Entity\Document')
                                    ->findOneBy(['id' => $document_id, 'client' => $client_id]);
        if (empty($document)) {
            throw new BadRequestException();
        }

        $document->setClient();
        $this->app['em']->persist($document);
        $this->app['em']->flush();
    }

    public function addDocument($client_id, $document_id)
    {
        $document = $this->app['em']->getRepository('App\Entity\Document')
                                    ->findOneById($document_id);
        if (empty($document)) {
            throw new BadRequestException();
        }

        $document->setClient($this->app['em']->getReference('App\Entity\Client', $client_id));
        $this->app['em']->persist($document);
        $this->app['em']->flush();
    }

    public function listClients($offset, $limit)
    {
        $clients = $this->app['em']->getRepository('App\Entity\Client')
                                   ->createQueryBuilder('c')
                                   ->select('partial c.{id,name,email,phone,status}')
                                   ->setFirstResult($offset)
                                   ->setMaxResults($limit)
                                   ->getQuery()
                                   ->getResult(Query::HYDRATE_ARRAY);

        $count = $this->app['em']->getRepository('App\Entity\Client')
                                 ->createQueryBuilder('c')
                                 ->select('count(c.id)')
                                 ->getQuery()
                                 ->getSingleScalarResult();
        return [
            'count' => $count,
            'clients' => $clients
        ];
    }

}
