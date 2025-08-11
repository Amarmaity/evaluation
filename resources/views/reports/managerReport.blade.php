<style>
    .span-tage .span-data {
        display: flex;
        justify-content: space-between;
        padding-right: 60px;
    }

    .span-tage tr {
        /* border-bottom: 1px solid #000; */
        margin-bottom: 30px;
    }
</style>

<div class="table-responsive">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Field</th>
                <th>Rating</th>
                <th>Comments</th>
            </tr>
        </thead>
        <tr>
            <td>1. How would you rate the employee’s quality of work, including accuracy, neatness, and timeliness?</td>
            <td>({{ $user->rate_employee_quality }}/5)</td>
            <td>{{ $user->comments_rate_employee_quality }}</td>
        </tr>
        <tr>
            <td>2. Does the employee align their work with the organization's goals and objectives?</td>
            <td>({{ $user->organizational_goals }}/5)</td>
            <td>{{ $user->comments_organizational_goals }}</td>
        </tr>
        <tr>
            <td >3. How effectively does the employee contribute to team efforts and collaborate with colleagues?</td>
            <td>({{ $user->collaborate_colleagues }}/5)</td>
            <td>{{ $user->comments_collaborate_colleagues }}</td>
        </tr>
        <tr>
            <td>4. Can you provide an example of when the employee demonstrated problem-solving skills? </td>
            <td>({{ $user->leadership_responsibilities }}/5)</td>
            <td>{{ $user->comments_leadership_responsibilities }}</td>
        </tr>
        <tr>
            <td>5. Has the employee shown leadership potential or accepted additional responsibilities?</td>
            <td>({{ $user->demonstrated }}/5)</td>
            <td>{{ $user->comments_demonstrated }}</td>
        </tr>
        <tr>
            <td>6. How would you rate the employee’s innovative thinking and contribution to team success? </td>
            <td>({{ $user->thinking_contribution }}/5)</td>
            <td>{{ $user->comments_thinking_contribution }}</td>
        </tr>
        <tr>
            <td>7. Does the employee effectively keep you informed about work progress and issues?</td>
            <td>({{ $user->informed_progress }}/5)</td>
            <td>{{ $user->comments_comments_informed_progress }}</td>
        </tr>
        <tr>
            <th>Total Manager Review</th>
            <td>{{ $user->ManagerTotalReview }}</td>
        </tr>
    </table>
</div>