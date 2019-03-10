<?php

namespace App\Tests\Rest;

use Doctrine\Bundle\DoctrineBundle\Command\CreateDatabaseDoctrineCommand;
use Doctrine\Bundle\DoctrineBundle\Command\DropDatabaseDoctrineCommand;
use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
//use Symfony\Component\Console\Application;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpKernel\KernelInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Console\Command;



class IndexControllerTest extends WebTestCase
{
    protected function setUp()
    {
        self::bootKernel();

        $application = new Application(self::$kernel);
        $application->setAutoExit(false);

        $dropCommand = new ArrayInput([
            'command' => 'doctrine:database:drop',
            '--force' => true,

        ]);
        $createCommand = new ArrayInput([
            'command' => 'doctrine:database:create',
        ]);
        $schemaCreateCommand = new ArrayInput([
            'command' => 'doctrine:schema:create',
        ]);
        $fixtureLoadCommand = new ArrayInput([
            'command' => 'doctrine:fixtures:load',
            '--append' => true
        ]);

        $application->run($dropCommand);
        $application->run($createCommand);
        $application->run($schemaCreateCommand);
        $application->run($fixtureLoadCommand);
    }

    public function testGetTasks()
    {
        $client = static::createClient([], ['HTTP_HOST' => 'test-api.loc']);
        $client->request('GET', '/api/tasks');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());
        $content = $client->getResponse()->getContent();
        $rowsArray = json_decode($content, true)['rows'];
        $this->assertEquals(5, count($rowsArray));
    }

    public function testGetTaskById()
    {
        $client = static::createClient([], ['HTTP_HOST' => 'test-api.loc']);
        $client->request('GET', '/api/task/1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());

        $responseDataArray = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('title', $responseDataArray);
        $this->assertArrayHasKey('content', $responseDataArray);
        $this->assertContains('Task 1', $responseDataArray['title']);
        $this->assertContains('Content for task 1', $responseDataArray['content']);

    }

    public function testPostRequest()
    {
        $client = static::createClient([], ['HTTP_HOST' => 'test-api.loc']);
        $client->request('POST', '/api/task', ['title' => 'New task', 'content' => 'Content for new task']);
        $this->assertEquals(201, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());

        $responseDataArray = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('title', $responseDataArray);
        $this->assertArrayHasKey('content', $responseDataArray);
        $this->assertContains('New task', $responseDataArray['title']);
        $this->assertContains('Content for new task', $responseDataArray['content']);

    }

    public function testPutRequest()
    {
        $client = static::createClient([], ['HTTP_HOST' => 'test-api.loc']);
        $client->request('PUT', '/api/task/1', ['title' => 'Task 01', 'content' => 'Content for task 01']);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());

        $responseDataArray = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('id', $responseDataArray);
        $this->assertEquals(1, $responseDataArray['id']);
        $this->assertArrayHasKey('title', $responseDataArray);
        $this->assertArrayHasKey('content', $responseDataArray);
        $this->assertContains('Task 01', $responseDataArray['title']);
        $this->assertContains('Content for task 01', $responseDataArray['content']);
    }

    public function testDeleteRequest()
    {
        $client = static::createClient([], ['HTTP_HOST' => 'test-api.loc']);
        $client->request('DELETE', '/api/task/1');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
//    public function test()
//    {
//        $this->markTestIncomplete('Test not implement');
//    }

}