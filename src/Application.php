<?php

namespace App;

use Silex\Provider;
use Silex\Application as Silex;
use Symfony\Component\Debug\ErrorHandler;
use Symfony\Component\Debug\ExceptionHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Debug\Debug;
use Doctrine\DBAL\Migrations\OutputWriter;
use Symfony\Component\Console\Output\ConsoleOutput;
use Doctrine\DBAL\Migrations\Configuration\Configuration;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;


class Application extends Silex
{
    public function __construct($config_file = 'config.php')
    {
        parent::__construct();
        $this->loadConfig($this, $config_file);
        $this->loadProviders($this);
        $this->loadErrorHandler($this);
        $this->loadModels($this);
        $this->loadRoutes($this);
        $this->loadEventListeners($this);
        $this->loadJson();
    }

    public function loadConfig(Application $app, $config_file)
    {
        $config = require __DIR__ . '/../config/' . $config_file;

        foreach ($config as $key => $value) {
            $app[$key] = $value;
        }
        $app['debug'] = true;
    }

    public function loadModels(Application $app)
    {
        $app['client'] = $app->share(function () use ($app) {
            return new Model\Client($app);
        });
//        $app['document'] = $app->share(function () use ($app) {
//            return new Model\Document($app);
//        });
//        $app['administrator'] = $app->share(function () use ($app) {
//            return new Model\Administrator($app);
//        });
//        $app['task'] = $app->share(function () use ($app) {
//            return new Model\Task($app);
//        });
    }

    public function loadProviders(Application $app)
    {
//        $app['dump.path'] = $app['root.path'];
//        $app->register(new \Sorien\Provider\PimpleDumpProvider());
//        $app->register(new Provider\MonologServiceProvider(), $app['monolog.config']);
        $app->register(new Provider\DoctrineServiceProvider());
        $app->register(new \App\Provider\DoctrineORMServiceProvider());

        $app['migrations.output_writer'] = new OutputWriter(
            function ($message) {
                $output = new ConsoleOutput();
                $output->writeln($message);
            }
        );
        $app['migrations.configuration'] = function () use ($app) {
            $configuration = new Configuration($app['db'], $app['migrations.output_writer']);
            $configuration->setMigrationsDirectory($app['migrations']['directory']);
            $configuration->setName($app['migrations']['name']);
            $configuration->setMigrationsNamespace($app['migrations']['namespace']);
            $configuration->setMigrationsTableName($app['migrations']['table_name']);
            $configuration->registerMigrationsFromDirectory($app['migrations']['directory']);

            return $configuration;
        };

        $app->register(new Provider\ValidatorServiceProvider());

        $app->register(new Provider\ServiceControllerServiceProvider());
    }

    public function loadEventListeners(Application $app)
    {

    }

    public function loadErrorHandler(Application $app)
    {
        $app->error(function (HttpException $e) use ($app) {
            $errCode = $e->getStatusCode();
            $data = [
                'code' => $errCode,
                'status' => 'error',
                'message' => $e->getMessage(),
                'data' => $e->getHeaders(),
            ];

//            $headers['Content-Type'] = 'application/json; charset=UTF-8';
            return new JsonResponse($data, $errCode);
        }, 1000);

        $app->error(function (\Exception $e) use ($app) {
            throw $e;
        });
    }


    public function loadRoutes(Application $app)
    {
        $app->mount('/', new Controller\ClientController());
    }

    protected function loadJson()
    {
        $this->before(function (Request $request) {
            if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
                $data = json_decode($request->getContent(), true);
                $request->request->replace(is_array($data) ? $data : []);
            }
        });
    }

    public function json($data = [], $status = 200, array $headers = [])
    {
        $result = [
            'code' => $status,
            'status' => $status >= 400 ? 'error' : 'success',
            'data' => $data,
        ];

        return new JsonResponse($result, $status, $headers);
    }

}
