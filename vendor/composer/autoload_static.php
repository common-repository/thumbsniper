<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInita6d1fe3bd45df4bfa2641c8ac3e7e4cf
{
    public static $prefixLengthsPsr4 = array (
        'T' => 
        array (
            'ThumbSniper\\' => 12,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'ThumbSniper\\' => 
        array (
            0 => __DIR__ . '/../..' . '/lib/thumbsniper',
            1 => __DIR__ . '/..' . '/thumbsniper/tooltip/lib/thumbsniper',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInita6d1fe3bd45df4bfa2641c8ac3e7e4cf::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInita6d1fe3bd45df4bfa2641c8ac3e7e4cf::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
