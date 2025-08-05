<?php

namespace App\Services;

use App\Models\BusinessColumn;

class BusinessColumnService
{
    public function getAll($search)
    {
        $query = BusinessColumn::query();
        if (!empty($search)) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        return $query->paginate(10);
    }


    public function findById(int $id)
    {
        return BusinessColumn::find($id);
    }

    public function create(array $data)
    {
        return BusinessColumn::create($data);
    }

    public function update(BusinessColumn $businessColumn, array $data)
    {
        $businessColumn->update($data);
        return $businessColumn;
    }

    public function delete(BusinessColumn $businessColumn)
    {
        return $businessColumn->delete();
    }
}
