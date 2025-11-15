<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentRequest;
use App\Http\Resources\StudentResource;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Student::query();
        if ($request->filled('search')) {
            $query->whereAny(['name', 'class', 'section'], 'like', '%' . $request->search . '%');
        }
        if ($request->filled('class')) {
            $query->where('class', $request->class);
        }
        if ($request->filled('student_id')) {
            $query->where('student_id', $request->section);
        }
        return StudentResource::collection($query->latest()->paginate(request('per_page', 10)));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StudentRequest $request)
    {
        $student = Student::create($request->validated());

        return new StudentResource($student);
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        return new StudentResource($student);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StudentRequest $request, Student $student)
    {
        $student->update($request->validated());

        return new StudentResource($student);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        $student->delete();

        return response()->noContent();
    }
}
