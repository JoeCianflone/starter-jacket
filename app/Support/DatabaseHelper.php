<?php declare(strict_types=1);

namespace App\Support;

use App\Support\Actions\GetWorkspaceName;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class DatabaseHelper
{
    public static function exists(string $id): bool
    {
        $name = GetWorkspaceName::action($id);

        $result = DB::select("SHOW DATABASES LIKE '{$name}'");

        return ! empty($result);
    }

    public static function create(string $id): bool
    {
        $name = GetWorkspaceName::action($id);

        return DB::statement("CREATE DATABASE {$name};");
    }

    public static function drop(string $id): bool
    {
        $name = GetWorkspaceName::action($id);

        return DB::statement("DROP DATABASE {$name};");
    }

    public static function workspaces(string $prefix): ?Collection
    {
        return collect(DB::select("SHOW DATABASES LIKE '{$prefix}%'"))
                ->flatMap(fn ($item) => collect($item)->flatten())
                ->reject(fn ($item) => $item == config('paths.db.shared.name'))
                ->map(fn ($item) => str_replace($prefix, '', $item));
    }

    public static function connect(string $id): void
    {
        $name = GetWorkspaceName::action($id);

        self::connectionConfigure($name);

        app('queue')->createPayloadUsing(fn () => ['workspace_id ' => $name]);
        app('events')->listen(JobProcessing::class, function ($event): void {
            if (isset($event->job->payload()['workspace_id'])) {
                self::connectionConfigure($event->job->payload()['workspace_id']);
            }
        });
    }

    public static function disconnect(): void
    {
        DB::disconnect('workspace');
        DB::purge('workspace');
    }

    public static function connectionConfigure(string $name): void
    {
        config([
            'database.connections.workspace.database' => $name,
            'database.redis.default.database' => $name,
            'database.redis.cache.database' => $name.'_cache',
            'rebase.paths.db.workspace.name' => $name,
            'session.files' => storage_path('framework/sessions/'.$name),
            'cache.prefix' => $name,
            'filesystem.disks.local.root' => storage_path('app/'.$name),
        ]);

        app('cache')->forgetDriver(config('cache.default'));
    }
}
