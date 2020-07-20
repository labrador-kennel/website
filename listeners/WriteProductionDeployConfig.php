<?php declare(strict_types=1);

namespace App\Listeners;

use TightenCo\Jigsaw\Jigsaw;

class WriteProductionDeployConfig {

    public function handle(Jigsaw $jigsaw) {
        if ($jigsaw->getEnvironment() === 'production') {
            $path = dirname(__DIR__) . '/config/netlify.toml';
            $jigsaw->writeOutputFile('netlify.toml', file_get_contents($path));
        }
    }

}