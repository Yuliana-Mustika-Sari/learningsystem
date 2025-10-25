<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Discussion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class DiscussionController extends Controller
{
    // Tampilkan semua diskusi dalam 1 course
    public function index(Course $course)
    {
        $discussions = Discussion::where('course_id', $course->id)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        // view directory uses singular "discussion" in views/courses/discussion/index.blade.php
        return view('courses.discussion.index', compact('course', 'discussions'));
    }

    // Simpan postingan baru
    public function store(Request $request, Course $course)
    {
        $request->validate([
            'content' => 'required|string|max:2000',
            // parent_post_id should reference the primary key (id) of discussions
            'parent_post_id' => 'nullable|integer|exists:discussions,id',
        ]);

            Discussion::create([
                'course_id' => $course->id,
                'user_id' => Auth::id(),
                'content' => $request->input('content'),
                'parent_post_id' => $request->parent_post_id, // null kalau post utama
                'created_at' => now(),
            ]);

        return redirect()->route('courses.discussions.index', $course)
            ->with('success', 'Post added successfully.');
    }

    // Hapus diskusi
    public function destroy(Course $course, Discussion $discussion)
    {
        $user = Auth::user();

            if (! Gate::forUser($user)->allows('discussion_management') && $user->id !== $discussion->user_id) {
            abort(403, 'Unauthorized.');
        }

        $discussion->delete();

        return back()->with('success', 'Discussion deleted.');
    }

    // List all discussions across courses (for navbar/global index)
    public function all()
    {
        $discussions = Discussion::with(['user', 'course'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('discussions.index', compact('discussions'));
    }
}
