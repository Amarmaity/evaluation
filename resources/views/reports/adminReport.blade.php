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
            <td>1. Has the employee demonstrated regular attendance and punctuality?</td>
            <td>({{ $user->demonstrated_attendance }}/5)</td>
            <td>{{ $user->comments_demonstrated_attendance }}</td>
        </tr>
        <tr>
            <td>2. How well does the employee manage time within the shift?</td>
            <td>({{ $user->employee_manage_shift }} /5)</td>
            <td>{{ $user->comments_employee_manage_shift }}</td>
        </tr>
        <tr>
            <td>3. How would you rate the employee’s accuracy and neatness in reports and documentation?</td>
            <td>({{ $user->documentation_neatness }} /5)</td>
            <td>{{ $user->comments_documentation_neatness }}</td>
        </tr>
        <tr>
            <td>4. Has the employee followed administrative procedures and job instructions properly?</td>
            <td>({{ $user->followed_instructions }} /5)</td>
            <td>{{ $user->comments_followed_instructions }}</td>
        </tr>
        <tr>
            <td>5. Does the employee effectively manage time and stay productive during working hours?</td>
            <td>({{ $user->productive }}/5)</td>
            <td>{{ $user->comments_productive }}</td>
        </tr>
        <tr>
            <td>6. How well does the employee handle changes in schedules or assignments?</td>
            <td>({{ $user->changes_schedules }}/5)</td>
            <td>{{ $user->comments_changes_schedules }}</td>
        </tr>
        <tr>
            <td>7. Does the employee consistently adhere to the company's leave policy?</td>
            <td>({{ $user->leave_policy }} /5)</td>
            <td>{{ $user->comments_leave_policy }}</td>
        </tr>
        <tr>
            <td>8. Has there been any salary deduction due to the employee's leave?</td>
            <td>({{ $user->salary_deduction }}/5)</td>
            <td>{{ $user->comments_salary_deduction }}</td>
        </tr>
        <tr>
            <td>9. How well does the employee interact with the housekeeping staff?</td>
            <td>({{ $user->interact_housekeeping }}/5)</td>
            <td>{{ $user->comments_interact_housekeeping }}</td>
        </tr>
        <tr>
            <th>Total Admin Review</th>
            <td>{{ $user->AdminTotalReview }}</td>
        </tr>
    </table>
</div>