<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssignmentController extends Controller
{
    public function index()
    {
        $instructor = Auth::user();
        $courses = Course::where('instructor_id', $instructor->id)->with('assignments')->get();

        return view('instructor.assignments.index', compact('courses'));
    }

    public function create(Course $course)
    {
        $instructor = Auth::user();
        $courses = Course::where('instructor_id', $instructor->id)->get();
        return view('instructor.assignments.create', compact('course'));
    }

    public function store(Request $request, Course $course)
    {
        if (!$course->exists) {
            return redirect()->route('instructor.assignments.index')->with('error', 'Course not found.');
        }
        if ($course->instructor_id !== Auth::id()) {
            return redirect()->route('instructor.assignments.index')->with('error', 'You do not have access to this course.');
        }
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date',
            'max_score' => 'required|integer|min:1|max:100',
        ]);

        Assignment::create([
            'course_id' => $course->id,
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'max_score' => $request->max_score,
        ]);
        return redirect()->route('instructor.assignments.index')->with('success', 'Assignment created successfully.');
    }

    public function edit(Assignment $assignment)
    {
        $course = $assignment->course;
        return view('instructor.assignments.edit', compact('assignment', 'course'));
    }

    public function update(Request $request, Assignment $assignment)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date',
            'max_score' => 'required|integer|min:1|max:100',
        ]);

        $assignment->update($request->only('title', 'description', 'due_date', 'max_score'));

        return redirect()->route('instructor.assignments.index')->with('success', 'Assignment updated successfully.');
    }

    public function destroy(Assignment $assignment)
    {
        $assignment->delete();

        return redirect()->route('instructor.assignments.index')->with('success', 'Assignment deleted successfully.');
    }
}
