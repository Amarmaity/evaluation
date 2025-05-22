<div class="table-responsive">
    <table class="table table-bordered">
        {{-- <tr>
            <th>Employee ID</th>
            <td>{{ $user->emp_id }}</td>
        </tr> --}}
        <tr>
            <th>Understand Requirements</th>
            <td>{{ $user->understand_requirements }} - {{ $user->comment_understand_requirements }}</td>
        </tr>
        <tr>
            <th>Business Needs</th>
            <td>{{ $user->business_needs }} - {{ $user->comments_business_needs }}</td>
        </tr>
        <tr>
            <th>Detailed Project Scope</th>
            <td>{{ $user->detailed_project_scope }} - {{ $user->comments_detailed_project_scope }}</td>
        </tr>
        <tr>
            <th>Responsive to Project Reach</th>
            <td>{{ $user->responsive_reach_project }} - {{ $user->comments_responsive_reach_project }}</td>
        </tr>
        <tr>
            <th>Comfortable Discussing Requirements</th>
            <td>{{ $user->comfortable_discussing }} - {{ $user->comments_comfortable_discussing }}</td>
        </tr>
        <tr>
            <th>Regular Updates</th>
            <td>{{ $user->regular_updates }} - {{ $user->comments_regular_updates }}</td>
        </tr>
        <tr>
            <th>Concerns Addressed</th>
            <td>{{ $user->concerns_addressed }} - {{ $user->comments_concerns_addressed }}</td>
        </tr>
        <tr>
            <th>Technical Expertise</th>
            <td>{{ $user->technical_expertise }} - {{ $user->comments_technical_expertise }}</td>
        </tr>
        <tr>
            <th>Best Practices Followed</th>
            <td>{{ $user->best_practices }} - {{ $user->comments_best_practices }}</td>
        </tr>
        <tr>
            <th>Suggests Innovative Solutions</th>
            <td>{{ $user->suggest_innovative }} - {{ $user->comments_suggest_innovative }}</td>
        </tr>
        <tr>
            <th>Quality of Code</th>
            <td>{{ $user->quality_code }} - {{ $user->comments_quality_code }}</td>
        </tr>
        <tr>
            <th>Encountered Issues</th>
            <td>{{ $user->encounter_issues }} - {{ $user->comments_encounter_issues }}</td>
        </tr>
        <tr>
            <th>Code Scalability</th>
            <td>{{ $user->code_scalable }} - {{ $user->comments_code_scalable }}</td>
        </tr>
        <tr>
            <th>Solution Performance</th>
            <td>{{ $user->solution_perform }} - {{ $user->comments_solution_perform }}</td>
        </tr>
        <tr>
            <th>Project Delivered</th>
            <td>{{ $user->project_delivered }} - {{ $user->comments_project_delivered }}</td>
        </tr>
        <tr>
            <th>Communicated & Handled</th>
            <td>{{ $user->communicated_handled }} - {{ $user->comments_communicated_handled }}</td>
        </tr>
        <tr>
            <th>Development Process</th>
            <td>{{ $user->development_process }} - {{ $user->comments_development_process }}</td>
        </tr>
        <tr>
            <th>Unexpected Challenges</th>
            <td>{{ $user->unexpected_challenges }} - {{ $user->comments_unexpected_challenges }}</td>
        </tr>
        <tr>
            <th>Effective Workarounds</th>
            <td>{{ $user->effective_workarounds }} - {{ $user->comments_effective_workarounds }}</td>
        </tr>
        <tr>
            <th>Bugs & Issues</th>
            <td>{{ $user->bugs_issues }} - {{ $user->comments_bugs_issues }}</td>
        </tr>
        <tr>
            <th>Total Client Review</th>
            <td>{{ $user->ClientTotalReview }}</td>
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
