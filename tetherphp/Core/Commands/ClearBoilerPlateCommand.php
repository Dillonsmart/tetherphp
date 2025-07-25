<?php

namespace TetherPHP\Core\Commands;

class ClearBoilerPlateCommand extends Command
{

    public string $command = 'boilerplate:clear';

    public string $description = 'Clears all the boilerplate files from the project';

    protected array $arguments = []; // listed in the order they are defined

    protected array $protectedFiles = [
        'Action.php',
        'Responder.php',
        'Domain.php',
    ];

    public function execute(): int
    {
        try {
            $filesToDelete = glob(app_dir() . '/**/*.php', GLOB_BRACE);

            foreach ($filesToDelete as $file) {
                if (is_file($file)) {
                    $fileName = basename($file);
                    // Skip protected files
                    if (in_array($fileName, $this->protectedFiles)) {
                        echo "Skipping protected file: " . $file . "\n";
                        continue;
                    }
                    unlink($file);
                    echo "Deleted: " . $file . "\n";
                } else {
                    echo "Skipping non-file: " . $file . "\n";
                }
            }

            $this->clearRoutes();

            $this->success("Boilerplate files cleared successfully.\n");
            return self::COMMAND_SUCCESS;

        } catch (\Exception $e) {
            $this->error("Error clearing boilerplate files: " . $e->getMessage() . "\n");
            return self::COMMAND_ERROR;
        }
    }

    private function clearRoutes(): void
    {
        $routesFile = project_root() . '/routes/web.php';
        if (file_exists($routesFile)) {
            file_put_contents($routesFile, "<?php\n\n// Routes cleared by ClearBoilerPlateCommand\n");
            echo "Cleared routes file: " . $routesFile . "\n";
        } else {
            echo "Routes file does not exist: " . $routesFile . "\n";
        }
    }
}