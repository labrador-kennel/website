<?php declare(strict_types=1);

namespace App\Listeners;

use Amp\Http\Client\HttpClientBuilder;
use Amp\Http\Client\Request;
use Amp\Http\Client\Response;
use TightenCo\Jigsaw\Jigsaw;
use function Amp\Promise\wait;

class DeployToNetlify {

    public function handle(Jigsaw $jigsaw) {
        if ($jigsaw->getEnvironment() === 'production') {
            $fileDigest = $this->generateFileDigest();
            $body = [
                'files' => $fileDigest
            ];
            $token = getenv('NETLIFY_TOKEN');
            if (!$token) {
                echo 'Could not find the netlify token. Skipping deploy!';
                return;
            }

            $apiUrl = 'https://api.netlify.com/api/v1';
            $deployUrl = "$apiUrl/sites/6518f0ed-3a88-44f7-93dd-f89036fe0c92/deploys";

            $request = new Request($deployUrl, 'POST', json_encode($body));
            $request->setHeader('Authorization', "Bearer ${token}");
            $request->setHeader('Content-Type', 'application/json');

            $client = HttpClientBuilder::buildDefault();

            /** @var Response $response */
            $response = wait($client->request($request));

            $body = wait($response->getBody()->buffer());
            $deployData = json_decode($body, true);
            $deployId = $deployData['id'];
            $uploadUrl = "$apiUrl/deploys/$deployId/files";

            foreach ($deployData['required'] as $sha) {
                $path = array_keys($fileDigest, $sha)[0];
                $encodedPath = urlencode($path);
                $uploadUrl .= "/$encodedPath";
                $filePath = dirname(__DIR__) . '/build_production' . $path;

                $request = new Request($uploadUrl, 'PUT', file_get_contents($filePath));
                $request->setHeader('Authorization', "Bearer ${token}");
                $request->setHeader('Content-Type', 'application/octet-stream');

                echo 'Uploading ' . $filePath . PHP_EOL;

                wait($client->request($request));
            }

            echo 'Locking deploy' . PHP_EOL;
            $lockDeployUrl = "$apiUrl/deploys/$deployId/lock";
            $request = new Request($lockDeployUrl, 'POST');
            $request->setHeader('Authorization', "Bearer ${token}");

            wait($client->request($request));
        }
    }

    private function generateFileDigest() : array {
        echo 'Generating file digest...' . PHP_EOL;
        $buildDir = dirname(__DIR__) . '/build_production';
        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($buildDir));
        $digest = [];

        /** @var \SplFileInfo $fileInfo */
        foreach ($iterator as $fileInfo) {
            if ($fileInfo->isDir()) {
                continue;
            }

            $path = $fileInfo->getPathname();
            $pattern = '#' . $buildDir . '#';
            $url = preg_replace($pattern, '', $path);
            $digest[$url] = sha1_file($path);
        }

        return $digest;
    }

}