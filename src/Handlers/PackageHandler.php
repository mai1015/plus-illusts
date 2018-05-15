<?php

declare(strict_types=1);

namespace Mai1015\PlusIllusts\Handlers;

use Illuminate\Console\Command;

class PackageHandler extends \Zhiyi\Plus\Support\PackageHandler
{
    /**
     * Publish public asstes source handle.
     *
     * @param \Illuminate\Console\Command $command
     * @return mixed
     */
    public function publishAsstesHandle(Command $command)
    {
        $force = $command->confirm('Overwrite any existing files');

        return $command->call('vendor:publish', [
            '--provider' => \Mai1015\PlusIllusts\Providers\AppServiceProvider::class,
            '--tag' => 'plus-illusts-public',
            '--force' => boolval($force),
        ]);
    }

    /**
     * Publish package config source handle.
     *
     * @param \Illuminate\Console\Command $command
     * @return mixed
     */
    public function publishConfigHandle(Command $command)
    {
        $force = $command->confirm('Overwrite any existing files');

        return $command->call('vendor:publish', [
            '--provider' => \Mai1015\PlusIllusts\Providers\AppServiceProvider::class,
            '--tag' => 'plus-illusts-config',
            '--force' => boolval($force),
        ]);
    }

    /**
     * Publish package resource handle.
     *
     * @param \Illuminate\Console\Command $command
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function publishHandle(Command $command)
    {
        return $command->call('vendor:publish', [
            '--provider' => \Mai1015\PlusIllusts\Providers\AppServiceProvider::class,
        ]);
    }

    /**
     * The migrate handle.
     *
     * @param \Illuminate\Console\Command $command
     * @return mixed
     */
    public function migrateHandle(Command $command)
    {
        return $command->call('migrate');
    }

    /**
     * The DB seeder handler.
     *
     * @param \Illuminate\Console\Command $command
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function dbSeedHandle(Command $command)
    {
        return $command->call('db:seed', [
            '--class' => \Mai1015\PlusIllusts\Seeds\DatabaseSeeder::class,
        ]);
    }
}
