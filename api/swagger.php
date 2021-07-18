<?php

require("vendor/autoload.php");
$openapi = \OpenApi\Generator::scan(['./src/Controller']);
header('Content-Type: application/x-yaml');
file_put_contents('api-endpoints.yml', $openapi->toYaml());
