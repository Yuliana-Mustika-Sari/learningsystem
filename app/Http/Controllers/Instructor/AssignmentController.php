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

    public function create(Request $request)
    {
        // Try to get the course id from the query string (index view passes it as a query parameter)
        $courseId = $request->query('course') ?? $request->query('course_id');

        // Fallback: if no query param, we won't be able to load a course
        if (! $courseId) {
            abort(404, 'Course not specified.');
        }

        $course = Course::findOrFail($courseId);

        return view('instructor.assignments.create', compact('course'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date',
            'max_score' => 'required|integer|min:1|max:100',
        ]);

        // Get course_id from the submitted form (hidden input in the create view)
        $courseId = $request->input('course_id');
        if (! $courseId) {
            return back()->withInput()->withErrors(['course_id' => 'Course is required.']);
        }

        // Ensure the course exists and belongs to the instructor (optional security check)
        $course = Course::findOrFail($courseId);

        Assignment::create([
            'course_id' => $course->id,
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'max_score' => $request->max_score,
        ]);

        // Redirect back to assignments index (no course id parameter expected)
        return redirect()->route('instructor.assignments.index')
            ->with('success', 'Assignment created successfully.');
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
