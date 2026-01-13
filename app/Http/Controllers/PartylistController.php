<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\partylist;

class PartylistController extends Controller
{
    /**
     * Display the specified partylist and its candidates.
     */
    public function show($id)
    {
        $partylist = partylist::with(['applied_candidacies.students', 'applied_candidacies.position'])->find($id);

        if (! $partylist) {
            abort(404);
        }

        // Sort candidacies by position name then student last name for display
        $candidacies = $partylist->applied_candidacies->sortBy(function($c) {
            $pos = $c->position ? $c->position->position_name : '';
            $student = $c->students ? ($c->students->last_name ?? '') : '';
            return $pos . '|' . $student;
        });

        return view('partylist.show', compact('partylist', 'candidacies'));
    }
}
