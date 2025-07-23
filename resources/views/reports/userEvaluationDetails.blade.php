@extends('layouts.app')

@section('title', 'HR Review Details')

@section('breadcrumb', "Employee {$employee_id} / View Evaluation")

@section('body-class', 'special-page')

@section('content')


    <style>
        .span-tage .span-data {
            display: flex;
            justify-content: space-between;
            padding-right: 105px !important;

        }

        .span-tage tr {
            /* border-bottom: 1px solid #000; */
            margin-bottom: 30px;
        }
    </style>



    <div class="text-right mb-3">
        <a href="{{ url()->previous() }}" class="btn btn-secondary">Back</a>
    </div>

    <h2 class="heading">Employee Evaluation Details: {{$employee_id}}</h2>

    <!-- Employee Evaluation History Table -->
    <div class="table-container ">
        <div class="table-wrapper">
            <table id="employeeEvaluationTable" class="table table-bordered table-hover main-table">
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
                            <td>Manager Name:</td>
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

                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-5 second-table span-tage">
            <div class="table-wrapper">
                <table id="employeeEvaluationTable" class="table table-bordered table-hover main-table">
                    <thead>
                        <tr>
                            <th class="span-data">Field <span>Rating</span></th>
                            <th> Comments</th>
                        </tr>
                    </thead>

                    @foreach($eval as $evaluation)
                        <tr>
                            <td class="span-data">1. Accuracy, neatness and timeliness of work
                                <span>({{ $evaluation->accuracy_neatness }} /5)</span></td>
                            <td>{{ $evaluation->comments_accuracy }}</td>
                        </tr>
                        <tr>
                            <td class="span-data">2. Adherence to duties and procedures in Job Description and Work Instructions
                                <span>({{ $evaluation->adherence }}/5)</span></td>
                            <td>{{ $evaluation->comments_adherence }}</td>
                        </tr>
                        <tr>
                            <td class="span-data">3. Synchronization with organizations/functional goals
                                <span>({{ $evaluation->synchronization }} /5)</span></td>
                            <td>{{ $evaluation->comments_synchronization }}</td>
                        </tr>
                        <tr>
                            <td class="span-data">Quality of Work Total Rating <span>{{ $evaluation->qualityworktotalrating }}</span></td>
                            {{-- <td></td> --}}
                        </tr>
                        <tr>
                            <td class="span-data">1. Punctuality to workplace <span>({{ $evaluation->punctuality }} /5)</span>
                            </td>
                            <td>{{ $evaluation->comments_punctuality }}</td>
                        </tr>
                        <tr>
                            <td class="span-data">2. Attendance <span>({{ $evaluation->attendance }} /5)</span></td>
                            <td>{{ $evaluation->comments_attendance }}</td>
                        </tr>
                        <tr>
                            <td class="span-data">3. Does the employee stay busy, look for things to do, take initiatives at
                                workplace <span>({{ $evaluation->initiatives_at_workplace }} /5)</span></td>
                            <td>{{ $evaluation->comments_initiatives }}</td>
                        </tr>
                        <tr>
                            <td class="span-data">4. Submits reports on time and meets deadlines
                                <span>({{ $evaluation->submits_reports }} /5)</span></td>
                            <td>{{ $evaluation->comments_submits_reports }}</td>
                        </tr>
                        <tr>
                            <td class="span-data">Work Habits Total Rating <span>{{ $evaluation->work_habits_rating }}</span></td>
                            {{-- <td></td> --}}
                        </tr>
                        <tr>
                            <td class="span-data">1. Skill and ability to perform job satisfactorily
                                <span>({{ $evaluation->skill_ability }}/5)</span></td>
                            <td>{{ $evaluation->comments_skill_ability }}</td>
                        </tr>
                        <tr>
                            <td class="span-data">2. Shown interest in learning and improving
                                <span>({{ $evaluation->learning_improving }}/5)</span></td>
                            <td> {{ $evaluation->comments_learning_improving }}</td>
                        </tr>
                        <tr>
                            <td class="span-data">3. Problem solving ability
                                <span>({{ $evaluation->problem_solving_ability }}/5)</span></td>
                            <td>{{ $evaluation->comments_problem_solving }}</td>
                        </tr>
                        <tr>
                            <td>Job Knowledge Total Rating <span>{{ $evaluation->jk_total_rating }}</span></td>
                            {{-- <td></td> --}}
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
                            <td class="span-data">1. Responds and contributes to team efforts
                                <span>({{$evaluation->respond_contributes}}/5)</span></td>
                            <td>{{$evaluation->comments_respond_contributes}}
                        </tr>
                        <tr>
                            <td class="span-data">2. Responds positively to suggestions, instructions, and criticism
                                <span>({{$evaluation->responds_positively}}/5)</span></td>
                            <td>{{$evaluation->comments_responds_positively}}</td>
                        </tr>
                        <tr>
                            <td class="span-data">3. Keeps supervisor informed of all details <span>({{$evaluation->supervisor}}
                                    /5)</span></td>
                            <td>{{$evaluation->comments_supervisor}}</td>
                        </tr>
                        <tr>
                            <td class="span-data">4. Adapts well to changing circumstances
                                <span>({{$evaluation->adapts_changing}}/5)</span></td>
                            <td>{{$evaluation->comments_adapts_changing}}</td>
                        </tr>
                        <tr>
                            <td class="span-data">5. Seeks feedback to improve <span>({{$evaluation->seeks_feedback}}/5)</span>
                            </td>
                            <td>{{$evaluation->comments_seeks_feedback}}</td>
                        </tr>
                        <tr>
                            <td class="span-data">Interpersonal Relations Total Rating <span>{{$evaluation->ir_total_rating}}</span></td>
                            {{-- <td></td> --}}
                        </tr>
                        <tr>
                            <td class="span-data">1. Aspirant to climb up the ladder, accepts challenges, new responsibilities,
                                and roles <span>({{$evaluation->challenges}}/10)</span></td>
                            <td>{{$evaluation->comments_challenges}}</td>
                        </tr>
                        <tr>
                            <td class="span-data">2. Innovative thinking - contribution to organizations, functions, and
                                personal growth <span>({{$evaluation->personal_growth}}/10)</span></td>
                            <td>{{$evaluation->comments_personal_growth}}</td>
                        </tr>
                        <tr>
                            <td>3. Work motivation <span>({{$evaluation->work_motivation}} /5)</span></td>
                            <td>{{$evaluation->comments_work_motivation}}

                        </tr>
                        <tr>
                            <td class="span-data">Leadership Skill Total Rating <span>{{$evaluation->leadership_rating}}</span></td>
                            {{-- <td></td> --}}
                        </tr>
                        <tr>
                            <td class="span-data">1. Employee performance and learning is unsatisfactory and is failing to
                                improve at a
                                satisfactory rate <span>({{$evaluation->progress_unsatisfactory}})</span>
                            </td>
                            <td>{{$evaluation->comments_unsatisfactory}}</td>
                        </tr>
                        <tr>
                            <td class="span-data">2. Employee performance and learning is acceptable and is improving at a
                                satisfactory rate <span>({{$evaluation->progress_acceptable}})</span></td>
                            <td>{{$evaluation->comments_acceptable}}</td>
                        </tr>
                        <tr>
                            <td class="span-data">3. Employee has successfully demonstrated outstanding overall performance
                                <span>({{$evaluation->progress_outstanding}})</span></td>
                            <td> {{$evaluation->comments_outstanding}}</td>
                        </tr>
                        <tr>
                            <td>Total Scoring System <span>{{ $evaluation->total_scoring_system }}</span></td>
                            {{-- <td></td> --}}
                        </tr>
                        {{-- <tr>
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
                        </tr> --}}
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

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