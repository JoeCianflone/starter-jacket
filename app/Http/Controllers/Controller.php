<?php declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected string $viewPath = '';

    /**
     * A controller helper function that will send me to the right View file.
     *
     * @param string $name
     * @param array $props
     * @param array $viewData
     * @return \Inertia\Response
     */
    public function view(string $name, array $props = [], array $viewData = []): \Inertia\Response
    {
        $name = "{$this->viewPath}/{$name}";

        return inertia($name, $props)->withViewData($viewData);
    }
}
