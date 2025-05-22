<?php

namespace App\Http\Controllers\allReviews;

use App\Http\Controllers\Controller;
use App\Models\evaluationTable;
use Illuminate\Support\Facades\DB;


class EvaluationController extends Controller
{

    public function showEvaluationReview($emp_id)
    {
        $table = 'evaluation_tables'; // Your table name

        // Define database columns and their corresponding frontend labels
        $columnMappings = [
            'designation' => 'Designation',
            'salary_grade' => 'Salary Grade',
            'employee_name' => 'Employee Name',
            'emp_id' => 'Employee ID',
            'department' => 'Department',
            'evaluation_purpose' => 'Evaluation Purpose',
            'division' => 'Division',
            'manager_name' => 'Manager Name',
            'joining_date' => 'Joining Date',
            'review_period' => 'Review Period',
            'accuracy_neatness' => 'Accuracy, Neatness & Timeliness',
            'comments_accuracy' => 'Justification (Accuracy)',
            'adherence' => 'Adherence to Duties',
            'comments_adherence' => 'Justification (Adherence)',
            'synchronization' => 'Synchronization with Goals',
            'comments_synchronization' => 'Justification (Synchronization)',
            'qualityworktotalrating' => 'Quality of Work Rating',
            'punctuality' => 'Punctuality',
            'comments_punctuality' => 'Justification (Punctuality)',
            'attendance' => 'Attendance',
            'comments_attendance' => 'Justification (Attendance)',
            'initiatives_at_workplace' => 'Initiatives at Workplace',
            'comments_initiatives' => 'Justification (Initiatives)',
            'submits_reports' => 'Submits Reports on Time',
            'comments_submits_reports' => 'Justification (Reports)',
            'work_habits_rating' => 'Work Habits Rating',
            'skill_ability' => 'Skill & Ability',
            'comments_skill_ability' => 'Justification (Skill)',
            'learning_improving' => 'Learning & Improving',
            'comments_learning_improving' => 'Justification (Learning)',
            'problem_solving_ability' => 'Problem Solving Ability',
            'comments_problem_solving' => 'Justification (Problem Solving)',
            'jk_total_rating' => 'Job Knowledge Rating',
            'total_scoring_system' => 'Total Scoring',
            'recomendation' => 'Recommendations',
            'evalutors_name' => 'Evaluator’s Name',
            'evaluator_signatur' => 'Evaluator Signature',
            'evaluator_signatur_date' => 'Evaluator Signature Date',
            'director_name' => 'Director’s Name',
            'director_signatur' => 'Director Signature',
            'director_signatur_date' => 'Director Signature Date',
        ];

        // var_dump($columnMappings);exit;

        // print_r($columnMappings);


        // dd($columnMappings);


        // Fetch data from database
        // $users = DB::table($table)->get();
        $users = DB::table($table)->where('emp_id',  $emp_id)->first();
        if (!$users) {
            return redirect()->back()->with('error', 'No review found for this employee.');
        }

        return view('review/evaluationDetails', compact('columnMappings', 'users','emp_id'));
    }

  



}
