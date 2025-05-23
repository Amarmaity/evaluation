<div class="table-responsive">
    <table class="table table-bordered">
        {{-- <tr>
            <th>Employee ID</th>
            <td>{{ $user->emp_id }}</td>
        </tr> --}}
        <tr>
            <th>1. How would you rate the employee’s quality of work, including accuracy, neatness, and timeliness?</th>
            <td>{{ $user->rate_employee_quality }} / {{ $user->comments_rate_employee_quality }}</td>
        </tr>
        <tr>
            <th>2. Does the employee align their work with the organization's goals and objectives?</th>
            <td>{{ $user->organizational_goals }} / {{ $user->comments_organizational_goals }}</td>
        </tr>
        <tr>
            <th>3. How effectively does the employee contribute to team efforts and collaborate with colleagues?</th>
            <td>{{ $user->collaborate_colleagues }} / {{ $user->comments_collaborate_colleagues }}</td>
        </tr>
        <tr>
            <th>4. Can you provide an example of when the employee demonstrated problem-solving skills?</th>
            <td>{{ $user->leadership_responsibilities }} / {{ $user->comments_leadership_responsibilities }}</td>
        </tr>
        <tr>
            <th>5. Has the employee shown leadership potential or accepted additional responsibilities?</th>
            <td>{{ $user->demonstrated }} / {{ $user->comments_demonstrated }}</td>
        </tr>
        <tr>
            <th>6. How would you rate the employee’s innovative thinking and contribution to team success?</th>
            <td>{{ $user->thinking_contribution }} / {{ $user->comments_thinking_contribution }}</td>
        </tr>
        <tr>
            <th>7. Does the employee effectively keep you informed about work progress and issues?</th>
            <td>{{ $user->informed_progress }} / {{ $user->comments_comments_informed_progress }}</td>
        </tr>
        <tr>
            <th>Total Manager Review</th>
            <td>{{ $user->ManagerTotalReview }}</td>
        </tr>
    </table>
</div>
