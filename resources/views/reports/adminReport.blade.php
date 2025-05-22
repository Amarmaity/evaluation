<div class="table-responsive">
    <table class="table table-bordered">
        {{-- <tr>
            <th>Employee ID</th>
            <td>{{ $user->emp_id }}</td>
        </tr> --}}
        <tr>
            <th>Has the employee demonstrated regular attendance and punctuality?</th>
            <td>{{ $user->demonstrated_attendance }} / {{ $user->comments_demonstrated_attendance }}</td>
        </tr>
        <tr>
            <th>How well does the employee manage time within the shift?</th>
            <td>{{ $user->employee_manage_shift }} / {{ $user->comments_employee_manage_shift }}</td>
        </tr>
        <tr>
            <th>How would you rate the employee’s accuracy and neatness in reports and documentation?</th>
            <td>{{ $user->documentation_neatness }} / {{ $user->comments_documentation_neatness }}</td>
        </tr>
        <tr>
            <th>Has the employee followed administrative procedures and job instructions properly?</th>
            <td>{{ $user->followed_instructions }} / {{ $user->comments_followed_instructions }}</td>
        </tr>
        <tr>
            <th>Does the employee effectively manage time and stay productive during working hours?</th>
            <td>{{ $user->productive }} / {{ $user->comments_productive }}</td>
        </tr>
        <tr>
            <th>How well does the employee handle changes in schedules or assignments?</th>
            <td>{{ $user->changes_schedules }} / {{ $user->comments_changes_schedules }}</td>
        </tr>
        <tr>
            <th>Does the employee consistently adhere to the company's leave policy?</th>
            <td>{{ $user->leave_policy }} / {{ $user->comments_leave_policy }}</td>
        </tr>
        <tr>
            <th>Has there been any salary deduction due to the employee's leave?</th>
            <td>{{ $user->salary_deduction }} / {{ $user->comments_salary_deduction }}</td>
        </tr>
        <tr>
            <th>How well does the employee interact with the housekeeping staff?</th>
            <td>{{ $user->interact_housekeeping }} / {{ $user->comments_interact_housekeeping }}</td>
        </tr>
        <tr>
            <th>Total Admin Review</th>
            <td>{{ $user->AdminTotalReview }}</td>
        </tr>

        <!-- Created At & Updated At -->
        {{-- <tr>
            <th>Created At</th>
            <td>{{ $user->created_at ?? 'Not available' }}</td>
        </tr>
        <tr>
            <th>Updated At</th>
            <td>{{ $user->updated_at ?? 'Not available' }}</td>
        </tr> --}}
    </table>
</div>
