<?php declare(strict_types=1);

namespace App\Listeners;

use Github\Client;
use TightenCo\Jigsaw\Jigsaw;

class GeneratePackageReadme {

    private Client $githubApi;
    private string $packageName;
    private string $reference;

    public function __construct(Client $client, string $packageName, string $reference = 'main') {
        $this->githubApi = $client;
        $this->packageName = $packageName;
        $this->reference = $reference;
    }

    public function __invoke(Jigsaw $jigsaw) : void {
        $outputPath = sprintf('docs/%s/index.md', $this->packageName);
        $jigsaw->writeSourceFile($outputPath, $this->getReadMeContent());
    }

    private function getReadMeContent() {
        $readme = $this->githubApi->api('repo')
            ->contents()
            ->readme('labrador-kennel', $this->packageName, $this->reference);

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