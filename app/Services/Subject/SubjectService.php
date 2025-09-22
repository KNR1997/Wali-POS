<?php

namespace App\Services\Subject;

use App\Http\Requests\UpdateSubjectRequest;
use App\Http\Resources\SubjectResource;
use App\Models\Subject;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use App\Traits\Filterable;
use Illuminate\Http\Request;

class SubjectService
{
    /**
     * Get a single resource from the database
     *
     *
     * @return SubjectResource
     */
    public function get(Subject $subject)
    {
        return new SubjectResource($subject);
    }

    /**
     * Get resource index from the database
     *
     * @param  $query
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index($data)
    {
        $query = Subject::query();
        if (! empty($data['search'])) {
            $query = $query->search($data['search']);
        }
        if (! empty($data['filters'])) {
            $this->filter($query, $data['filters']);
        }
        if (! empty($data['sort_by']) && ! empty($data['sort'])) {
            $query = $query->orderBy($data['sort_by'], $data['sort']);
        }

        return SubjectResource::collection($query->paginate(10));
    }

    /**
     * Creates resource in the database
     *
     *
     * @return Builder|Model|null
     *
     */
    public function create(array $data)
    {
        return Subject::create($data);
    }

    /**
     * Updates resource in the database
     *
     *
     * @return bool
     *
     */
    public function update(Subject $subject, array $data)
    {
        $data = $this->clean($data);

        return $subject->update($data);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subject $subject)
    {
        return $subject->delete();
    }

    /**
     * Clean the data
     *
     *
     * @return array
     */
    private function clean(array $data)
    {
        foreach ($data as $i => $row) {
            if ($row === 'null') {
                $data[$i] = null;
            }
        }

        return $data;
    }

    /**
     * Filter resources
     *
     * @return void
     */
    private function filter(Builder &$query, $filters)
    {
        $query->filter(Arr::except($filters, ['role']));

        if (! empty($filters['role'])) {
            $roleFilter = Filterable::parseFilter($filters['role']);
            if (! empty($roleFilter)) {
                if (is_array($roleFilter[2])) {
                    $query->whereIs(...$roleFilter[2]);
                } else {
                    $query->whereIs($roleFilter[2]);
                }
            }
        }

    }
}
