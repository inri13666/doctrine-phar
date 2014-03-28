<?php
if (!function_exists('__def')) {
    function __def($constant, $value)
    {
        if (strlen($constant) <= 0) {
            return false;
        }
        if (!defined($constant)) {
            define($constant, $value);
            return true;
        }
        //Already Defined
        return false;
    }
}
__def('DS', DIRECTORY_SEPARATOR);

if (version_compare(PHP_VERSION, '5.3.0') < 0) {
    exit("PHP must be 5.3.0+");
}

Phar::mapPhar();
$basePath = 'phar://' . __FILE__ . '/';

/**
 * Default ENVIRONMENT
 */
__def('ENVIRONMENT', 'development');

/**
 * Define Default Way To Use Docrine As Library Or As CLI-app
 */
__def('DOCTRINE_CLI', false); //Default We Use Doctrine As Library

if (DOCTRINE_CLI) {
    /**
     * Cli Mode
     */
    throw new Exception('CLI mode Not Implemented');
} else {
    if (class_exists('\Doctrine\Common\ClassLoader', false)) {
        /**
         * Nothing to do Doctrine Already Registered
         */
        return;
    } else {
        /**
         * Load Doctrine Class Loader First
         */
        require_once $basePath . 'Doctrine/Common/ClassLoader.php';
    }

    $components = array(
        'Doctrine' => $basePath, /*Doctrine Library*/
        'Symphony' => $basePath . 'Doctrine', /*Doctrine Symphony Dependency*/
    );

    foreach ($components as $component => $path) {
        if (@\Doctrine\Common\ClassLoader::getClassLoader($component)) {

        } else {
            $classLoader = new \Doctrine\Common\ClassLoader($component, $path);
            $classLoader->register();
        }
    }
}

__HALT_COMPILER();
?>
