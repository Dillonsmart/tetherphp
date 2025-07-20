<?php

namespace TetherPHP\Core\Commands;

class ClearBoilerPlateCommand extends Command
{

    public string $command = 'boilerplate:clear';

    public string $description = 'Clears all the boilerplate files from the project';

    protected array $arguments = []; // listed in the order they are defined

    protected array $options = [];

    public function execute(): int
    {
        try {

            $actions = glob (app_dir() . '/Actions/**/*.php');
            $domains = glob (app_dir() . '/Domains/**/*.php');
            $responders = glob (app_dir() . '/Responders/**/*.php');
            $views = glob (views_dir() . '/**/*.php');

            $filesToDelete = array_merge($actions, $domains, $responders, $views);

            foreach ($filesToDelete as $file) {
                if (is_file($file)) {
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