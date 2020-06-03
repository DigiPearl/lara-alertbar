<?php

namespace DigiPearl\LaraAlertbar\Console;

use Illuminate\Console\Command;

class PublishCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature =laralertbar:publish {--force : Overwrite any existing files};
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish all of the laralertbar resources';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->call('vendor:publish', [
            '--tag' =>laralertbar-config',
            '--force' => $this->option('force'),
        ]);
        $this->call('vendor:publish', [
            '--tag' =>laralertbar-view',
            '--force' => $this->option('force'),
        ]);
        $this->call('vendor:publish', [
            '--tag' =>laralertbar-asset',
            '--force' => true,
        ]);
    }
}
