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
            <th class="span-data">1. How would you rate the employee’s adherence to company policies and procedures?
                <span>({{ $user->adherence_hr }}/5)</span></th>
            <td>{{ $user->comments_adherence_hr }}</td>
        </tr>
        <tr>
            <th class="span-data">2. Does the employee maintain professionalism and a positive attitude in the
                workplace? <span>({{ $user->professionalism_positive }}/5)</span></th>
            <td>{{ $user->comments_professionalism }}</td>
        </tr>
        <tr>
            <th class="span-data">3. How well does the employee respond to feedback or suggestions for improvement from
                colleagues? <span>({{ $user->respond_feedback }}/5)</span></th>
            <td>{{ $user->comments_respond_feedback }}</td>
        </tr>
        <tr>
            <th class="span-data">4. Does the employee take the initiative to seek feedback and act on it?
                <span>({{ $user->initiative }}/5)</span></th>
            <td>{{ $user->comments_initiative }}</td>
        </tr>
        <tr>
            <th class="span-data">5. Has the employee shown interest in learning and participating in training programs?
                <span>({{ $user->interest_learning }}/5)</span></th>
            <td>{{ $user->comments_interest_learning }}</td>
        </tr>
        <tr>
            <th class="span-data">6. Does the employee consistently adhere to the company's leave policy?
                <span>({{ $user->company_leave_policy }}/5)</span></th>
            <td>{{ $user->comments_company_leave_policy }}</td>
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