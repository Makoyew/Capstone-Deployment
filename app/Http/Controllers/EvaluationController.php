<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Department;
use Illuminate\Support\Facades\Redirect;

class EvaluationController extends Controller
{
    public function index(Request $request)
    {
    $currentUser = auth()->user();

    $departments = Department::all();

    $selectedDepartment = $request->input('department_evaluation');

    $usersQuery = User::query();

    if ($selectedDepartment) {
        $usersQuery->where('department_id', $selectedDepartment);
    }

    $usersQuery->whereHas('evaluations');

    $users = $usersQuery->paginate(5);

    return view('evaluations.index', compact('users', 'departments', 'selectedDepartment'));
    }




    public function view($userId)
    {
    $user = User::findOrFail($userId);
    $evaluationData = Evaluation::where('user_id', $userId)->first();

    $evaluator = null;

    if ($evaluationData) {
        $evaluator = User::find($evaluationData->evaluator_id);
    }

    return view('evaluations.view', compact('user', 'evaluationData', 'evaluator'));
    }


    public function showForm($user_id)
    {
        $user = User::findOrFail($user_id);

        if ($user->hasEvaluated(auth()->user())) {
            return redirect()->route('evaluations.index')->with('error', 'You have already evaluated this employee.');
        }

        return view('evaluations.form', compact('user'));
    }

    public function submitEvaluation(Request $request)
    {
    try {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'rating_1a' => 'required|integer|between:1,5',
            'rating_2a' => 'required|integer|between:1,5',
            'rating_3a' => 'required|integer|between:1,5',
            'rating_4a' => 'required|integer|between:1,5',
            'rating_5a' => 'required|integer|between:1,5',
            'rating_6a' => 'required|integer|between:1,5',
            'rating_7a' => 'required|integer|between:1,5',
            'rating_8a' => 'required|integer|between:1,5',
            'rating_9a' => 'required|integer|between:1,5',
            'rating_10a' => 'required|integer|between:1,5',
            'rating_1b' => 'required|integer|between:1,5',
            'rating_2b' => 'required|integer|between:1,5',
            'rating_3b' => 'required|integer|between:1,5',
            'rating_4b' => 'required|integer|between:1,5',
            'rating_5b' => 'required|integer|between:1,5',
            'rating_6b' => 'required|integer|between:1,5',
            'rating_7b' => 'required|integer|between:1,5',
            'rating_8b' => 'required|integer|between:1,5',
            'rating_9b' => 'required|integer|between:1,5',
            'rating_10b' => 'required|integer|between:1,5',
            'rating_1c' => 'required|integer|between:1,5',
            'rating_2c' => 'required|integer|between:1,5',
            'rating_3c' => 'required|integer|between:1,5',
            'rating_4c' => 'required|integer|between:1,5',
            'rating_5c' => 'required|integer|between:1,5',
            'rating_6c' => 'required|integer|between:1,5',
            'rating_7c' => 'required|integer|between:1,5',
            'rating_8c' => 'required|integer|between:1,5',
            'rating_9c' => 'required|integer|between:1,5',
            'rating_10c' => 'required|integer|between:1,5',
            'rating_1d' => 'required|integer|between:1,5',
            'rating_2d' => 'required|integer|between:1,5',
            'rating_3d' => 'required|integer|between:1,5',
            'rating_4d' => 'required|integer|between:1,5',
            'rating_5d' => 'required|integer|between:1,5',
            'rating_6d' => 'required|integer|between:1,5',
            'rating_7d' => 'required|integer|between:1,5',
            'rating_8d' => 'required|integer|between:1,5',
            'rating_9d' => 'required|integer|between:1,5',
            'rating_10d' => 'required|integer|between:1,5',
            'comments_a' => 'nullable|string',
            'comments_b' => 'nullable|string',
            'comments_c' => 'nullable|string',
            'comments_d' => 'nullable|string',
        ]);

        $validatedData['evaluator_id'] = auth()->user()->id;

        $existingEvaluation = Evaluation::where('evaluator_id', auth()->user()->id)
            ->where('user_id', $validatedData['user_id'])
            ->first();

        if ($existingEvaluation) {
            return redirect()->back()->with('error', 'You have already evaluated this user.');
        }



    $evaluation = new Evaluation([
        'evaluator_id' => auth()->user()->id,
        'user_id' => $validatedData['user_id'],
        'rating_1a' => $validatedData['rating_1a'],
        'rating_2a' => $validatedData['rating_2a'],
        'rating_3a' => $validatedData['rating_3a'],
        'rating_4a' => $validatedData['rating_4a'],
        'rating_5a' => $validatedData['rating_5a'],
        'rating_6a' => $validatedData['rating_6a'],
        'rating_7a' => $validatedData['rating_7a'],
        'rating_8a' => $validatedData['rating_8a'],
        'rating_9a' => $validatedData['rating_9a'],
        'rating_10a' => $validatedData['rating_10a'],
        'rating_1b' => $validatedData['rating_1b'],
        'rating_2b' => $validatedData['rating_2b'],
        'rating_3b' => $validatedData['rating_3b'],
        'rating_4b' => $validatedData['rating_4b'],
        'rating_5b' => $validatedData['rating_5b'],
        'rating_6b' => $validatedData['rating_6b'],
        'rating_7b' => $validatedData['rating_7b'],
        'rating_8b' => $validatedData['rating_8b'],
        'rating_9b' => $validatedData['rating_9b'],
        'rating_10b' => $validatedData['rating_10b'],
        'rating_1c' => $validatedData['rating_1c'],
        'rating_2c' => $validatedData['rating_2c'],
        'rating_3c' => $validatedData['rating_3c'],
        'rating_4c' => $validatedData['rating_4c'],
        'rating_5c' => $validatedData['rating_5c'],
        'rating_6c' => $validatedData['rating_6c'],
        'rating_7c' => $validatedData['rating_7c'],
        'rating_8c' => $validatedData['rating_8c'],
        'rating_9c' => $validatedData['rating_9c'],
        'rating_10c' => $validatedData['rating_10c'],
        'rating_1d' => $validatedData['rating_1d'],
        'rating_2d' => $validatedData['rating_2d'],
        'rating_3d' => $validatedData['rating_3d'],
        'rating_4d' => $validatedData['rating_4d'],
        'rating_5d' => $validatedData['rating_5d'],
        'rating_6d' => $validatedData['rating_6d'],
        'rating_7d' => $validatedData['rating_7d'],
        'rating_8d' => $validatedData['rating_8d'],
        'rating_9d' => $validatedData['rating_9d'],
        'rating_10d' => $validatedData['rating_10d'],
        'comments_a' => $validatedData['comments_a'],
        'comments_b' => $validatedData['comments_b'],
        'comments_c' => $validatedData['comments_c'],
        'comments_d' => $validatedData['comments_d'],
        'submitted_at' => now(),
    ]);

        $overallRating = $this->calculateOverallRating($validatedData);
        $evaluation->overall_rating = $overallRating;


        $evaluation->save();

        return redirect()->route('evaluations.index')->with('success', 'Evaluation submitted successfully.');
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Error submitting evaluation: ' . $e->getMessage());
            }
        }

        private function calculateOverallRating($data)
    {
        $ratings = array_values($data);

        $sumOfRatings = array_sum($ratings);

        $totalRatings = 40;
        $overallRating = $totalRatings > 0 ? $sumOfRatings / $totalRatings : 0;

        return $overallRating;
    }
}

