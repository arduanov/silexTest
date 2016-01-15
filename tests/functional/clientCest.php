<?php
namespace account;

use \FunctionalTester;

class clientCest
{
    public function getClientList(FunctionalTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendGET('/client');
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(200);

        $data = $I->grabDataFromResponseByJsonPath('$.data')[0];
        $I->assertEquals(6, $data['count']);
        $I->assertEquals(5, count($data['clients']));

        $I->amGoingTo('test offset');
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendGET('/client', ['offset' => 4]);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(200);

        $data = $I->grabDataFromResponseByJsonPath('$.data')[0];
        $I->assertEquals(6, $data['count']);
        $I->assertEquals(2, count($data['clients']));


        $I->amGoingTo('test limit');
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendGET('/client', ['limit' => 10]);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(200);

        $data = $I->grabDataFromResponseByJsonPath('$.data')[0];
        $I->assertEquals(6, $data['count']);
        $I->assertEquals(6, count($data['clients']));
    }

    public function getClient(FunctionalTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendGET('/client/1');
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(200);

        $I->canSeeResponseIsValidOnSchemaFile(codecept_data_dir() . 'schema/client.json');
    }

    public function editClient(FunctionalTester $I)
    {
        $client_data = json_decode('{
    "id": 1,
    "name": "Супер Иванов Иван",
    "email": "testivanov@email.com",
    "phone": "+7 (912) 12-34-99999",
    "status": "possible"
}', true);

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPUT('/client/1', $client_data);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(201);

        $I->canSeeResponseIsValidOnSchemaFile(codecept_data_dir() . 'schema/client.json');

        $data = $I->grabDataFromResponseByJsonPath('$.data')[0];
        $I->assertEquals($client_data, $data);
    }

    public function addClient(FunctionalTester $I)
    {
        $client_data = json_decode('{
    "name": "testname",
    "email": "test@email.com",
    "phone": "+7 (912) 12-34-99999",
    "status": "possible"
}', true);

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/client', $client_data);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(201);

        $I->canSeeResponseIsValidOnSchemaFile(codecept_data_dir() . 'schema/client.json');

        $data = $I->grabDataFromResponseByJsonPath('$.data')[0];
        unset($data['id']);
        $I->assertEquals($client_data, $data);
    }

    public function removeClientDocument(FunctionalTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendDELETE('/client/1/document/1');
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(204);
        $I->seeInDatabase('document', ['id' => 1, 'client_id' => null]);
    }

    public function addClientDocument(FunctionalTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPUT('/client/1/document/3');
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(204);
        $I->seeInDatabase('document', ['id' => 3, 'client_id' => 1]);
    }
}