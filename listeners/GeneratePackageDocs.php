<?php declare(strict_types=1);

namespace App\Listeners;

use Github\Client;
use TightenCo\Jigsaw\Jigsaw;
use TightenCo\Jigsaw\Parsers\JigsawMarkdownParser;
use function Stringy\create as s;

class GeneratePackageDocs {

    private Client $githubApi;
    private string $packageName;
    private JigsawMarkdownParser $markdownParser;
    private string $reference;

    public function __construct(Client $client, string $packageName, JigsawMarkdownParser $markdownParser, string $reference = 'main') {
        $this->githubApi = $client;
        $this->packageName = $packageName;
        $this->markdownParser = $markdownParser;
        $this->reference = $reference;
    }

    public function __invoke(Jigsaw $jigsaw) : void {
        $packageName = $this->packageName;
        $docDirs = ['how-tos', 'tutorials', 'references'];
        $navConfig = [];
        foreach ($docDirs as $docDir) {
            $navConfig[s($docDir)->camelize()->__toString()] = [];
            $docExists = $this->githubApi->api('repo')->contents()->exists('labrador-kennel', $this->packageName, sprintf('/docs/%s', $docDir), $this->reference);
            if (!$docExists) {
                continue;
            }

            $docs = $this->githubApi->api('repo')->contents()->show('labrador-kennel', $this->packageName, sprintf('/docs/%s', $docDir), $this->reference);
            $docIndexInfo = [];
            foreach ($docs as $doc) {
                $pattern = '#^[0-9]{2}\-#';
                $name = preg_replace($pattern, '', $doc['name']);
                $path = sprintf('docs/%s/%s/%s', $this->packageName, $docDir, $name);
                $docContents = $this->githubApi->api('repo')->contents()->show('labrador-kennel', $this->packageName, $doc['path'], $this->reference);
                $content = explode(PHP_EOL, base64_decode($docContents['content']));

                $title = trim($content[0], '# ');
                $excerpt = '';
                $spaceCounter = 0;

                foreach ($content as $line) {
                    if (empty(trim($line))) {
                        $spaceCounter++;
                    } else if ($line[0] === '#') {
                        continue;
                    } else {
                        $excerpt .= $line;
                    }

                    if ($spaceCounter >= 2) {
                        break;
                    }
                }

                $content = implode(PHP_EOL, $content);
                $contentPath = sprintf('/%s', preg_replace('#\.md$#', '', $path));

                $docIndexInfo[] = [
                    'title' => $title,
                    'excerpt' => $this->markdownParser->transform($excerpt),
                    'path' => $contentPath
                ];
                $navConfig[s($docDir)->camelize()->__toString()][] = [
                    'title' => $title,
                    'path' => $contentPath
                ];

                $content = <<<JIGSAW
---
extends: _layouts.${packageName}
section: content
---

${content}
JIGSAW;

                $jigsaw->writeSourceFile($path, $content);
            }

            $packageName = $this->packageName;
            $indexContent = <<<BLADE
@extends('_layouts.default')

@section('body')
<section class="container">
    <h1>Labrador ${packageName} ${docDir}</h1>
    <div class="columns is-multiline is-centered">
BLADE;
            $docCardTemplate = function(string $title, string $excerpt, string $path) {
                return <<<HTML
<div class="column is-half">
    <div class="card">
        <header class="card-header">
            <p class="card-header-title">${title}</p>
        </header>
        <div class="card-content">
            <div class="content">${excerpt}</div>
        </div>
        <footer class="card-footer">
            <a class="card-footer-item" href="${path}">Read More</a>
        </footer>
    </div>
</div>
HTML;
            };
            foreach ($docIndexInfo as $docStub) {
                $indexContent .= $docCardTemplate($docStub['title'], $docStub['excerpt'], $docStub['path']);
            }

            $indexContent .= '</div></section>@endsection';

            $indexPath = sprintf('docs/%s/%s/index.blade.php', $this->packageName, $docDir);
            $jigsaw->writeSourceFile($indexPath, $indexContent);
        }
        $configDir = sprintf('%s/config', dirname(__DIR__));
        if (!is_dir($configDir)) {
            mkdir($configDir);
        }

        $configPath = sprintf('%s/config/%s-nav.json', dirname(__DIR__), $this->packageName);
        $content = json_encode($navConfig);
        file_put_contents($configPath, $content);
    }

}