<?php

namespace Tree6bee\Support\Ctx\Loader;

/**
 * 框架加载类
 *
 * @example 
 *      new \Tree6bee\Support\Ctx\Loader\Psr4(array('Ctx\\' => __DIR__ . '/../Ctx'));
 */
class Psr4
{
    private $psr4map = array();

    public function __construct($psr4map, $prepend = false)
    {
        $this->psr4map = $psr4map;
        spl_autoload_register(array($this, 'loadClass'), true, $prepend);
    }


    /**
     * 框架核心类自动加载方法
     */
    public function loadClass($className)
    {
        $logicalPathPsr4 = strtr($className, '\\', DIRECTORY_SEPARATOR) . '.php';
        foreach ($this->psr4map as $prefix => $dir) {
            if (0 === strpos($className, $prefix)) {   //class with namespace
                $length = strlen($prefix);
                $classFile = $dir . DIRECTORY_SEPARATOR . substr($logicalPathPsr4, $length);
                if (is_file($classFile)) {
                    includeFile($classFile);
                    return class_exists($className, false) || interface_exists($className, false);
                }
            }
        }
        return false;
    }
}
