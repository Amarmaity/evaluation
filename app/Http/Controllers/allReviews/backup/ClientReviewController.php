<?php

namespace App\Http\Controllers\allReviews;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ClientReviewController extends Controller
{
    //


    public function showClientReview($emp_id)
    {
        $table = 'client_review_tables';

        $clientColumnMappings = [
            'emp_id',
        'understand_requirements' => 'Did the developer(s) understand your project requirements clearly?',
        'comment_understand_requirements' => 'Justify Review',
        'business_needs'=> 'Were your business goals and technical needs properly translated into the solution?',
        'comments_business_needs' => 'Justify Review',
        'detailed_project_scope' => 'Was there a clear and detailed project scope defined at the beginning?',
        'comments_detailed_project_scope' => 'Justify Review',
        'responsive_reach_project' => 'Was the developer(s) responsive and easy to reach during the project',
        'comments_responsive_reach_project' => 'Justify Review',
        'comfortable_discussing' => 'Did you feel comfortable discussing changes or suggestions?',
        'comments_comfortable_discussing' => 'Justify Review',
        'regular_updates' => 'Did the developer(s) provide regular updates on progress?',
        'comments_regular_updates' => 'Justify Review',
        'concerns_addressed' => 'Were your questions and concerns addressed promptly?',
        'comments_concerns_addressed' => 'Justify Review',
        'technical_expertise' => 'How would you rate the technical expertise of the developer(s)?',
        'comments_technical_expertise' => 'Justify Review',
        'best_practices' => 'Were industry best practices followed during the development process?',
        'comments_best_practices' => 'Justify Review',
        'suggest_innovative' => 'Did the developer(s) suggest innovative solutions or improvements?', 
        'comments_suggest_innovative' => 'Justify Review',
        'quality_code' => 'How would you rate the quality of the code delivered?',
        'comments_quality_code' => 'Justify Review',
        'encounter_issues' => 'Did you encounter any bugs or issues post-launch?',
        'comments_encounter_issues' => 'Justify Review',
        'code_scalable' => 'Was the code scalable and well-structured for future updates',
        'comments_code_scalable' => 'Justify Review',
        'solution_perform' => 'Did the solution perform well under expected load and conditions?',
        'comments_solution_perform' => 'Justify Review',
        'project_delivered' => 'Was the project delivered on time?',
        'comments_project_delivered' => 'Justify Review',
        'communicated_handled' => 'If there were delays, were they communicated and handled effectively?',
        'comments_communicated_handled' => 'Justify Review',
        'development_process' => 'Was the development process well-organized and structured?',
        'comments_development_process' => 'Justify Review',
        'unexpected_challenges' => 'How well did the developer(s) handle unexpected challenges or changes?',
        'comments_unexpected_challenges' => 'Justify Review',
        'effective_workarounds'=> 'Did the developer(s) propose effective workarounds when issues arose?',
        'comments_effective_workarounds' => 'Justify Review',
        'bugs_issues' => 'How quickly were bugs or issues resolved during the project?',
        'comments_bugs_issues' => 'Justify Review',
        'ClientTotalReview' => 'Client Total Review',
        ];

        // Fixing the typo: Change 'tabel' to 'table'
        $users = DB::table($table)->where('emp_id', $emp_id)->first();
        if (!$users) {
            return redirect()->back()->with('error', 'No review found for this employee.');
        }

        // Fixing compact: Variable names shouldn't have '$' inside compact()
        return view('review/clientReviewDetails', compact('clientColumnMappings', 'users', 'emp_id'));
    }
}
