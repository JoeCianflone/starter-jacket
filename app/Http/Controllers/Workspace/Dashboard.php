<?php declare(strict_types=1);

namespace App\Http\Controllers\Workspace;

//region Use-Statements...
use App\Domain\Event\EventRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

//endregion Use-Statements...

class Dashboard extends Controller
{
    protected string $viewPath = 'Pages/Workspace';

    public function __construct()
    {
    }

    public function __invoke(Request $request): \Inertia\Response
    {
        return $this->view('Dashboard', [
            'events' => [],
            'messages' => [],
            'users' => [],
        ]);
    }
}
