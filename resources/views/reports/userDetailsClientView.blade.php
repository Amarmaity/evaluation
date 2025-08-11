@extends('layouts.app')

@section('title', 'Client Review Details')
{{-- @section('breadcrumb',' /Employee'. ' ' .$employee_id ) --}}
@section('breadcrumb', "Employee {$employee_id} /Client Review")

@section('body-class', 'special-page')

@section('content')

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

        .set-position tr {
            display: flex;
            gap: 100px;
        }

        .set-position td {
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

        @media (max-width: 1124px) {
            .set-position tr {
                gap: 40px;
            }
        }
    </style>

    <div class="container">

        <!-- Back Button aligned to the right -->
        <div class="text-right mb-3">
            <a href="{{ url()->previous() }}" class="btn btn-secondary">Back</a>
        </div>

        <h2>Client Review Details</h2>
        <!-- Client Review History Table -->
        <div class="table-container span-tage">
            <div class="table-wrapper">
                <table id="clientReviewHistoryTable" class="display table table-striped  table-bordered set-position">
                    <thead>
                        <tr>
                            <th>Field</th>
                            <th>Rating</th>
                            <th>Comments</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reviews as $review)
                            <tr>
                                <td>1. Understanding Requirements</td>
                                <td>({{ $review->understand_requirements }} /5)</td>
                                <td>{{ $review->comment_understand_requirements }}</td>
                            </tr>
                            <tr>
                                <td>2. Business Needs</td>
                                <td>({{ $review->business_needs }}/5)</td>
                                <td>{{ $review->comments_business_needs }}</td>
                            </tr>
                            <tr>
                                <td>3. Detailed Project Scope</td>
                                <td>({{ $review->detailed_project_scope }}/5)</td>
                                <td>{{ $review->comments_detailed_project_scope }}</td>
                            </tr>
                            <tr>
                                <td>4. Responsive to Project Needs</td>
                                <td>({{ $review->responsive_reach_project }}/5)</td>
                                <td>{{ $review->comments_responsive_reach_project }}</td>
                            </tr>
                            <tr>
                                <td>5. Comfortable Discussing Issues</td>
                                <td>({{ $review->comfortable_discussing }}/5)</td>
                                <td>{{ $review->comments_comfortable_discussing }}
                                </td>
                            </tr>
                            <tr>
                                <td>6. Regular Updates</td>
                                <td>({{ $review->regular_updates }} /5)</td>
                                <td>{{ $review->comments_regular_updates }}</td>
                            </tr>
                            <tr>
                                <td>7. Concerns Addressed</td>
                                <td>({{ $review->concerns_addressed }}/5)</td>
                                <td>{{ $review->comments_concerns_addressed }}</td>
                            </tr>
                            <tr>
                                <td>8. Technical Expertise</td>
                                <td>({{ $review->technical_expertise }}/5)</td>
                                <td>{{ $review->comments_technical_expertise }}</td>
                            </tr>
                            <tr>
                                <td>9. Best Practices</td>
                                <td>({{ $review->best_practices }}/5)</td>
                                <td>{{ $review->comments_best_practices }}</td>
                            </tr>
                            <tr>
                                <td>10. Innovation Suggestions</td>
                                <td>({{ $review->suggest_innovative }}/5)</td>
                                <td>{{ $review->comments_suggest_innovative }}</td>
                            </tr>
                            <tr>
                                <td>11. Quality of Code</td>
                                <td>({{ $review->quality_code }} /5)</td>
                                <td>{{ $review->comments_quality_code }}</td>
                            </tr>
                            <tr>
                                <td>12. Handling Issues</td>
                                <td>({{ $review->encounter_issues }} /5)</td>
                                <td>{{ $review->comments_encounter_issues }}</td>
                            </tr>
                            <tr>
                                <td>13. Scalability of Code</td>
                                <td>({{ $review->code_scalable }} /5)</td>
                                <td>{{ $review->comments_code_scalable }}</td>
                            </tr>
                            <tr>
                                <td>14. Performance of Solutions</td>
                                <td>({{ $review->solution_perform }}/5)</td>
                                <td>{{ $review->comments_solution_perform }}</td>
                            </tr>
                            <tr>
                                <td>15. Project Delivery</td>
                                <td>({{ $review->project_delivered }}/5)</td>
                                <td>{{ $review->comments_project_delivered }}</td>
                            </tr>
                            <tr>
                                <td>16. Communication & Handling</td>
                                <td>({{ $review->communicated_handled }}/5)</td>
                                <td>{{ $review->comments_communicated_handled }}</td>
                            </tr>
                            <tr>
                                <td>17. Development Process</td>
                                <td>({{ $review->development_process }}/5)</td>
                                <td>{{ $review->comments_development_process }}</td>
                            </tr>
                            <tr>
                                <td>18. Handling Unexpected Challenges</td>
                                <td>({{ $review->unexpected_challenges }}/5)</td>
                                <td>{{ $review->comments_unexpected_challenges }}</td>
                            </tr>
                            <tr>
                                <td>19. Effective Workarounds</td>
                                <td>({{ $review->effective_workarounds }}/5)</td>
                                <td>{{ $review->comments_effective_workarounds }}</td>
                            </tr>
                            <tr>
                                <td>20. Bug & Issue Resolution</td>
                                <td>({{ $review->bugs_issues }}/5)</td>
                                <td>{{ $review->comments_bugs_issues }}</td>
                            </tr>
                            <tr>
                                <td>Total Client Review Score</td>
                                <td>{{ $review->ClientTotalReview }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <!-- Include DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.2/css/jquery.dataTables.min.css">

    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Include DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.2/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#clientReviewHistoryTable').DataTable({
                "paging": true,
                "searching": true,
                "ordering": false,  // Disable ordering
                "info": true,
                "lengthMenu": [5, 10, 25, 50],  // Allow different page lengths
                "columnDefs": [
                    { "targets": [0, 1], "searchable": true }  // Enable search on the first two columns
                ]
            });
        });
    </script>
@endsection