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
            <th class="span-data">1. Has the employee demonstrated regular attendance and punctuality?
                <span>({{ $user->demonstrated_attendance }}/5)</span></th>
            <td>{{ $user->comments_demonstrated_attendance }}</td>
        </tr>
        <tr>
            <th class="span-data">2. How well does the employee manage time within the shift?
                <span>({{ $user->employee_manage_shift }} /5)</span></th>
            <td>{{ $user->comments_employee_manage_shift }}</td>
        </tr>
        <tr>
            <th class="span-data">3. How would you rate the employee’s accuracy and neatness in reports and
                documentation? <span>({{ $user->documentation_neatness }} /5)</span></th>
            <td>{{ $user->comments_documentation_neatness }}</td>
        </tr>
        <tr>
            <th class="span-data">4. Has the employee followed administrative procedures and job instructions properly?
                <span>({{ $user->followed_instructions }} /5)</span></th>
            <td>{{ $user->comments_followed_instructions }}</td>
        </tr>
        <tr>
            <th class="span-data">5. Does the employee effectively manage time and stay productive during working hours?
                <span>({{ $user->productive }}/5)</span></th>
            <td>{{ $user->comments_productive }}</td>
        </tr>
        <tr>
            <th class="span-data">6. How well does the employee handle changes in schedules or assignments?
                <span>({{ $user->changes_schedules }}/5)</span></th>
            <td>{{ $user->comments_changes_schedules }}</td>
        </tr>
        <tr>
            <th class="span-data">7. Does the employee consistently adhere to the company's leave policy?
                <span>({{ $user->leave_policy }} /5)</span></th>
            <td>{{ $user->comments_leave_policy }}</td>
        </tr>
        <tr>
            <th class="span-data">8. Has there been any salary deduction due to the employee's leave?
                <span>({{ $user->salary_deduction }}/5)</span></th>
            <td>{{ $user->comments_salary_deduction }}</td>
        </tr>
        <tr>
            <th class="span-data">9. How well does the employee interact with the housekeeping staff?
                <span>({{ $user->interact_housekeeping }}/5)</span></th>
            <td>{{ $user->comments_interact_housekeeping }}</td>
        </tr>
        <tr>
            <th>Total Admin Review</th>
            <td>{{ $user->AdminTotalReview }}</td>
        </tr>
    </table>
</div>