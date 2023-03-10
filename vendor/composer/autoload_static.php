<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit7354fb90e3d99770cb8e59a3c54ae5eb
{
    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Stripe\\' => 7,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Stripe\\' => 
        array (
            0 => __DIR__ . '/..' . '/stripe/stripe-php/lib',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit7354fb90e3d99770cb8e59a3c54ae5eb::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit7354fb90e3d99770cb8e59a3c54ae5eb::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit7354fb90e3d99770cb8e59a3c54ae5eb::$classMap;

        }, null, ClassLoader::class);
    }
}
