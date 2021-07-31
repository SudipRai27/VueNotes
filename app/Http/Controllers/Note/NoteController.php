<?php

namespace App\Http\Controllers\Note;

use App\Models\Note;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\NoteResource;

class NoteController extends Controller
{
    public function index(Request $request)
    {
        $sortField = request('sortBy', 'created_at');
        if (!in_array($sortField, ['id', 'title', 'body', 'created_at'])) {
            $sortField = 'created_at';
        }

        $sortDirection = request('sortDirection', 'desc');
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'desc';
        }

        $perPage = request('perPage', 10);

        $notes = Note::when(request('search'), function ($query) {
            $query->where(function ($q) {
                $q->where('title', 'LIKE', '%' . request('search') . '%')
                    ->orWhere('body', 'LIKE', '%' . request('search') . '%')
                    ->orWhere('id', 'LIKE', '%' . request('search') . '%')
                    ->orWhere('created_at', 'LIKE', '%' . request('search') . '%');
            });
        })->where('user_id', $request->user()->id)
            ->orderBy($sortField, $sortDirection)
            ->paginate($perPage);

        return NoteResource::collection($notes)->additional(['message' => 'Note Details with pagination'])
            ->response()
            ->setStatusCode(200);
    }

    public function destroy($note)
    {
        $noteIds = explode(",", $note);
        Note::destroy($noteIds);
        return response()->json(['message' => 'Deleted Successfully']);
    }
}