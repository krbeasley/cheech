<?php

declare(strict_types=1);

function dd(...$vars) {
    $styling = "background-color: #1b1b1b; padding: 1rem; "
        . "color: rgb(0, 255, 0); border-radius: .5rem; width: 600px;";

    echo "<pre style='$styling'>";

    for ($i = 0; $i < count($vars); $i++) {
        var_dump($vars[$i]);

        if (count($vars) > 1 && $i < count($vars) - 1) {
            echo '----------' . PHP_EOL;
        }
    }

    echo "</pre>";

    die();
}
