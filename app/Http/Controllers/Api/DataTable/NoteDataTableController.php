<?php

namespace App\Http\Controllers\Api\Datatable;

use App\Models\Note;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Datatable\DatatableController;

class NoteDataTableController extends DatatableController
{
    protected $allowCreation = true;

    public function builder()
    {
        return Note::query();
    }

    public function belongsToUserKeyColumn()
    {
        return 'user_id';
    }

    public function getDisplayableColumns()
    {
        return [
            'id', 'title', 'body', 'created_at'
        ];
    }

    public function getUpdateableColumns()
    {
        return [
            'title', 'body'
        ];
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|max:255',
            'body' => 'required',

        ]);
        $record = $this->builder->findOrFail($id);
        $record->update($request->only($this->getUpdateableColumns()));
        return response()->json([
            'data' => tap($record)
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'body' => 'required',

        ]);
        $data = $request->user()
            ->notes()
            ->create($request->only($this->getUpdateableColumns()));

        return response()->json([
            'data' => $data
        ], 200);
    }
}