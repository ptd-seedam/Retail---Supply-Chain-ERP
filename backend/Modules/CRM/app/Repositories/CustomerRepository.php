<?php

namespace Modules\CRM\Repositories;

use Modules\Core\Repositories\BaseRepository;
use Modules\CRM\Models\Customer;

class CustomerRepository extends BaseRepository
{
    public function __construct(Customer $model)
    {
        parent::__construct($model);
    }

    public function getActive()
    {
        return $this->model->where('status', 'active')->get();
    }

    public function getByGroup($groupId)
    {
        return $this->model->where('group_id', $groupId)->get();
    }

    public function getByEmail($email)
    {
        return $this->model->where('email', $email)->first();
    }

    public function getByPhone($phone)
    {
        return $this->model->where('phone', $phone)->first();
    }

    public function getWithRelations()
    {
        return $this->model->with(['group', 'loyaltyPoints'])->get();
    }
}
