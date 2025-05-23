@extends('layouts.app')

@section('title', 'HR Review Details')

@section('breadcrumb', "Employee {$employee_id} / View Evaluation")


@section('content')


    <div class="text-right mb-3">
        <a href="{{ url()->previous() }}" class="btn btn-secondary">Back</a>
    </div>

    <h2>Employee Evaluation Details: {{$employee_id}}</h2>

    <!-- Employee Evaluation History Table -->
    <table id="employeeEvaluationTable" class="display table table-striped table-bordered">
        <thead>
            <tr>
                <th>Field</th>
                <th>Rating / Comments</th>
            </tr>
        </thead>
        <tbody>
            @foreach($eval as $evaluation)
                <tr>
                    <td>Designation:</td>
                    <td>{{$evaluation->designation}}</td>
                </tr>
                <tr>
                    <td>Salary Grade/Band:</td>
                    <td>{{$evaluation->salary_grade}}</td>
                </tr>
                <tr>
                    <td>Name of Employee:</td>
                    <td>{{$evaluation->employee_name}}
                </tr>
                <tr>
                    <td>Employee Id:</td>
                    <td>{{$evaluation->emp_id}}
                </tr>

                 <tr>
                    <td>Division:</td>
                    <td>{{$evaluation->division}}
                </tr> 
                <tr>
                    <td>Manager Name::</td>
                    <td>{{$evaluation->manager_name}}
                </tr> 
                <tr>
                    <td>Joining Date:</td>
                    <td>{{$evaluation->joining_date}}
                </tr> 
                <tr>
                    <td>Evaluation Purpose:</td>
                    <td>{{$evaluation->evaluation_purpose}}
                </tr>
                 <tr>
                    <td>Review Period:</td>
                    <td>{{$evaluation->review_period}}
                </tr> 
                 

                <tr>
                    <td>1. Accuracy, neatness and timeliness of work</td>
                    <td>{{ $evaluation->accuracy_neatness }} / {{ $evaluation->comments_accuracy }}</td>
                </tr>
                <tr>
                    <td>2. Adherence to duties and procedures in Job Description and Work Instructions</td>
                    <td>{{ $evaluation->adherence }} / {{ $evaluation->comments_adherence }}</td>
                </tr>
                <tr>
                    <td>3. Synchronization with organizations/functional goals</td>
                    <td>{{ $evaluation->synchronization }} / {{ $evaluation->comments_synchronization }}</td>
                </tr>
                <tr>
                    <td>Quality of Work Total Rating</td>
                    <td>{{ $evaluation->qualityworktotalrating }}</td>
                </tr>
                <tr>
                    <td>1. Punctuality to workplace</td>
                    <td>{{ $evaluation->punctuality }} / {{ $evaluation->comments_punctuality }}</td>
                </tr>
                <tr>
                    <td>2. Attendance</td>
                    <td>{{ $evaluation->attendance }} / {{ $evaluation->comments_attendance }}</td>
                </tr>
                <tr>
                    <td>3. Does the employee stay busy, look for things to do, take initiatives at workplace</td>
                    <td>{{ $evaluation->initiatives_at_workplace }} / {{ $evaluation->comments_initiatives }}</td>
                </tr>
                <tr>
                    <td>4. Submits reports on time and meets deadlines</td>
                    <td>{{ $evaluation->submits_reports }} / {{ $evaluation->comments_submits_reports }}</td>
                </tr>
                <tr>
                    <td>Work Habits Total Rating</td>
                    <td>{{ $evaluation->work_habits_rating }}</td>
                </tr>
                <tr>
                    <td>1. Skill and ability to perform job satisfactorily</td>
                    <td>{{ $evaluation->skill_ability }} / {{ $evaluation->comments_skill_ability }}</td>
                </tr>
                <tr>
                    <td>2. Shown interest in learning and improving</td>
                    <td>{{ $evaluation->learning_improving }} / {{ $evaluation->comments_learning_improving }}</td>
                </tr>
                <tr>
                    <td>3. Problem solving ability</td>
                    <td>{{ $evaluation->problem_solving_ability }} / {{ $evaluation->comments_problem_solving }}</td>
                </tr>
                <tr>
                    <td>Job Knowledge Total Rating</td>
                    <td>{{ $evaluation->jk_total_rating }}</td>
                </tr>
                {{-- <tr>
                    <td>Recommendation</td>
                    <td>{{ $evaluation->recomendation }}</td>
                </tr> --}}
                <tr>
                    <td>Evaluator's Name</td>
                    <td>{{ $evaluation->evalutors_name }}</td>
                </tr>
                <tr>
                    <td>Evaluator's Signature</td>
                    <td><img src="{{ asset('storage/' . $evaluation->evaluator_signatur) }}" alt="Evaluator's Signature"
                            style="width: 100px; height: 120px; object-fit: cover;"></td>
                </tr>
                <tr>
                    <td>Evaluation Date</td>
                    <td>{{ $evaluation->evaluator_signatur_date }}</td>
                </tr>
                <tr>
                    <td>1. Responds and contributes to team efforts</td>
                    <td>{{$evaluation->respond_contributes}} / {{$evaluation->comments_respond_contributes}}
                </tr>
                <tr>
                    <td>2. Responds positively to suggestions, instructions, and criticism</td>
                    <td>{{$evaluation->responds_positively}} / {{$evaluation->comments_responds_positively}}</td>
                </tr>
                <tr>
                    <td>3. Keeps supervisor informed of all details</td>
                    <td>{{$evaluation->supervisor}} / {{$evaluation->comments_supervisor}}</td>
                </tr>
                <tr>
                    <td>4. Adapts well to changing circumstances</td>
                    <td>{{$evaluation->adapts_changing}} / {{$evaluation->comments_adapts_changing}}</td>
                </tr>
                <tr>
                    <td>5. Seeks feedback to improve</td>
                    <td>{{$evaluation->seeks_feedback}} / {{$evaluation->comments_seeks_feedback}}</td>
                </tr>
                <tr>
                    <td>Interpersonal Relations Total Rating</td>
                    <td>{{$evaluation->ir_total_rating}}</td>
                </tr>
                <tr>
                    <td>1. Aspirant to climb up the ladder, accepts challenges, new responsibilities, and roles</td>
                    <td>{{$evaluation->challenges}} / {{$evaluation->comments_challenges}}</td>
                </tr>
                <tr>
                    <td>2. Innovative thinking - contribution to organizations, functions, and personal growth</td>
                    <td>{{$evaluation->personal_growth}} / {{$evaluation->comments_personal_growth}}</td>
                </tr>
                <tr>
                    <td>3. Work motivation</td>
                    <td>{{$evaluation->work_motivation}} / {{$evaluation->comments_work_motivation}}
                    <td>
                </tr>
                <tr>
                    <td>Leadership Skill Total Rating</td>
                    <td>{{$evaluation->leadership_rating}}</td>
                </tr>
                <tr>
                    <td>1. Employee performance and learning is unsatisfactory and is failing to improve at a satisfactory rate
                    </td>
                    <td>{{$evaluation->progress_unsatisfactory}} / {{$evaluation->comments_unsatisfactory}}</td>
                </tr>
                <tr>
                    <td>2. Employee performance and learning is acceptable and is improving at a satisfactory rate</td>
                    <td>{{$evaluation->progress_acceptable}} / {{$evaluation->comments_acceptable}}</td>
                </tr>
                <tr>
                    <td>3. Employee has successfully demonstrated outstanding overall performance</td>
                    <td>{{$evaluation->progress_outstanding}} / {{$evaluation->comments_outstanding}}</td>
                </tr>
                <tr>
                    <td>Total Scoring System</td>
                    <td>{{ $evaluation->total_scoring_system }}</td>
                </tr>
                <tr>
                    <td>FINAL COMMENTS</td>
                    <td>{{$evaluation->final_comment}}
                </tr>
                <tr>
                    <td>Director's Name</td>
                    <td>{{$evaluation->director_name}}</td>
                </tr>
                <tr>
                    <td>director_signatur</td>
                    <td><img src="{{ asset('storage/' . $evaluation->director_signatur) }}" alt="Director's Signature"
                            style="width: 100px; height: 120px; object-fit: cover;"></td>
                </tr>
                <tr>
                    <td>director_signatur_date</td>
                    <td>{{$evaluation->director_signatur_date}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

@endsection

@section('scripts')
    <!-- Include DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.2/css/jquery.dataTables.min.css">

    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Include DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#employeeEvaluationTable').DataTable({
                "paging": true,
                "searching": true,
                "ordering": false,
                "info": true,
                "lengthMenu": [5, 10, 25, 50],
                "columnDefs": [
                    { "targets": [0, 1], "searchable": true }
                ]
            });
        });
    </script>
@endsection