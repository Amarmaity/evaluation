<div class="table-responsive">
    <table class="table table-bordered">
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

        <!-- Ratings and Comments -->
        <tr>
            <td>Accuracy, neatness and timeliness of work</td>
            <td>{{ $user->accuracy_neatness }} - {{ $user->comments_accuracy }}</td>
        </tr>
        <tr>
            <td>Adherence to duties and procedures in Job Description and Work Instructions</td>
            <td>{{ $user->adherence }} - {{ $user->comments_adherence }}</td>
        </tr>
        <tr>
            <td>Synchronization with organizations/functional goals</td>
            <td>{{ $user->synchronization }} - {{ $user->comments_synchronization }}</td>
        </tr>
        <tr>
            <td>Quality of Work Total Rating</td>
            <td>{{ $user->qualityworktotalrating }}</td>
        </tr>
        <tr>
            <td>Punctuality to workplace</td>
            <td>{{ $user->punctuality }} - {{ $user->comments_punctuality }}</td>
        </tr>
        <tr>
            <td>Attendance</td>
            <td>{{ $user->attendance }} - {{ $user->comments_attendance }}</td>
        </tr>
        <tr>
            <td>Does the employee stay busy, look for things to do, take initiatives at workplace</td>
            <td>{{ $user->initiatives_at_workplace }} - {{ $user->comments_initiatives }}</td>
        </tr>
        <tr>
            <td>Submits reports on time and meets deadlines</td>
            <td>{{ $user->submits_reports }} - {{ $user->comments_submits_reports }}</td>
        </tr>
        <tr>
            <td>Work Habits Rating</td>
            <td>{{ $user->work_habits_rating }}</td>
        </tr>
        <tr>
            <td>Skill and ability to perform job satisfactorily</td>
            <td>{{ $user->skill_ability }} - {{ $user->comments_skill_ability }}</td>
        </tr>
        <tr>
            <td>Shown interest in learning and improving</td>
            <td>{{ $user->learning_improving }} - {{ $user->comments_learning_improving }}</td>
        </tr>
        <tr>
            <td>Problem solving ability</td>
            <td>{{ $user->problem_solving_ability }} - {{ $user->comments_problem_solving }}</td>
        </tr>
        <tr>
            <td>Job Knowledge Total Rating</td>
            <td>{{ $user->jk_total_rating }}</td>
        </tr>
        <tr>
            <td>Recommendation</td>
            <td>{{ $user->recomendation }}</td>
        </tr>
        <tr>
            <td>Evaluator's Name</td>
            <td>{{ $user->evalutors_name }}</td>
        </tr>
        <tr>
            <td>Evaluator's Signature</td>
            <td>{{ $user->evaluator_signatur }}</td>
        </tr>
        <tr>
            <td>Evaluation Date</td>
            <td>{{ $user->evaluator_signatur_date }}</td>
        </tr>
        <tr>
            <td>Responds and contributes to team efforts</td>
            <td>{{ $user->respond_contributes }} - {{ $user->comments_respond_contributes }}</td>
        </tr>
        <tr>
            <td>Responds positively to suggestions, instructions, and criticism</td>
            <td>{{ $user->responds_positively }} - {{ $user->comments_responds_positively }}</td>
        </tr>
        <tr>
            <td>Keeps supervisor informed of all details</td>
            <td>{{ $user->supervisor }} - {{ $user->comments_supervisor }}</td>
        </tr>
        <tr>
            <td>Adapts well to changing circumstances</td>
            <td>{{ $user->adapts_changing }} - {{ $user->comments_adapts_changing }}</td>
        </tr>
        <tr>
            <td>Seeks feedback to improve</td>
            <td>{{ $user->seeks_feedback }} - {{ $user->comments_seeks_feedback }}</td>
        </tr>
        <tr>
            <td>Interpersonal Relations Total Rating</td>
            <td>{{ $user->ir_total_rating }}</td>
        </tr>
        <tr>
            <td>Aspirant to climb up the ladder, accepts challenges, new responsibilities, and roles</td>
            <td>{{ $user->challenges }} - {{ $user->comments_challenges }}</td>
        </tr>
        <tr>
            <td>Innovative thinking - contribution to organizations, functions, and personal growth</td>
            <td>{{ $user->personal_growth }} - {{ $user->comments_personal_growth }}</td>
        </tr>
        <tr>
            <td>Work motivation</td>
            <td>{{ $user->work_motivation }} - {{ $user->comments_work_motivation }}</td>
        </tr>
        <tr>
            <td>Leadership Skill Total Rating</td>
            <td>{{ $user->leadership_rating }}</td>
        </tr>
        <tr>
            <td>Employee performance and learning is unsatisfactory and is failing to improve at a satisfactory rate</td>
            <td>{{ $user->progress_unsatisfactory }} - {{ $user->comments_unsatisfactory }}</td>
        </tr>
        <tr>
            <td>Employee performance and learning is acceptable and is improving at a satisfactory rate</td>
            <td>{{ $user->progress_acceptable }} - {{ $user->comments_acceptable }}</td>
        </tr>
        <tr>
            <td>Employee has successfully demonstrated outstanding overall performance</td>
            <td>{{ $user->progress_outstanding }} - {{ $user->comments_outstanding }}</td>
        </tr>
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
            <td>{{ $user->director_signatur }}</td>
        </tr>
        <tr>
            <td>Director's Signature Date</td>
            <td>{{ $user->director_signatur_date }}</td>
        </tr>
        

        <!-- Created At & Updated At -->
        {{-- <tr>
            <th>Created At</th>
            <td>{{ \Carbon\Carbon::parse($user->created_at)->format('d M, Y H:i:s') }}</td>
        </tr>
        <tr>
            <th>Updated At</th>
            <td>{{ \Carbon\Carbon::parse($user->updated_at)->format('d M, Y H:i:s') }}</td>
        </tr> --}}
    </table>
</div>
