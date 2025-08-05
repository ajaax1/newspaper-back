<?php

namespace App\Services;

use App\Models\IndustrialGuide;

class IndustrialGuideService
{
    public function getAll($search)
    {
        $query = IndustrialGuide::query();

        if (!empty($search)) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        return $query->paginate(10);
    }

    public function findById(int $id)
    {
        return IndustrialGuide::find($id);
    }

    public function create(array $data)
    {
        return IndustrialGuide::create($data);
    }

    public function update(IndustrialGuide $industrialGuide, array $data)
    {
        $industrialGuide->update($data);
        return $industrialGuide;
    }

    public function delete(IndustrialGuide $industrialGuide)
    {
        return $industrialGuide->delete();
    }
}
