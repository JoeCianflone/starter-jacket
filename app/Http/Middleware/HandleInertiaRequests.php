<?php declare(strict_types=1);

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request)
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request)
    {
        return array_merge(parent::share($request), [
            'workspace_id' => $request->get('workspace_id'),
            'sub' => $request->get('sub'),
            'siteURL' => "https://{$request->get('sub')}.".config('app.domain'),
            'auth' => function (): array {
                return [
                    'user' => auth()->user() ? [
                        'id' => auth()->user()->id,
                        'name' => auth()->user()->name,
                        'email' => auth()->user()->email,
                        'roles' => auth()->user()->role,
                        // 'avatar' => auth()->user()->avatar,
                        // 'visibility' => RoleAccess::for(auth()->user()->role),
                    ] : null,
                ];
            },
            'app' => function (): array {
                return [
                    // 'roles' => Arr::except(MemberRoles::toArray(), ['SUPER', 'OWNER']),
                    // 'visibility' => Arr::except(Visibility::toArray(), ['SUPER']),
                    'name' => config('app.name'),
                    'domain' => config('app.domain'),
                    // 'products' => config('pricing.products'),
                ];
            },
            'flash' => function (): array {
                return [
                    'success' => session('success'),
                    'alert' => session('alert'),
                    'message' => session('message'),
                    'errors' => session('errors'),
                ];
            },
        ]);
    }
}
