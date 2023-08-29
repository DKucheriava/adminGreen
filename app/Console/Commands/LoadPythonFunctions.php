<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class LoadPythonFunctions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'python:load-functions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command executes the Python script, loads the functions, and stores them in the cache.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $pythonFilePath = base_path('python_scripts/functions.py');
        $lastModifiedTime = filemtime($pythonFilePath);
        $cachedTimestamp = Cache::get('python_functions_timestamp');

        if ($lastModifiedTime !== $cachedTimestamp) {
            // Python file has changed, re-run the script and cache functions
            $functionsOutput = shell_exec('python3 ' . $pythonFilePath);
            $parsedFunctions = json_decode($functionsOutput, true);

            Cache::put('python_functions', $parsedFunctions, 60 * 24); // Cache for 24 hours
            Cache::put('python_functions_timestamp', $lastModifiedTime);
        }

        $this->info('Python functions loaded and cached.');
    }
}
