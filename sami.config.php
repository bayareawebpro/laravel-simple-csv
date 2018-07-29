<?php
use Sami\Sami;
use Sami\RemoteRepository\GitHubRemoteRepository;
use Symfony\Component\Finder\Finder;
$iterator = Finder::create()
    ->files()
    ->name('*.php')
    ->in(__DIR__ .'/src')
;

return new Sami($iterator,[
    'theme'     => 'default',
    'title'     => 'Simple CSV',
    'author'    => 'Dan Alvidrez',
    'build_dir' => __DIR__ . '/_docs',
    'cache_dir' => __DIR__ . '/_docs/cache',
    'default_opened_level' => 2,
    'include_parent_data' => false,
]);
