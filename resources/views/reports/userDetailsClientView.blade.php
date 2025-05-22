@extends('layouts.app')

@section('title', 'Client Review Details')
{{-- @section('breadcrumb',' /Employee'. ' ' .$employee_id ) --}}
@section('breadcrumb', "Employee {$employee_id} /Client Review")

@section('content')
    <div class="container">
        
        <!-- Back Button aligned to the right -->
        <div class="text-right mb-3">
            <a href="{{ url()->previous() }}" class="btn btn-secondary">Back</a>
        </div>
        
        <h2>Client Review Details</h2>
        <!-- Client Review History Table -->
        <table id="clientReviewHistoryTable" class="display table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Field</th>
                    <th>Rating / Comments</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reviews as $review)
                    <tr>
                        <td>Understanding Requirements</td>
                        <td>{{ $review->understand_requirements }} / {{ $review->comment_understand_requirements }}</td>
                    </tr>
                    <tr>
                        <td>Business Needs</td>
                        <td>{{ $review->business_needs }} / {{ $review->comments_business_needs }}</td>
                    </tr>
                    <tr>
                        <td>Detailed Project Scope</td>
                        <td>{{ $review->detailed_project_scope }} / {{ $review->comments_detailed_project_scope }}</td>
                    </tr>
                    <tr>
                        <td>Responsive to Project Needs</td>
                        <td>{{ $review->responsive_reach_project }} / {{ $review->comments_responsive_reach_project }}</td>
                    </tr>
                    <tr>
                        <td>Comfortable Discussing Issues</td>
                        <td>{{ $review->comfortable_discussing }} / {{ $review->comments_comfortable_discussing }}</td>
                    </tr>
                    <tr>
                        <td>Regular Updates</td>
                        <td>{{ $review->regular_updates }} / {{ $review->comments_regular_updates }}</td>
                    </tr>
                    <tr>
                        <td>Concerns Addressed</td>
                        <td>{{ $review->concerns_addressed }} / {{ $review->comments_concerns_addressed }}</td>
                    </tr>
                    <tr>
                        <td>Technical Expertise</td>
                        <td>{{ $review->technical_expertise }} / {{ $review->comments_technical_expertise }}</td>
                    </tr>
                    <tr>
                        <td>Best Practices</td>
                        <td>{{ $review->best_practices }} / {{ $review->comments_best_practices }}</td>
                    </tr>
                    <tr>
                        <td>Innovation Suggestions</td>
                        <td>{{ $review->suggest_innovative }} / {{ $review->comments_suggest_innovative }}</td>
                    </tr>
                    <tr>
                        <td>Quality of Code</td>
                        <td>{{ $review->quality_code }} / {{ $review->comments_quality_code }}</td>
                    </tr>
                    <tr>
                        <td>Handling Issues</td>
                        <td>{{ $review->encounter_issues }} / {{ $review->comments_encounter_issues }}</td>
                    </tr>
                    <tr>
                        <td>Scalability of Code</td>
                        <td>{{ $review->code_scalable }} / {{ $review->comments_code_scalable }}</td>
                    </tr>
                    <tr>
                        <td>Performance of Solutions</td>
                        <td>{{ $review->solution_perform }} / {{ $review->comments_solution_perform }}</td>
                    </tr>
                    <tr>
                        <td>Project Delivery</td>
                        <td>{{ $review->project_delivered }} / {{ $review->comments_project_delivered }}</td>
                    </tr>
                    <tr>
                        <td>Communication & Handling</td>
                        <td>{{ $review->communicated_handled }} / {{ $review->comments_communicated_handled }}</td>
                    </tr>
                    <tr>
                        <td>Development Process</td>
                        <td>{{ $review->development_process }} / {{ $review->comments_development_process }}</td>
                    </tr>
                    <tr>
                        <td>Handling Unexpected Challenges</td>
                        <td>{{ $review->unexpected_challenges }} / {{ $review->comments_unexpected_challenges }}</td>
                    </tr>
                    <tr>
                        <td>Effective Workarounds</td>
                        <td>{{ $review->effective_workarounds }} / {{ $review->comments_effective_workarounds }}</td>
                    </tr>
                    <tr>
                        <td>Bug & Issue Resolution</td>
                        <td>{{ $review->bugs_issues }} / {{ $review->comments_bugs_issues }}</td>
                    </tr>
                    <tr>
                        <td>Total Client Review Score</td>
                        <td>{{ $review->ClientTotalReview }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
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
        $(document).ready(function() {
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