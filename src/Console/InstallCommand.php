<?php

declare(strict_types=1);

namespace Revolution\Vercel\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vercel:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the Vercel resources';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        File::ensureDirectoryExists(base_path('api'));
        File::copy(__DIR__.'/../../stubs/api/index.php', base_path('api/index.php'));
        File::copy(__DIR__.'/../../stubs/.vercelignore', base_path('.vercelignore'));
        File::copy(__DIR__.'/../../stubs/vercel.json', base_path('vercel.json'));

        $this->info('Vercel resources installed successfully.');

        return 0;
    }
}
