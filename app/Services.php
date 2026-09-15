<?php

declare(strict_types=1);

namespace App;

use TetherPHP\framework\Interfaces\ServicesInterface;
use TetherPHP\framework\Modules\Env;
use TetherPHP\framework\Modules\Log;

/*
 * What the application is made of, in one place.
 *
 * public/index.php builds one of these and hands it to the Kernel, which reads
 * the Env and the Log off it — the two properties ServicesInterface asks for —
 * and hands it to every Action. An Action passes its Domain the pieces the
 * Domain asks for — `new IndexDomain($services->env)` — never the whole
 * object, so a Domain's constructor still says exactly what it depends on and
 * a unit test can build one with a fake.
 *
 * To add a dependency, add a property here and build it in public/index.php:
 *
 *     public PDO $db,                              // here
 *     db: new PDO($env->get('DB_DSN') ?? ''),      // in public/index.php
 *
 * That is the whole mechanism. There is no container, nothing is resolved by
 * name, and a class of your own that this holds lives under app/Services/ in
 * the App\Services namespace. Something expensive to build that most requests
 * never use should connect on first use — the way Session does — rather than
 * this object becoming lazy.
 */
final readonly class Services implements ServicesInterface
{
    public function __construct(
        public Env $env,
        public Log $log,
    ) {
    }
}
