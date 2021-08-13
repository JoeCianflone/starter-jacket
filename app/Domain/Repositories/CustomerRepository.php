<?php declare(strict_types=1);

namespace App\Domain\Repositories;

use App\Domain\Models\Customer;
use App\Domain\Repositories\BaseRepository;

class CustomerRepository extends BaseRepository
{
    public function __construct(Customer $model)
    {
        parent::__construct(model: $model);
    }

    public function subscribe(Customer $customer, string $method, array $product, array $price, array $options)
    {
        $customer
            ->newSubscription($product['id'], $price['id'])
            ->create($method, $options);
    }
}
