<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitb6e126ac113125fe9f65e36e191c1dc6
{
    public static $files = array (
        '880da49e486e7549b21df41865f33ab7' => __DIR__ . '/..' . '/masnathan/odtphp/lib/pclzip.lib.php',
    );

    public static $prefixLengthsPsr4 = array (
        'O' => 
        array (
            'Odtphp\\' => 7,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Odtphp\\' => 
        array (
            0 => __DIR__ . '/..' . '/masnathan/odtphp/src',
        ),
    );

    public static $classMap = array (
        'Odtphp\\Exceptions\\OdfException' => __DIR__ . '/..' . '/masnathan/odtphp/src/Exceptions/OdfException.php',
        'Odtphp\\Exceptions\\PclZipProxyException' => __DIR__ . '/..' . '/masnathan/odtphp/src/Exceptions/PclZipProxyException.php',
        'Odtphp\\Exceptions\\PhpZipProxyException' => __DIR__ . '/..' . '/masnathan/odtphp/src/Exceptions/PhpZipProxyException.php',
        'Odtphp\\Exceptions\\SegmentException' => __DIR__ . '/..' . '/masnathan/odtphp/src/Exceptions/SegmentException.php',
        'Odtphp\\Odf' => __DIR__ . '/..' . '/masnathan/odtphp/src/Odf.php',
        'Odtphp\\Segment' => __DIR__ . '/..' . '/masnathan/odtphp/src/Segment.php',
        'Odtphp\\SegmentIterator' => __DIR__ . '/..' . '/masnathan/odtphp/src/SegmentIterator.php',
        'Odtphp\\Zip\\PclZipProxy' => __DIR__ . '/..' . '/masnathan/odtphp/src/Zip/PclZipProxy.php',
        'Odtphp\\Zip\\PhpZipProxy' => __DIR__ . '/..' . '/masnathan/odtphp/src/Zip/PhpZipProxy.php',
        'Odtphp\\Zip\\ZipInterface' => __DIR__ . '/..' . '/masnathan/odtphp/src/Zip/ZipInterface.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitb6e126ac113125fe9f65e36e191c1dc6::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitb6e126ac113125fe9f65e36e191c1dc6::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitb6e126ac113125fe9f65e36e191c1dc6::$classMap;

        }, null, ClassLoader::class);
    }
}
