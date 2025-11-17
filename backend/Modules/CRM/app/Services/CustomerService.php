<?php

namespace Modules\CRM\Services;

use Modules\CRM\Repositories\CustomerRepository;

class CustomerService
{
    protected $repository;

    public function __construct(CustomerRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAllCustomers()
    {
        return $this->repository->all();
    }

    public function getActiveCustomers()
    {
        return $this->repository->getActive();
    }

    public function getCustomer($id)
    {
        return $this->repository->findOrFail($id);
    }

    public function getCustomersByGroup($groupId)
    {
        return $this->repository->getByGroup($groupId);
    }

    public function createCustomer(array $data)
    {
        return $this->repository->create($data);
    }

    public function updateCustomer($id, array $data)
    {
        return $this->repository->update($id, $data);
    }

    public function deleteCustomer($id)
    {
        return $this->repository->delete($id);
    }

    public function paginateCustomers($perPage = 15)
    {
        return $this->repository->paginate($perPage);
    }

    public function getCustomersWithRelations()
    {
        return $this->repository->getWithRelations();
    }

    public function updateBalance($customerId, $amount)
    {
        $customer = $this->getCustomer($customerId);
        $customer->update(['current_balance' => $customer->current_balance + $amount]);
        return $customer;
    }
}
