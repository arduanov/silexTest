<?php

namespace App\Provider;


use \Doctrine\DBAL\Configuration as DBALConfiguration,
    \Doctrine\DBAL\DriverManager;

use \Doctrine\ORM\Configuration as ORMConfiguration,
    \Doctrine\ORM\Mapping\Driver\AnnotationDriver,
    \Doctrine\ORM\Mapping\Driver\YamlDriver,
    \Doctrine\ORM\Mapping\Driver\XmlDriver,
    \Doctrine\ORM\EntityManager;

use \Doctrine\Common\Persistence\Mapping\Driver\MappingDriverChain,
    \Doctrine\Common\Annotations\AnnotationReader,
    \Doctrine\Common\Cache\ArrayCache,
    \Doctrine\Common\EventManager;

use \Doctrine\Common\Proxy\Autoloader;

use \Silex\Application;
use \Silex\ServiceProviderInterface;

class DoctrineORMServiceProvider implements ServiceProviderInterface
{


    public function register(Application $app)
    {
        if (!$app['db'] instanceof \Doctrine\DBAL\Connection) {
            throw new \InvalidArgumentException('$app[\'db\'] must be an instance of \Doctrine\DBAL\Connection');
        }

        $this->loadDoctrineConfiguration($app);
        $this->setOrmDefaults($app);
        $this->loadDoctrineOrm($app);
    }

    public function boot(Application $app)
    {

    }

    private function loadDoctrineOrm(Application $app)
    {
        $app['em'] = $app['db.orm.em'] = $app->share(function () use ($app) {
            $em = EntityManager::create($app['db'], $app['db.orm.config']);
            return $em;
        });
    }

    private function setOrmDefaults(Application $app)
    {

        $defaults = [
            'entities' => [
                [
                    'type' => 'annotation',
                    'path' => __DIR__ . '/../Entity/',
                    'namespace' => 'App\Entity',
                ]
            ],

            'proxies_dir' => __DIR__ . '/../Proxy',
            'proxies_namespace' => 'DoctrineProxy',
            'auto_generate_proxies' => true,
            'cache' => new ArrayCache('/tmp'),
        ];

        foreach ($defaults as $key => $value) {
            if (!isset($app['db.orm.' . $key])) {
                $app['db.orm.' . $key] = $value;
            }
        }
    }

    public function loadDoctrineConfiguration(Application $app)
    {
        $app['db.orm.config'] = $app->share(function () use ($app) {

            $cache = $app['db.orm.cache'];

            $config = new ORMConfiguration;
            $config->setMetadataCacheImpl($cache);
            $config->setQueryCacheImpl($cache);

            $chain = new MappingDriverChain;

            foreach ((array)$app['db.orm.entities'] as $entity) {
                switch ($entity['type']) {
                    case 'default':
                    case 'annotation':
                        $driver = $config->newDefaultAnnotationDriver((array)$entity['path'], false);
                        $chain->addDriver($driver, $entity['namespace']);
                        break;
                    case 'yml':
                        $driver = new YamlDriver((array)$entity['path']);
                        $driver->setFileExtension('.yml');
                        $chain->addDriver($driver, $entity['namespace']);
                        break;
                    case 'xml':
                        $driver = new XmlDriver((array)$entity['path'], $entity['namespace']);
                        $driver->setFileExtension('.xml');
                        $chain->addDriver($driver, $entity['namespace']);
                        break;
                    default:
                        throw new \InvalidArgumentException(sprintf('"%s" is not a recognized driver', $entity['type']));
                        break;
                }
            }

            $config->setMetadataDriverImpl($chain);

            $config->setProxyDir($app['db.orm.proxies_dir']);
            $config->setProxyNamespace($app['db.orm.proxies_namespace']);
            $config->setAutoGenerateProxyClasses($app['db.orm.auto_generate_proxies']);
            $config->addCustomHydrationMode('SimpleArrayHydrator', 'App\Provider\SimpleArrayHydrator');

            // автолоад прокси
            $proxyDir = $app['db.orm.proxies_dir'];
            $proxyNamespace = $app['db.orm.proxies_namespace'];
            Autoloader::register($proxyDir, $proxyNamespace);

            return $config;
        });
    }

}