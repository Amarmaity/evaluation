<div class="table-responsive">
    <table class="table table-bordered">
        {{-- <tr>
            <th>Employee ID</th>
            <td>{{ $user->emp_id }}</td>
        </tr> --}}
        <tr>
            <th>How would you rate the employee’s adherence to company policies and procedures?</th>
            <td>{{ $user->adherence_hr }} / {{ $user->comments_adherence_hr }}</td>
        </tr>
        <tr>
            <th>Does the employee maintain professionalism and a positive attitude in the workplace?</th>
            <td>{{ $user->professionalism_positive }} / {{ $user->comments_professionalism }}</td>
        </tr>
        <tr>
            <th>How well does the employee respond to feedback or suggestions for improvement from colleagues?</th>
            <td>{{ $user->respond_feedback }} / {{ $user->comments_respond_feedback }}</td>
        </tr>
        <tr>
            <th>Does the employee take the initiative to seek feedback and act on it?</th>
            <td>{{ $user->initiative }} / {{ $user->comments_initiative }}</td>
        </tr>
        <tr>
            <th>Has the employee shown interest in learning and participating in training programs?</th>
            <td>{{ $user->interest_learning }} / {{ $user->comments_interest_learning }}</td>
        </tr>
        <tr>
            <th>Does the employee consistently adhere to the company's leave policy?</th>
            <td>{{ $user->company_leave_policy }} / {{ $user->comments_company_leave_policy }}</td>
        </tr>
        <tr>
            <th>Total HR Review</th>
            <td>{{ $user->HrTotalReview }}</td>
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
