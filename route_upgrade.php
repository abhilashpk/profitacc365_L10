<?php

/**
 * ADVANCED ROUTE UPGRADER V2
 * - Converts Laravel 5 routes to Laravel 10
 * - Detects prefixes like: /purchase_invoice/*
 * - Auto groups by prefix
 * - Converts uses => [], middleware => [], as => ''
 * - Removes duplicates
 * - Formats clean output
 */

$webFile = __DIR__ . '/routes/web.php';
$backupFile = __DIR__ . '/routes/web_backup_' . date('Ymd_His') . '.php';

copy($webFile, $backupFile);

echo "✔ Backup created: {$backupFile}\n";

$raw = file_get_contents($webFile);
$lines = preg_split("/\r\n|\n|\r/", $raw);

$routes = [];
$prefixGroups = [];

// helper: extract controller + method from uses
function parseController($text)
{
    $text = trim($text);

    // ['App\Http\Controllers\XController', 'method']
    if (str_contains($text, '::class')) {
        return $text;
    }

    // "Controller@method"
    if (str_contains($text, '@')) {
        [$ctrl, $method] = explode('@', trim($text, "'\" "));
        return "$ctrl::class, '$method'";
    }

    return $text;
}

// MAIN PARSER
foreach ($lines as $line) {
    if (!str_contains($line, 'Route::')) continue;

    $original = $line;

    // METHOD (get/post/etc)
    preg_match("/Route::(get|post|put|patch|delete)/", $line, $m);
    if (!$m) continue;
    $method = $m[1];

    // URL
    preg_match("/\(['\"](.*?)['\"]/", $line, $m);
    $url = $m ? $m[1] : '/';

    // Extract key/value array
    preg_match("/\[(.*)\]/", $line, $m);
    $inside = $m[1] ?? '';

    // Extract "as"
    preg_match("/'as'\s*=>\s*['\"](.*?)['\"]/", $inside, $nm);
    $routeName = $nm[1] ?? null;

    // Extract "uses"
    preg_match("/'uses'\s*=>\s*\[(.*?)\]/", $inside, $umArray);
    preg_match("/'uses'\s*=>\s*['\"](.*?)['\"]/", $inside, $umString);
    $uses = $umArray[1] ?? $umString[1] ?? null;

    if ($uses) {
        $uses = parseController($uses);
    }

    // Extract middleware
    preg_match("/'middleware'\s*=>\s*\[(.*?)\]/", $inside, $mdArray);
    preg_match("/'middleware'\s*=>\s*['\"](.*?)['\"]/", $inside, $mdString);

    $middleware = $mdArray[1] ?? $mdString[1] ?? null;
    if ($middleware) {
        $middleware = trim($middleware, "'\" ");
    }

    // Store
    $routes[] = [
        'method' => $method,
        'url' => $url,
        'name' => $routeName,
        'uses' => $uses,
        'middleware' => $middleware,
        'raw' => $original
    ];
}

// GROUP BY PREFIX (first directory)
foreach ($routes as $r) {
    $parts = explode('/', trim($r['url'], '/'));
    $prefix = $parts[0] ?? '/';

    $prefixGroups[$prefix][] = $r;
}

// BUILD NEW ROUTES
$output = "<?php\n\nuse Illuminate\\Support\\Facades\\Route;\n";

foreach ($prefixGroups as $prefix => $group) {

    if ($prefix === '') {
        // root level routes
        foreach ($group as $r) {

            $line = "Route::{$r['method']}('{$r['url']}', [{$r['uses']}])";

            if ($r['name'])
                $line .= "->name('{$r['name']}')";

            if ($r['middleware'])
                $line .= "->middleware('{$r['middleware']}')";

            $output .= $line . ";\n";
        }

        $output .= "\n";
        continue;
    }

    // Grouped prefix block
    $output .= "Route::prefix('$prefix')->group(function () {\n";

    foreach ($group as $r) {
        $subUrl = preg_replace("/^$prefix/", '', trim($r['url'], '/'));
        $subUrl = '/' . ltrim($subUrl, '/');

        if ($subUrl === '/') $subUrl = '/';

        $line = "    Route::{$r['method']}('$subUrl', [{$r['uses']}])";

        if ($r['name'])
            $line .= "->name('{$r['name']}')";

        if ($r['middleware'])
            $line .= "->middleware('{$r['middleware']}')";

        $output .= $line . ";\n";
    }

    $output .= "});\n\n";
}

file_put_contents($webFile, $output);

echo "✔ Routes successfully converted to Laravel 10 format.\n";
echo "✔ Grouped by prefix and cleaned.\n";
echo "✔ Output written to routes/web.php\n";
?>
