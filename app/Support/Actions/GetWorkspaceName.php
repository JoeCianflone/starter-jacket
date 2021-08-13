<?php declare(strict_types=1);

namespace App\Support\Actions;

class GetWorkspaceName
{
    public static function action(string $id): string
    {
        return  config('paths.db.workspace.prefix').str_replace('-', '_', $id);
    }
}
