<?php

declare(strict_types=1);

$root = dirname(__DIR__);

if (! function_exists('env')) {
    function env(string $key, mixed $default = null): mixed
    {
        $value = $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key);

        return $value === false || $value === null ? $default : $value;
    }
}

$registry = include $root.'/config/modules.php';

$studly = static function (string $key): string {
    return str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $key)));
};

$count = 0;

foreach ($registry['registry'] as $mod) {
    $key = $mod['key'];
    $name = $studly($key);
    $dir = $root.'/app/Modules/'.$name;

    foreach (['routes', 'Services', 'tests'] as $sub) {
        if (! is_dir($dir.'/'.$sub)) {
            mkdir($dir.'/'.$sub, 0777, true);
        }
    }

    $class = $name.'Module';
    $file = $dir.'/'.$class.'.php';

    if (! is_file($file)) {
        $php = <<<PHP
<?php

declare(strict_types=1);

namespace App\\Modules\\{$name};

use App\\Modules\\AbstractModule;

final class {$class} extends AbstractModule
{
    public function key(): string
    {
        return '{$key}';
    }
}

PHP;
        file_put_contents($file, $php);
    }

    $settingsSchema = $mod['settings_schema'] ?? [];
    file_put_contents(
        $dir.'/settings.php',
        "<?php\n\ndeclare(strict_types=1);\n\n/**\n * Settings schema for module [{$key}].\n */\nreturn ".var_export($settingsSchema, true).";\n"
    );

    $deps = empty($mod['dependencies'])
        ? "- None\n"
        : implode("\n", array_map(static fn (string $d): string => "- `{$d}`", $mod['dependencies']))."\n";

    file_put_contents(
        $dir.'/README.md',
        "# {$mod['name']}\n\n"
        ."**Key:** `{$key}`  \n"
        ."**Category:** {$mod['category']}  \n"
        ."**Tier:** {$mod['pricing_tier']}\n\n"
        ."{$mod['description']}\n\n"
        ."## Dependencies\n\n{$deps}\n"
        ."## Enable\n\n"
        ."Business Admin → Module Marketplace → toggle **{$mod['name']}**, or apply an industry preset during onboarding.\n"
    );

    $count++;
}

echo "Scaffolded {$count} modules under app/Modules\n";
