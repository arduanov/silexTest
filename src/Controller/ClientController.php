<?php

namespace App\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity;

class ClientController implements \Silex\ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];

        $controllers->get('/client', [$this, 'clientsList']);
        $controllers->get('/client/{id}', [$this, 'clientView'])->assert('id', '\d+');
        $controllers->put('/client/{id}', [$this, 'clientEdit'])->assert('id', '\d+');
        $controllers->post('/client', [$this, 'clientCreate']);

        $controllers->put('/client/{id}/document/{document_id}', [$this, 'clientDocumentAdd'])
                    ->assert('document_id', '\d+')->assert('id', '\d+');
        $controllers->delete('/client/{id}/document/{document_id}', [$this, 'clientDocumentDelete'])
                    ->assert('document_id', '\d+')->assert('id', '\d+');

        return $controllers;
    }

    public function clientsList(Application $app, Request $request)
    {
        $offset = $request->get('offset', 0);
        $limit = $request->get('limit', 5);

        if (!in_array($limit, [5, 10, 20])) {
            $app->abort(400);
        }

        $data = $app['client']->listClients($offset, $limit);
        return $app->json($data);
    }

    public function clientView(Application $app, Request $request, $id)
    {
        $client = $app['client']->findOneWithDocs($id);
        return $app->json($client);
    }

    public function clientCreate(Application $app, Request $request)
    {
        $fields = $request->request->all();
        return $this->clientUpdate($app, $fields);
    }

    public function clientEdit(Application $app, Request $request, $id)
    {
        $fields = $request->request->all();
        $fields['id'] = $id;
        return $this->clientUpdate($app, $fields);
    }

    private function clientUpdate(Application $app, $fields)
    {
        $constraint = new Assert\Collection([
            'fields' => [
                'name' => [
                    new Assert\Length(['min' => 2, 'max' => 255]),
                    new Assert\NotBlank(),
                ],
                'email' => [
                    new Assert\Email(),
                    new Assert\NotBlank(),
                ],
                'phone' => [
                    new Assert\Length(['min' => 4, 'max' => 30]),
                ],
                'status' => [
                    new Assert\Choice(array_values(Entity\Client::$STATUS)),
                    new Assert\NotBlank(),
                ],

            ],
            'allowMissingFields' => false,
            'allowExtraFields' => true,
            'extraFieldsMessage' => 'This {{ field }} field was not expected.',
            'missingFieldsMessage' => 'This {{ field }} field is missing.',

        ]);

        $errors = $app['validator']->validateValue($fields, $constraint);
        if (count($errors) > 0) {
            $message = 'Bad Request. ';
            foreach ($errors as $error) {
                $message .= ' ' . $error->getMessage() . "\n";
            }
            $app->abort(400, $message);
        }

        $client = $app['client']->editClient($fields);
        return $app->json($client, 201);
    }

    public function clientDocumentDelete(Application $app, Request $request, $id, $document_id)
    {
        $app['client']->deleteDocument($id, $document_id);
        return $app->json([], 204);
    }

    public function clientDocumentAdd(Application $app, Request $request, $id, $document_id)
    {
        $app['client']->addDocument($id, $document_id);
        return $app->json([], 204);
    }
}