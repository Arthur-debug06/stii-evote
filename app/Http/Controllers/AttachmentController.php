<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\applied_candidacy;
use App\Models\students;

class AttachmentController extends Controller
{
    /**
     * Serve grade attachment for an applied candidacy.
     * Uses the public disk first, falls back to raw storage path if needed.
     */
    public function candidacyGrade($id)
    {
        $applied = applied_candidacy::findOrFail($id);

        $path = $applied->grade_attachment;
        if (empty($path)) {
            abort(404);
        }

        // Prefer public disk (storage/app/public)
        if (Storage::disk('public')->exists($path)) {
            $full = Storage::disk('public')->path($path);
            if (file_exists($full)) {
                return response()->file($full);
            }
        }

        // Fallback: check storage/app/<path>
        $full = storage_path('app/' . $path);
        if (file_exists($full)) {
            return response()->file($full);
        }

        abort(404);
    }

    /**
     * Serve student profile or id image by student id.
     * $type = 'profile' or 'id'
     */
    public function studentImage($studentId, $type)
    {
        $student = students::findOrFail($studentId);

        $path = null;
        if ($type === 'profile') {
            $path = $student->profile_image;
        } elseif ($type === 'id') {
            $path = $student->student_id_image;
        }elseif ($type === 'id_back') {
            $path = $student->student_id_image_back;
        }

        if (empty($path)) {
            abort(404);
        }

        if (Storage::disk('public')->exists($path)) {
            $full = Storage::disk('public')->path($path);
            if (file_exists($full)) {
                return response()->file($full);
            }
        }

        $full = storage_path('app/' . $path);
        if (file_exists($full)) {
            return response()->file($full);
        }

        abort(404);
    }
}
