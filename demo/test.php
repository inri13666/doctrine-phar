<?php
/**
 * User  : Nikita.Makarov
 * Date  : 3/28/14
 * Time  : 7:32 PM
 * E-Mail: nikita.makarov@effective-soft.com
 */
use Doctrine\DBAL\Driver\ServerInfoAwareConnection;

$doctrine_path = implode(DIRECTORY_SEPARATOR, array(dirname(dirname(__FILE__)), 'compiled')) . DIRECTORY_SEPARATOR . 'doctrine.';

if (extension_loaded('bz2')) {
    require_once 'phar://' . $doctrine_path . 'bz2';
} elseif (extension_loaded('zlib')) {
    require_once 'phar://' . $doctrine_path . 'gz';
} else {
    require_once 'phar://' . $doctrine_path . 'phar';
}

echo ("Common Version\t:\t" . Doctrine\Common\Version::VERSION) . PHP_EOL;
echo ("DBAL Version\t:\t" . Doctrine\DBAL\Version::VERSION) . PHP_EOL;
echo ("ORM Version\t:\t" . Doctrine\ORM\Version::VERSION) . PHP_EOL;

$db['host'] = 'localhost';
$db['user'] = 'root';
$db['password'] = '';
$db['dbname'] = 'maxmind';
$db['driver'] = 'pdo_mysql';

$cache = new \Doctrine\Common\Cache\FilesystemCache(sys_get_temp_dir());

$config = new  \Doctrine\DBAL\Configuration();

$config->setResultCacheImpl($cache);

$conn = \Doctrine\DBAL\DriverManager::getConnection($db, $config);
new \Doctrine\DBAL\Cache\QueryCacheProfile(0, "some key", $cache);
var_dump($conn->getWrappedConnection()->getServerVersion());
//var_dump($conn);die();
//echo ("Server Version\t:\t" . $conn->getWrappedConnection()->getServerVersion()) . PHP_EOL;

$queryBuilder = $conn->createQueryBuilder();
$t = microtime(true);
$stm = $queryBuilder
    ->select('u.*')
    ->from('ip_country_v4', 'u')
    ->execute();
$stm->fetchAll();
$stm->closeCursor();
echo (($x = microtime(true)) - $t) . PHP_EOL;
$stm = $queryBuilder
    ->select('u.*')
    ->from('ip_country_v4', 'u')
    ->execute();
$stm->fetchAll();
$stm->closeCursor();
echo (($t = microtime(true)) - $x) . PHP_EOL;
