<?php declare(strict_types=1);

namespace App\Http\Middleware;

use App\Domain\Workspace\WorkspaceRepository;
use App\Http\Middleware\BaseMiddleware;
use App\Support\DatabaseHelper;
use App\Support\HostHelper;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ParseSecondaryConnection extends BaseMiddleware
{
    protected array $except = [
        'storage',
        'horizon',
        'register',
        '_debugbar',
        'stripe',
        'search',
    ];

    public function __construct(private WorkspaceRepository $workspaceRepository)
    {
    }

    public function handle(Request $request, Closure $next): mixed
    {
        $host = new HostHelper(
            host: $request->getHost(),
            path: $request->getPathInfo(),
        );

        if ($this->ignore($request->path()) || $host->isOnOurDomain()) {
            return $next($request);
        }

        try {
            DatabaseHelper::disconnect();

            $workspace = match ($host->getSub()) {
                // config('paths.subdomains.auth') => $this->connectFromAuthSubdomain($request),
                    // config('paths.subdomains.admin') => Lookup::byCustomerID($host->getPath()[0])->first(),
                    default => $this->connectFromWorkspace($host)
            };

            $request->merge([
                'customer_id' => $workspace->customer_id,
                'workspace_id' => $workspace->id,
                'sub' => $workspace->sub,
                'domain' => $host->getDomain(),
            ]);

            DatabaseHelper::connect($workspace->id);
        } catch (Exception $e) {
            return redirect()->route('search.index')->with('message', 'We cannot find that website, maybe you should try searching for it');
        }

        return $next($request);
    }

    // private function connectFromAuthSubdomain($request)
    // {
    //     $lookup = null;
    //     if ($request->query('customer_id') !== 'null' && !is_null($request->query('customer_id'))) {
    //         $lookup = Lookup::byCustomerID($request->query('customer_id'))->first();
    //     }

    //     if ($request->query('to') !== 'null' && !is_null($request->query('to'))) {
    //         $lookup = Cache::remember('lookup-sub', 300, fn () => Lookup::bySub($request->query('to'))->first());
    //     }

    //     return $lookup;
    // }

    private function connectFromWorkspace($host)
    {
        return match ($host->isCustomDomain()) {
            true => $this->workspaceRepository->getByDomain($host->getDomain()),
            false => $this->workspaceRepository->getBySubdomain($host->getSub()),
        };
    }
}
