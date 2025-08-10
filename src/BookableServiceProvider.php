<?php

namespace YottaHQ\Bookable;

use YottaHQ\Bookable\Support\AvailabilityStrategyResolver;
use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Support\ServiceProvider;

class BookableServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/bookable.php', 'bookable'
        );
    }

    public function boot(): void
    {
        $this->publishConfig();
        $this->publishMigrations();

        AboutCommand::add('yottahq/bookable', fn () => [
            'Description' => 'A Laravel package for managing bookable resources and their availability.',
            'Author' => 'YottaHQ Team',
            'Repository' => 'https://github.com/yottahq/bookable',
            'License' => 'MIT',
        ]);
    }

    private function publishConfig(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/bookable.php' => config_path('bookable.php'),
            ], 'bookable-config');
        }
    }

    private function publishMigrations(): void
    {
        // copy the migrations to the database/migrations directory iwth timestamp
        if ($this->app->runningInConsole()) {
            foreach (glob(__DIR__.'/../database/migrations/*.php') as $file) {
                $filename = basename($file);
                $timestamp = date('Y_m_d_His', filemtime($file));
                $newFileName = database_path('migrations/'.$timestamp.'_'.$filename);
                if (!file_exists($newFileName)) {
                    copy($file, $newFileName);
                }
            }
        }
    }
}
