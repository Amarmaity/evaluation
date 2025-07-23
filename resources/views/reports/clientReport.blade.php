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
    .set-position tr{
            display: flex;
            gap: 100px;
           
        }
        .set-position tr, .set-position th,  .set-position td,  .set-position tbody{
             cursor: auto !important;
            }

        .set-position td , .set-position th{
            width: 100%;
        }

        .set-position thead tr {
            display: flex;
            justify-content: space-between;
        }

        .set-position thead tr th {
            display: flex;
            width: 100%;
            justify-content: flex-start;
        }
        .set-position .span-data , .set-position th{
            color: #000000 !important;
        }

        @media (max-width: 1124px) {
            .set-position tr {
                gap: 40px;
            }
        }
</style>
<div class="container table-container span-tage">
    <div class="table-responsive table-wrapper">
        <table class="table table-bordered table-hover main-table set-position">
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
                <th class="span-data">1. Understand Requirements <span>({{ $user->understand_requirements }}/5)</span></th>
                <td>{{ $user->comment_understand_requirements }}</td>
            </tr>
            <tr>
                <th class="span-data">2. Business Needs <span>({{ $user->business_needs }}/5)</span></th>
                <td>{{ $user->comments_business_needs }}</td>
            </tr>
            <tr>
                <th class="span-data">3. Detailed Project Scope <span>({{ $user->detailed_project_scope }}/5)</span>
                </th>
                <td>{{ $user->comments_detailed_project_scope }}</td>
            </tr>
            <tr>
                <th class="span-data">4. Responsive to Project Reach  <span>({{ $user->responsive_reach_project }}/5)</span>
                </th>
                <td>{{ $user->comments_responsive_reach_project }}</td>
            </tr>
            <tr>
                <th class="span-data">5. Comfortable Discussing Requirements <span>({{ $user->comfortable_discussing }}/5)</span></th>
                <td>{{ $user->comments_comfortable_discussing }}</td>
            </tr>
            <tr>
                <th class="span-data">6. Regular Updates <span>({{ $user->regular_updates }}/5)</span></th>
                <td>{{ $user->comments_regular_updates }}</td>
            </tr>
            <tr>
                <th class="span-data">7. Concerns Addressed <span>({{ $user->concerns_addressed }} /5)</span></th>
                <td>{{ $user->comments_concerns_addressed }}</td>
            </tr>
            <tr>
                <th class="span-data">8. Technical Expertise <span>({{ $user->technical_expertise }} /5)</span></th>
                <td>{{ $user->comments_technical_expertise }}</td>
            </tr>
            <tr>
                <th class="span-data">9. Best Practices Followed <span>({{ $user->best_practices }}/5)</span></th>
                <td>{{ $user->comments_best_practices }}</td>
            </tr>
            <tr>
                <th class="span-data">10.Suggests Innovative Solutions <span>({{ $user->suggest_innovative }}/5)</span>
                </th>
                <td>{{ $user->comments_suggest_innovative }}</td>
            </tr>
            <tr>
                <th class="span-data">11. Quality of Code <span>({{ $user->quality_code }}/5)</span></th>
                <td>{{ $user->comments_quality_code }}</td>
            </tr>
            <tr>
                <th class="span-data">12. Encountered Issues <span>({{ $user->encounter_issues }}/5)</span></th>
                <td>{{ $user->comments_encounter_issues }}</td>
            </tr>
            <tr>
                <th class="span-data">13. Code Scalability <span>({{ $user->code_scalable }}/5)</span></th>
                <td>{{ $user->comments_code_scalable }}</td>
            </tr>
            <tr>
                <th class="span-data">14. Solution Performance <span>({{ $user->solution_perform }}/5)</span></th>
                <td>{{ $user->comments_solution_perform }}</td>
            </tr>
            <tr>
                <th class="span-data">15. Project Delivered <span>({{ $user->project_delivered }}/5)</span></th>
                <td>{{ $user->comments_project_delivered }}</td>
            </tr>
            <tr>
                <th class="span-data">16. Communicated & Handled <span>({{ $user->communicated_handled }}/5)</span></th>
                <td>{{ $user->comments_communicated_handled }}</td>
            </tr>
            <tr>
                <th class="span-data">17. Development Process <span>({{ $user->development_process }}/5)</span></th>
                <td> {{ $user->comments_development_process }}</td>
            </tr>
            <tr>
                <th class="span-data">18. Unexpected Challenges <span>({{ $user->unexpected_challenges }}/5)</span></th>
                <td>{{ $user->comments_unexpected_challenges }}</td>
            </tr>
            <tr>
                <th class="span-data">19. Effective Workarounds <span>({{ $user->effective_workarounds }}/5)</span></th>
                <td>{{ $user->comments_effective_workarounds }}</td>
            </tr>
            <tr>
                <th class="span-data">20. Bugs & Issues <span>({{ $user->bugs_issues }}/5)</span></th>
                <td>{{ $user->comments_bugs_issues }}</td>
            </tr>
            <tr>
                <th class="span-data">Total Client Review <span>{{ $user->ClientTotalReview }}</span></th>
                <td></td>
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
</div>