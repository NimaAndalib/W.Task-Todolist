<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Note;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{
    public function show()
    {
        $note = Auth::user()->note;

        return response()->json([
            'id' => $note->id ?? null,
            'text' => $note->text ?? '',
            'updated_at' => $note->updated_at ?? null,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate(['text' => 'nullable|string']);

        $user = Auth::user();
        $note = $user->note ?? new Note();
        $note->user_id = $user->id;
        $note->text = $request->text;
        $note->save();

        return response()->json([
            'id' => $note->id,
            'text' => $note->text,
            'updated_at' => $note->updated_at,
        ]);
    }

    public function destroy()
    {
        $note = Auth::user()->note;
        if($note) $note->delete();

        return response()->json(['deleted' => true]);
    }
}
