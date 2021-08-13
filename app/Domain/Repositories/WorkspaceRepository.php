<?php declare(strict_types=1);

namespace App\Domain\Repositories;

use App\Domain\Repositories\BaseRepository;
use App\Domain\Workspace\Workspace;
use Illuminate\Database\Eloquent\Collection;

class WorkspaceRepository extends BaseRepository
{
    public function __construct(Workspace $model)
    {
        parent::__construct(model: $model);
    }

    public function customerSubdomainExists(string $customerID, string $subdomain): bool
    {
        return $this->model
                    ->where('customer_id', $customerID)
                    ->where('sub', $subdomain)
                    ->exists();
    }

    public function getByCustomerID(string $customerID): Collection
    {
        return $this->model->where('customer_id', $customerID)->get();
    }

    public function countByCustomerID(string $customerID): int
    {
        return $this->model->where('customer_id', $customerID)->count();
    }

    public function getByWorkspaceID(string $workspaceID): Workspace
    {
        return $this->model->where('id', $workspaceID)->firstOrFail();
    }

    public function getBySubdomain(string $sub): Workspace
    {
        return $this->model->where('sub', $sub)->firstOrFail();
    }

    public function getByDomain(string $domain): Workspace
    {
        return $this->model->where('domain', $domain)->firstOrFail();
    }

    public function getWorkspaceTheme(string $workspaceID): string
    {
        return $this->model->where('id', $workspaceID)->firstOrFail()->theme;
    }
}
