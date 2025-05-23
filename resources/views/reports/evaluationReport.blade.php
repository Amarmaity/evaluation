<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Side by Side Form</title>

    <!-- CSRF Token for AJAX -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding-top: 30px;
        }

        .table-responsive {
            margin-bottom: 30px;
        }

        .table th {
            background-color: #007bff;
            color: white;
        }

        .table td {
            background-color: white;
        }

        .form-control {
            border-radius: 5px;
        }

        .form-group label {
            font-weight: bold;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .form-section {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .form-section h3 {
            margin-bottom: 20px;
        }

        .form-section .row {
            margin-bottom: 15px;
        }

        .evaluation__form {
            margin-top: 30px;
        }

        .evaluation__form textarea {
            resize: none;
        }

        .btn-clear {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-clear:hover {
            background-color: #007bff;
            border-color: #007bff;
        }
    </style>
</head>
<div>

    <body>
        <div>
            <div class="table-responsive">
                <table class="table table-bordered">
                    @if(Session::get('user_type') === 'Super User')
                        <tr>
                            <th>Employee Name</th>
                            <td>{{ $user->employee_name }}</td>
                        </tr>
                        <tr>
                            <th>Employee ID</th>
                            <td>{{ $user->emp_id }}</td>
                        </tr>
                        <tr>
                            <th>Designation</th>
                            <td>{{ $user->designation }}</td>
                        </tr>
                        <tr>
                            <th>Salary Grade</th>
                            <td>{{ $user->salary_grade }}</td>
                        </tr>
                        <tr>
                            <th>Department</th>
                            <td>{{ $user->department }}</td>
                        </tr>
                        <tr>
                            <th>Evaluation Purpose</th>
                            <td>{{ $user->evaluation_purpose }}</td>
                        </tr>
                        <tr>
                            <th>Division</th>
                            <td>{{ $user->division }}</td>
                        </tr>
                        <tr>
                            <th>Manager Name</th>
                            <td>{{ $user->manager_name }}</td>
                        </tr>
                        <tr>
                            <th>Joining Date</th>
                            <td>{{ \Carbon\Carbon::parse($user->joining_date)->format('d M, Y') }}</td>
                        </tr>
                        <tr>
                            <th>Review Period</th>
                            <td>{{ $user->review_period }}</td>
                        </tr>
                    @endif

                    <!-- Ratings and Comments -->
                    <tr>
                        <td>1. Accuracy, neatness and timeliness of work</td>
                        <td>{{ $user->accuracy_neatness }} / {{ $user->comments_accuracy }}</td>
                    </tr>
                    <tr>
                        <td>2. Adherence to duties and procedures in Job Description and Work Instructions</td>
                        <td>{{ $user->adherence }} / {{ $user->comments_adherence }}</td>
                    </tr>
                    <tr>
                        <td>3. Synchronization with organizations/functional goals</td>
                        <td>{{ $user->synchronization }} / {{ $user->comments_synchronization }}</td>
                    </tr>
                    <tr>
                        <td>Quality of Work Total Rating</td>
                        <td>{{ $user->qualityworktotalrating }}</td>
                    </tr>
                    <tr>
                        <td>1. Punctuality to workplace</td>
                        <td>{{ $user->punctuality }} / {{ $user->comments_punctuality }}</td>
                    </tr>
                    <tr>
                        <td>2. Attendance</td>
                        <td>{{ $user->attendance }} / {{ $user->comments_attendance }}</td>
                    </tr>
                    <tr>
                        <td>3. Does the employee stay busy, look for things to do, take initiatives at workplace</td>
                        <td>{{ $user->initiatives_at_workplace }} / {{ $user->comments_initiatives }}</td>
                    </tr>
                    <tr>
                        <td>4. Submits reports on time and meets deadlines</td>
                        <td>{{ $user->submits_reports }} / {{ $user->comments_submits_reports }}</td>
                    </tr>
                    <tr>
                        <td>Work Habits Total Rating</td>
                        <td>{{ $user->work_habits_rating }}</td>
                    </tr>
                    <tr>
                        <td>1. Skill and ability to perform job satisfactorily</td>
                        <td>{{ $user->skill_ability }} / {{ $user->comments_skill_ability }}</td>
                    </tr>
                    <tr>
                        <td>2. Shown interest in learning and improving</td>
                        <td>{{ $user->learning_improving }} / {{ $user->comments_learning_improving }}</td>
                    </tr>
                    <tr>
                        <td>3. Problem solving ability</td>
                        <td>{{ $user->problem_solving_ability }} / {{ $user->comments_problem_solving }}</td>
                    </tr>
                    <tr>
                        <td>Job Knowledge Total Rating</td>
                        <td>{{ $user->jk_total_rating }}</td>
                    </tr>
                    {{-- <tr>
                        <td>Recommendation</td>
                        <td>{{ $user->recomendation }}</td>
                    </tr> --}}
                    <tr>
                        <td>Evaluator's Name</td>
                        <td>{{ $user->evalutors_name }}</td>
                    </tr>
                    <tr>
                        <td>Evaluator's Signature</td>
                        <td>
                            <img src="{{ asset('storage/' . $user->evaluator_signatur) }}" alt="Evaluator's Signature"
                                style="width: 100px; height: 120px; object-fit: cover;">
                        </td>
                    </tr>

                    <tr>
                        <td>Evaluation Date</td>
                        <td>{{ $user->evaluator_signatur_date }}</td>
                    </tr>
                    <tr>
                        <td>1. Responds and contributes to team efforts</td>
                        <td>{{ $user->respond_contributes }} - {{ $user->comments_respond_contributes }}</td>
                    </tr>
                    <tr>
                        <td>2. Responds positively to suggestions, instructions, and criticism</td>
                        <td>{{ $user->responds_positively }} - {{ $user->comments_responds_positively }}</td>
                    </tr>
                    <tr>
                        <td>3. Keeps supervisor informed of all details</td>
                        <td>{{ $user->supervisor }} - {{ $user->comments_supervisor }}</td>
                    </tr>
                    <tr>
                        <td>4. Adapts well to changing circumstances</td>
                        <td>{{ $user->adapts_changing }} - {{ $user->comments_adapts_changing }}</td>
                    </tr>
                    <tr>
                        <td>5. Seeks feedback to improve</td>
                        <td>{{ $user->seeks_feedback }} - {{ $user->comments_seeks_feedback }}</td>
                    </tr>
                    <tr>
                        <td>Interpersonal Relations Total Rating</td>
                        <td>{{ $user->ir_total_rating }}</td>
                    </tr>
                    <tr>
                        <td>1. Aspirant to climb up the ladder, accepts challenges, new responsibilities, and roles
                        </td>
                        <td>{{ $user->challenges }} - {{ $user->comments_challenges }}</td>
                    </tr>
                    <tr>
                        <td>2. Innovative thinking - contribution to organizations, functions, and personal growth</td>
                        <td>{{ $user->personal_growth }} - {{ $user->comments_personal_growth }}</td>
                    </tr>
                    <tr>
                        <td>3. Work motivation</td>
                        <td>{{ $user->work_motivation }} - {{ $user->comments_work_motivation }}</td>
                    </tr>
                    <tr>
                        <td>Leadership Skill Total Rating</td>
                        <td>{{ $user->leadership_rating }}</td>
                    </tr>
                    <tr>
                        <td>1. Employee performance and learning is unsatisfactory and is failing to improve at a
                            satisfactory
                            rate</td>
                        <td>{{ $user->progress_unsatisfactory }} - {{ $user->comments_unsatisfactory }}</td>
                    </tr>
                    <tr>
                        <td>2. Employee performance and learning is acceptable and is improving at a satisfactory rate
                        </td>
                        <td>{{ $user->progress_acceptable }} - {{ $user->comments_acceptable }}</td>
                    </tr>
                    <tr>
                        <td>3. Employee has successfully demonstrated outstanding overall performance</td>
                        <td>{{ $user->progress_outstanding }} - {{ $user->comments_outstanding }}</td>
                    </tr>
                    <tr>
                        <td>Total score in evaluation:</td>
                        <td>{{$user->total_scoring_system}}</td>
                    </tr>
                    @if(in_array(Session::get('user_type'), ['admin', 'hr', 'manage', 'Super User', 'users']))
                        <tr>
                            <td>FINAL COMMENTS</td>
                            <td>{{ $user->final_comment }}</td>
                        </tr>
                        <tr>
                            <td>Director's Name</td>
                            <td>{{ $user->director_name }}</td>
                        </tr>
                        <tr>
                            <td>Director's Signature</td>
                            <td>
                                <img src="{{ asset('storage/' . $user->director_signatur) }}" alt="Director's Signature"
                                    style="width: 100px; height: 120px; object-fit: cover;">
                            </td>
                        </tr>

                        <tr>
                            <td>Director's Signature Date</td>
                            <td>{{ $user->director_signatur_date }}</td>
                        </tr>
                    @endif
                </table>
            </div>
            @if($user->director_feedback_flag == 0)
                    @if(Session::get('user_type') === 'Super User')
                        <form action="{{ route('director-submit-from', $user->emp_id) }}" method="POST" id="evaluationSubmit"
                            class="evaluation__form" enctype="multipart/form-data">
                            @csrf
                            <div class="form-section form-section1">
                                <h3>Director Upload</h3>
                                <div class="mb-4">
                                    <label for="final_comment">FINAL COMMENTS:</label>
                                    <textarea name="final_comment" id="f_comment" rows="1" class="form-control"></textarea>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="director_name">Director's Name:</label>
                                        <input type="text" name="director_name" id="d_name" class="form-control"
                                            placeholder="Enter Name">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="signatur">Signature:</label>
                                        <input type="file" name="director_signatur" id="signatur" class="form-control">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="date">Date:</label>
                                        <input type="date" name="director_signatur_date" id="date" class="form-control">
                                    </div>
                                </div>
                                {{-- <div class="mt-3 text-end">
                                    <button onclick="history.back()" class="btn btn-secondary">← Back</button>
                                </div> --}}
                            </div>
                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary secondary-btn">Submit</button>
                                <button type="reset" class="btn btn-outlined secondary-btn">Clear</button>
                            </div>
                        </form>
                    @endif
                </div>
            @endif
</div>
</body>
</div>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<script>


    document.getElementById('evaluationSubmit').addEventListener('submit', function (e) {
        e.preventDefault();


        var formData = new FormData(this);


        var emp_id = '{{ $user->emp_id ?? "" }}';


        if (!emp_id) {
            alert('Employee ID is missing. Cannot submit the form.');
            return;
        }


        fetch(`/evaluation-report-submit/${emp_id}`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Director evaluation submitted successfully!');
                    document.getElementById('evaluationSubmit').reset(); // Reset the form after submission
                    location.reload();
                } else {
                    alert(data.message || 'There was an error submitting the form.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('There was an error submitting the form. Please try again.');
            });
    });



    // if (Session:: get('user_type') === 'Super User') {
    //     // Get the form element by its ID
    //     document.getElementById('evaluationSubmit').addEventListener('submit', function (e) {
    //         e.preventDefault(); // Prevent default form submission

    //         var formData = new FormData(this); // Get form data

    //         // Get the employee ID from the Blade template (ensure the emp_id exists)
    //         var emp_id = '{{ $user->emp_id ?? "" }}';

    //         // Check if the emp_id exists
    //         if (!emp_id) {
    //             alert('Employee ID is missing. Cannot submit the form.');
    //             return; // Stop form submission if emp_id is missing
    //         }

    //         // Send a POST request with the form data
    //         fetch(`/evaluation-report-submit/${emp_id}`, {
    //             method: 'POST',
    //             body: formData,
    //             headers: {
    //                 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    //             }
    //         })
    //             .then(response => response.json())  // Ensure the response is JSON
    //             .then(data => {
    //                 // Log the response to the console to inspect it
    //                 console.log('Response Data:', data);  // Add this line to check what you're getting

    //                 if (data.success) {
    //                     alert('Director evaluation submitted successfully!');
    //                     document.getElementById('evaluationSubmit').reset(); // Reset the form after submission
    //                     location.reload();
    //                 } else {
    //                     alert(data.message || 'There was an error submitting the form.');
    //                 }
    //             })
    //             .catch(error => {
    //                 console.error('Error:', error);
    //                 alert('There was an error submitting the form. Please try again.');
    //             });
    //     });
    // }


</script>