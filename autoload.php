<?php declare(strict_types=1);
spl_autoload_register(
    function ($class) {
        // Define the base namespace and corresponding base directory
        $baseNamespace = 'genescu\\components\\';
        $baseDir = __DIR__ . '/src/';
        // Check if the class uses the base namespace
        $len = strlen($baseNamespace);
        if (strncmp($baseNamespace, $class, $len) !== 0) {
            // If the class does not use the base namespace, return to allow other autoloaders to handle it
            return;
        }

        // Remove the base namespace portion and replace namespace separator with directory separator
        $relativeClass = substr($class, $len);
        $classFile = $baseDir . str_replace('\\', DIRECTORY_SEPARATOR, $relativeClass) . '.php';

        // Load the class file if it exists
        if (file_exists($classFile)) {
            include_once $classFile;
        }
    }
);
