<?php

use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use Cache\Adapter\Filesystem\FilesystemCachePool;
use App\Listeners\GeneratePackageDocs;
use App\Listeners\GeneratePackageReadme;
use App\Listeners\GenerateSitemap;
use TightenCo\Jigsaw\Jigsaw;

/** @var $container \Illuminate\Container\Container */
/** @var $events \TightenCo\Jigsaw\Events\EventBus */

/**
 * You can run custom code at different stages of the build process by
 * listening to the 'beforeBuild', 'afterCollections', and 'afterBuild' events.
 *
 * For example:
 *
 * $events->beforeBuild(function (Jigsaw $jigsaw) {
 *     // Your code here
 * });
 */

$filesystemAdapter = new Local(__DIR__ . '/');
$filesystem        = new Filesystem($filesystemAdapter);

$pool = new FilesystemCachePool($filesystem);
$pool->setFolder('/tmp/cache');

$githubApi = new \Github\Client();
$githubApi->addCache($pool);
$githubApi->authenticate(getenv('LABRADOR_KENNEL_SITE_TOKEN'), null, \Github\AuthMethod::ACCESS_TOKEN);
$markdownParser = $container->make(\TightenCo\Jigsaw\Parsers\JigsawMarkdownParser::class);

$customGenerators = [
    new GeneratePackageReadme($githubApi, 'core', '3.1.0'),
    new GeneratePackageReadme($githubApi, 'http-cors', 'main'),
    new GeneratePackageReadme($githubApi, 'async-event', '3.0.0-beta1'),
    new GeneratePackageDocs($githubApi, 'core', $markdownParser, '3.1.0'),
    new GeneratePackageDocs($githubApi, 'http-cors', $markdownParser, 'main'),
    new GeneratePackageDocs($githubApi, 'async-event', $markdownParser, '2.3.0')
];

$events->beforeBuild(function(Jigsaw $jigsaw) use($customGenerators) {
    foreach ($customGenerators as $customGenerator) {
        $customGenerator($jigsaw);
    }
});

$events->afterBuild(GenerateSitemap::class);

$cleanupDirs = ['core', 'http', 'async-event', 'http-cors'];
$events->afterBuild(function() use($cleanupDirs) {
    foreach ($cleanupDirs as $cleanupDir) {
        $dir = __DIR__ . '/source/docs/' . $cleanupDir;
        exec("rm -rf $dir");
    }
});
