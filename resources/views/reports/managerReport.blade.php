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

<div class="table-responsive span-tage">
    <table class="table table-bordered">
        {{-- <tr>
            <th>Employee ID</th>
            <td>{{ $user->emp_id }}</td>
        </tr> --}}
        <thead>
            <tr>
                <th class="span-data">Field <span>Rating</span></th>
                <th>Comments</th>
            </tr>
        </thead>
        <tr>
            <th class="span-data">1. How would you rate the employee’s quality of work, including accuracy, neatness,
                and timeliness? <span>({{ $user->rate_employee_quality }}/5)</span></th>
            <td>{{ $user->comments_rate_employee_quality }}</td>
        </tr>
        <tr>
            <th class="span-data">2. Does the employee align their work with the organization's goals and objectives?
                <span>({{ $user->organizational_goals }}/5)</span></th>
            <td>{{ $user->comments_organizational_goals }}</td>
        </tr>
        <tr>
            <th class="span-data">3. How effectively does the employee contribute to team efforts and collaborate with
                colleagues? <span>({{ $user->collaborate_colleagues }}/5)</span></th>
            <td>{{ $user->comments_collaborate_colleagues }}</td>
        </tr>
        <tr>
            <th class="span-data">4. Can you provide an example of when the employee demonstrated problem-solving
                skills? <span>({{ $user->leadership_responsibilities }}/5)</span></th>
            <td>{{ $user->comments_leadership_responsibilities }}</td>
        </tr>
        <tr>
            <th class="span-data">5. Has the employee shown leadership potential or accepted additional
                responsibilities? <span>({{ $user->demonstrated }}/5)</span></th>
            <td>{{ $user->comments_demonstrated }}</td>
        </tr>
        <tr>
            <th class="span-data">6. How would you rate the employee’s innovative thinking and contribution to team
                success? <span>({{ $user->thinking_contribution }}/5)</span></th>
            <td>{{ $user->comments_thinking_contribution }}</td>
        </tr>
        <tr>
            <th class="span-data">7. Does the employee effectively keep you informed about work progress and issues?
                <span>({{ $user->informed_progress }}/5)</span></th>
            <td>{{ $user->comments_comments_informed_progress }}</td>
        </tr>
        <tr>
            <th>Total Manager Review</th>
            <td>{{ $user->ManagerTotalReview }}</td>
        </tr>
    </table>
</div>