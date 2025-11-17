<?php

namespace Modules\Core\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;

interface BaseRepositoryInterface
{
    public function all();
    public function find($id);
    public function findOrFail($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function paginate($perPage = 15);
    public function where($column, $operator = null, $value = null);
    public function count();
}
