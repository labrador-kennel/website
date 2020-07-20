<?php declare(strict_types=1);

namespace App\Listeners;

use Github\Client;
use TightenCo\Jigsaw\Jigsaw;

class GeneratePackageReadme {

    private Client $githubApi;
    private string $packageName;

    public function __construct(Client $client, string $packageName) {
        $this->githubApi = $client;
        $this->packageName = $packageName;
    }

    public function __invoke(Jigsaw $jigsaw) : void {
        $outputPath = sprintf('docs/%s/index.md', $this->packageName);
        $jigsaw->writeSourceFile($outputPath, $this->getReadMeContent());
    }

    private function getReadMeContent() {
        $readme = $this->githubApi->api('repo')
            ->contents()
            ->readme('labrador-kennel', $this->packageName);

        $packageName = $this->packageName;
        $decodedReadmeContent = base64_decode($readme['content']);
        return <<<JIGSAW
---
extends: _layouts.${packageName}
section: content
---

${decodedReadmeContent}
JIGSAW;
    }

}