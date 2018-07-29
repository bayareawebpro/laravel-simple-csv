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
    'title'     => 'Laravel Simple CSV',
    'author'    => 'Dan Alvidrez',
    'build_dir' => __DIR__ . '/_docs',
    'cache_dir' => __DIR__ . '/_docs/cache',
    'remote_repository'    => new GitHubRemoteRepository('bayareawebpro/laravel-simple-csv', __DIR__),
    'default_opened_level' => 2,
    'include_parent_data' => false,
]);
