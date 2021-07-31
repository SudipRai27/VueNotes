<?php

namespace App\Http\Controllers\Api\Datatable;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Builder;

abstract class DatatableController extends Controller
{
    protected $builder;

    protected $allowCreation = true;

    abstract public function builder();

    public function __construct()
    {
        $builder = $this->builder();

        if (!$builder instanceof Builder) {
            throw new Exception('Entity builder not instance of builder');
        }

        $this->builder = $builder;
    }

    public function index()
    {
        return response()->json([
            'data' => [
                'table' => $this->builder->getModel()->getTable(),
                'displayable' => array_values($this->getDisplayableColumns()),
                'updateable' => array_values($this->getUpdateableColumns()),
                'records' => $this->getRecords(),
                'allow' => [
                    'creation' => $this->allowCreation
                ],
            ]
        ]);
    }

    public function getDisplayableColumns()
    {
        return array_diff($this->getDatabaseColumnNames(), $this->builder->getModel()->getHidden());
    }

    public function getUpdateableColumns()
    {
        return array_diff($this->getDatabaseColumnNames(), $this->getDisplayableColumns());
    }

    public function getDatabaseColumnNames()
    {
        return Schema::getColumnListing($this->builder->getModel()->getTable());
    }

    public function getRecords()
    {
        if (request()->user() && method_exists($this, 'belongsToUserKeyColumn')) {
            $this->builder = $this->builder
                ->where($this->belongsToUserKeyColumn(), request()->user()->id);
        }
        if (request()->has('limit')) {
            $this->builder = $this->builder->limit(request()->limit);
        }

        return $this->builder->orderBy('id', 'asc')->get($this->getDisplayableColumns());
    }

    public function update(Request $request, $id)
    {
        $record = $this->builder->findOrFail($id);
        $record->update($request->only($this->getUpdateableColumns()));
        return response()->json([
            'data' => tap($record)
        ], 200);
    }

    public function store(Request $request)
    {
        if (!$this->allowCreation) {
            return false;
        }

        return $this->builder->create($request->only($this->getUpdateableColumns()));
    }
}