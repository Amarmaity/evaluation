@extends('layouts.app')
@section('breadcrumb', 'Evaluation/' . $employee_id)
@section('title', 'Evaluation')

@section('content')


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Side by Side Form</title>


    <!-- CSRF Token for AJAX -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<style>
    button.info-button {
        width: 30px !important;
        border-radius: 50% !important;
        padding: 0 !important;
        min-height: 30px !important;
        min-width: 30px !important;
        height: 30px !important;
    }
</style>


    <body>

        <!-- Centered Headings -->


        <form action="{{route('insert-data-evaluation')}}" method="post" id="evaluationSubmit"
            class="evaluation__form form-block form-block--updated" enctype="multipart/form-data">
            @csrf
            <div class="client">
                <h1 class="client__heading">Evaluation</h1>
                <select id="financialYear" class="form-select client__select" name="financial_year" required>
                    <option value="" selected>Financial Year</option>
                    <option value="2025-2026">2025-2026</option>
                    <option value="2026-2027">2026-2027</option>
                    <option value="2027-2028">2027-2028</option>
                    <option value="2028-2029">2028-2029</option>
                    <option value="2029-2030">2029-2030</option>
                </select>
            </div>
            <!-- Form Section -->
            <div class="form-section1 form-section1--main">
                <div class="content-block">
                    <input type="checkbox" name="" id="content8">
                    <label for="content8" class="main-label">Employee & Evaluation Details</label>
                    <div class="content">
                        <div class="row form-div">
                            {{-- <div class="col-12 col-sm-6 search-container">
                                <label for="financialYear" class="forms-label">Financial Years:</label>
                                <select id="employeeDetails" name="financial_year" required class="form-control input-box">
                                    <option value="" selected>Select Financial Years</option>
                                    <option value="2025-2026">2025-2026</option>
                                    <option value="2026-2027">2026-2027</option>
                                    <option value="2027-2028">2027-2028</option>
                                    <option value="2028-2029">2028-2029</option>
                                    <option value="2028-2029">2029-2030</option>
                                </select>
                            </div> --}}
                            <div class="col-12 col-sm-6">
                                <label for="designation" class="forms-label">Designation:</label>
                                <input type="text" name="designation" id="designation" value="{{ $designation ?? '' }}"
                                    placeholder="Enter designation" class="form-control" readonly>
                            </div>
                            <div class="col-12 col-sm-6">
                                <label for="salary_grade" class="forms-label">Salary Grade/Band:</label>
                                <input type="text" name="salary_grade" id="salary_grade" placeholder="Enter Salary Grade"
                                    value="{{ $salary_grade ?? '' }}" class="form-control" readonly>
                            </div>
                            <div class="col-12 col-sm-6">
                                <label for="employee_name" class="forms-label">Name of Employee:</label>
                                <input type="text" name="employee_name" id="employee_name"
                                    value="{{ $employee_name ?? '' }}" placeholder="Enter name" class="form-control"
                                    readonly>
                            </div>
                            <div class="col-12 col-sm-6">
                                <label for="emp_id" class="forms-label">Employee Id:</label>
                                <input type="text" name="emp_id" id="emp_id" value="{{ $employee_id ?? '' }}"
                                    placeholder="Enter Employee Id" class="form-control" readonly>
                            </div>
                            {{-- <div class="col-12 col-sm-6">
                                <label for="department" class="forms-label">Department:</label>
                                <input type="text" name="department" id="department" value="{{ $department ?? '' }}"
                                    placeholder="Enter Department" required class="form-control">
                            </div> --}}
                            <div class="col-12 col-sm-6">
                                <label for="evaluation_purpose" class="forms-label">Evaluation Purpose:</label>
                                <input type="text" name="evaluation_purpose" id="evaluation_purpose"
                                    value="{{ $evaluation_purpose ?? '' }}" placeholder="Enter Evaluation Purpose" readonly
                                    class="form-control">
                            </div>
                            <div class="col-12 col-sm-6">
                                <label for="division" class="forms-label">Division:</label>
                                <input type="text" name="division" id="division" placeholder="Enter Division"
                                    value="{{ $division ?? '' }}" readonly class="form-control">
                            </div>
                            <div class="col-12 col-sm-6">
                                <label for="manager_name" class="forms-label">Manager Name:</label>
                                <input type="text" name="manager_name" id="manager_name" placeholder="Enter Manager Name"
                                    value="{{ $manager_name ?? '' }}" class="form-control" readonly>
                            </div>
                            <div class="col-12 col-sm-6">
                                <label for="joining_date" class="forms-label">Joining Date:</label>
                                <input type="date" name="joining_date" id="joining_date" class="form-control" readonly
                                    value="{{ $dob ?? '' }}">
                            </div>
                            <div class="col-12 col-sm-6">
                                <label for="review_period" class="forms-label">Review Period:</label>
                                <input type="text" name="review_period" id="review_period" placeholder="Enter Review Period"
                                    value="{{ $financial_year ?? '' }}" class="form-control" readonly>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-block">
                    <input type="checkbox" name="" id="content1">
                    <label for="content1" class="main-label">A. Functional Skills</label>
                    <div class="content">
                        <div class="form-section">
                            <label for="accuracy_neatness" class="second-label">1. Accuracy, neatness and timeliness of
                                work:
                            </label>
                            {{-- <button type="button" class="info-button"
                                title="Rate Yourself. 0 = Poor, 5 = Excellent.">i</button> --}}

                            <!-- <input type="number" name="accuracy_neatness" id="qw1" min="0" max="5" required
                                        oninput="qualityWorkTotalRating()" placeholder="Rate Yourself" class="form-control"> -->
                            <select class="form-select" aria-label="multiple select example" name="accuracy_neatness"
                                id="qw1" oninput="qualityWorkTotalRating()" required>
                                <option selected disabled>Rate</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                            <div class="review-block">
                                <label for="comment" class="third-label">Justify Your Review.</label>
                                <textarea name="comments_accuracy" id="comments" class="form-control" rows="1"
                                    cols="50"></textarea>
                            </div>

                            <label for="adherence" class="second-label">2. Adherence to duties and procedures in Job
                                Description
                                and Work
                                Instructions:

                            </label>
                            {{-- <button type="button" class="info-button"
                                title="Rate Yourself. 0 = Poor, 5 = Excellent.">i</button> --}}
                            <!-- <input type="number" name="adherence" id="qw2" min="0" max="5" required
                                        oninput="qualityWorkTotalRating()" placeholder="Rate Yourself" class="form-control"> -->
                            <select class="form-select" aria-label="multiple select example" name="adherence" id="qw2"
                                oninput="qualityWorkTotalRating()" required>
                                <option selected disabled>Rate</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                            <div class="review-block">
                                <label for="comment" class="third-label">Justify Your Review.</label>
                                <textarea name="comments_adherence" id="comments" class="form-control" rows="1"
                                    cols="50"></textarea>
                            </div>

                            <label for="synchronization" class="second-label">3. Synchronization with
                                organizations/functional
                                goals:

                            </label>
                            {{-- <button type="button" class="info-button"
                                title="Rate Yourself. 0 = Poor, 5 = Excellent.">i</button> --}}
                            <!-- <input type="number" name="synchronization" id="qw3" min="0" max="5" required
                                        oninput="qualityWorkTotalRating()" placeholder="Rate Yourself" class="form-control"> -->
                            <select class="form-select" aria-label="multiple select example" name="synchronization" id="qw3"
                                oninput="qualityWorkTotalRating()" required>
                                <option selected disabled>Rate</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                            <div class="review-block">
                                <label for="functional_goals" class="third-label">Justify Your Review</label>
                                <textarea name="comments_synchronization" id="comments" class="form-control" rows="1"
                                    cols="50"></textarea>
                            </div>

                            <label for="qualityworktotalrating" class="second-label">Quality of Work Total Rating:
                            </label>
                            <ol class="breadcrumb breadcrumb-div">
                                <li class="breadcrumb-item">
                                    <input type="text" name="qualityworktotalrating" id="qualityworktotalrating" readonly
                                        class="form-control form-total">
                                </li>
                                <li class="breadcrumb-item">15</li>
                            </ol>
                        </div>
                    </div>
                </div>
                <!-- Work Habits Section -->
                <div class="content-block">
                    <input type="checkbox" name="" id="content2">
                    <label for="content2" class="main-label">B. Work Habits</label>
                    <div class="content">
                        <div class="form-section">
                            <div>
                                <label for="punctuality" class="second-label">1. Punctuality in workplace:

                                </label>
                                {{-- <button type="button" class="info-button"
                                    title="Rate your self. 0 = Poor, 5 = Excellent.">i</button> --}}
                                <!-- <input type="number" name="punctuality" id="wh1" min="0" max="5" required
                                            oninput="workHabitsTotalRating()" placeholder="Rate Yourself" class="form-control"> -->
                                <select class="form-select" aria-label="multiple select example" name="punctuality" id="wh1"
                                    oninput="workHabitsTotalRating()" required>
                                    <option selected disabled>Rate</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>
                                <div class="review-block">
                                    <label for="comments_punctuality" class="third-label">Justify Your Review:</label>
                                    <textarea name="comments_punctuality" id="comments_punctuality" class="form-control"
                                        rows="1" cols="50"></textarea>
                                </div>
                            </div>

                            <div>
                                <label for="attendance" class="second-label">2. Attendance:

                                </label>
                                {{-- <button type="button" class="info-button"
                                    title="Rate Yourself. 0 = Poor, 5 = Excellent.">i</button> --}}
                                <!-- <input type="number" name="attendance" id="wh2" min="0" max="5" required
                                            oninput="workHabitsTotalRating()" placeholder="Rate Yourself" class="form-control"> -->
                                <select class="form-select" aria-label="multiple select example" name="attendance"
                                    oninput="workHabitsTotalRating()" id="wh2" required>
                                    <option selected disabled>Rate</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>
                                <div class="review-block">
                                    <label for="comments_attendance" class="third-label">Justify Your Review:</label>
                                    <textarea name="comments_attendance" id="comments_attendance" class="form-control"
                                        rows="1" cols="50"></textarea>
                                </div>
                            </div>

                            <div>
                                <label for="initiatives_at_workplace" class="second-label">3. Do you stay busy, look for
                                    things
                                    to
                                    do, take
                                    initiatives at workplace:

                                </label>
                                {{-- <button type="button" class="info-button"
                                    title="Rate Yourself. 0 = Poor, 5 = Excellent.">i</button> --}}
                                <!-- <input type="number" name="initiatives_at_workplace" id="wh3" min="0" max="5" required
                                            oninput="workHabitsTotalRating()" placeholder="Rate Yourself" class="form-control"> -->
                                <select class="form-select" aria-label="multiple select example"
                                    name="initiatives_at_workplace" oninput="workHabitsTotalRating()" id="wh3" required>
                                    <option selected disabled>Rate</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>
                                <div class="review-block">
                                    <label for="comments_initiatives" class="third-label">Justify Your Review:</label>
                                    <textarea name="comments_initiatives" id="comments_initiatives" class="form-control"
                                        rows="1" cols="50"></textarea>
                                </div>
                            </div>

                            <div>
                                <label for="submits_reports" class="second-label">4. Submits reports on time and meets
                                    deadlines:

                                </label>
                                {{-- <button type="button" class="info-button"
                                    title="Rate Yourself. 0 = Poor, 5 = Excellent.">i</button> --}}
                                <!-- <input type="number" name="submits_reports" id="wh4" min="0" max="5" required
                                            oninput="workHabitsTotalRating()" placeholder="Rate Yourself" class="form-control"> -->
                                <select class="form-select" aria-label="multiple select example" name="submits_reports"
                                    oninput="workHabitsTotalRating()" id="wh4" required>
                                    <option selected disabled>Rate</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>
                                <div class="review-block">
                                    <label for="comments_submits_reports" class="third-label">Justify Your Review:</label>
                                    <textarea name="comments_submits_reports" id="comments_submits_reports"
                                        class="form-control" rows="1" cols="50"></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Right Section (Total Rating) -->
                        <div class="form-section">
                            <label for="work_habits_rating" class="second-label">Work Habits Total Rating:</label>
                            <ol class="breadcrumb breadcrumb-div">
                                <li class="breadcrumb-item">
                                    <input type="text" name="work_habits_rating" id="work_habits_rating" readonly
                                        class="form-control form-total">
                                </li>
                                <li class="breadcrumb-item">20</li>
                            </ol>
                        </div>
                    </div>
                </div>

                <!-- Job Knowledge Section -->
                <div class="content-block">
                    <input type="checkbox" name="radio-block" id="content3">
                    <label for="content3" class="main-label">C. Job Knowledge</label>
                    <div class="content">
                        <!-- Left Section (Ratings) -->
                        <div class="form-section">
                            <div>
                                <label for="skill_ability" class="second-label">1. Skill and ability to perform job
                                    satisfactorily:
                                </label>
                                {{-- <button type="button" class="info-button"
                                    title="Rate Yourself. 0 = Poor, 5 = Excellent.">i</button> --}}
                                <!-- <input type="number" name="skill_ability" id="jk1" min="0" max="5" required
                                            oninput="jobKnowledgeTotalRating()" placeholder="Rate Yourself" class="form-control"> -->
                                <select class="form-select" aria-label="multiple select example" name="skill_ability"
                                    oninput="jobKnowledgeTotalRating()" id="jk1" required>
                                    <option selected disabled>Rate</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>
                                <div class="review-block">
                                    <label for="comments_skill_ability" class="third-label">Justify Your Review:</label>
                                    <textarea name="comments_skill_ability" id="comments_skill_ability" class="form-control"
                                        rows="1" cols="50"></textarea>
                                </div>
                            </div>

                            <div>
                                <label for="learning_improving" class="second-label">2. Shown interest in learning and
                                    improving:
                                </label>
                                {{-- <button type="button" class="info-button"
                                    title="Rate Yourself. 0 = Poor, 5 = Excellent.">i</button> --}}
                                <!-- <input type="number" name="learning_improving" id="jk2" min="0" max="5" required
                                            oninput="jobKnowledgeTotalRating()" placeholder="Rate Yourself" class="form-control"> -->
                                <select class="form-select" aria-label="multiple select example" name="learning_improving"
                                    oninput="jobKnowledgeTotalRating()" id="jk2" required>
                                    <option selected disabled>Rate</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>
                                <div class="review-block">
                                    <label for="comments_learning_improving" class="third-label">Justify Your
                                        Review:</label>
                                    <textarea name="comments_learning_improving" id="comments_learning_improving"
                                        class="form-control" rows="1" cols="50"></textarea>
                                </div>
                            </div>

                            <div>
                                <label for="problem_solving_ability" class="second-label">3. Problem solving ability:
                                </label>
                                {{-- <button type="button" class="info-button"
                                    title="Rate Yourself. 0 = Poor, 5 = Excellent.">i</button> --}}
                                <!-- <input type="number" name="problem_solving_ability" id="jk3" min="0" max="5" required
                                            oninput="jobKnowledgeTotalRating()" placeholder="Rate Yourself" class="form-control"> -->
                                <select class="form-select" aria-label="multiple select example"
                                    name="problem_solving_ability" oninput="jobKnowledgeTotalRating()" id="jk3" required>
                                    <option selected disabled>Rate</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>
                                <div class="review-block">
                                    <label for="comments_problem_solving" class="third-label">Justify Your Review:</label>
                                    <textarea name="comments_problem_solving" id="comments_problem_solving"
                                        class="form-control" rows="1" cols="50"></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Right Section (Total Rating) -->
                        <div class="form-section">
                            <div>
                                <label for="jk_total_rating" class="second-label">Job Knowledge Total Rating:
                                </label>
                                <ol class="breadcrumb breadcrumb-div">
                                    <li class="breadcrumb-item">
                                        <input type="text" name="jk_total_rating" id="jk_total_rating" readonly
                                            class="form-control form-total">
                                    </li>
                                    <li class="breadcrumb-item">15</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Interpersonal Relations/Behaviour Section -->
                <div class="content-block">
                    <input type="checkbox" name="" id="content5">
                    <label for="content5" class="main-label">D. Interpersonal Relations and Behavior</label>
                    <div class="content">

                        <!-- Left Section (Ratings) -->
                        <div class="form-section">
                            <div>
                                <label for="respond_contributes" class="second-label">1. Responds and contributes to team
                                    efforts:
                                </label>
                                {{-- <button type="button" class="info-button"
                                    title="Rate Yourself. 0 = Poor, 5 = Excellent.">i</button> --}}

                                <!-- <input type="number" name="respond_contributes" id="ir1" min="0" max="5" required
                                            oninput="interpersonalTotalRating()" placeholder="Rate Yourself" class="form-control"> -->
                                <select class="form-select" aria-label="multiple select example" name="respond_contributes"
                                    oninput="interpersonalTotalRating()" id="ir1" required>
                                    <option selected disabled>Rate</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>
                                <div class="review-block">
                                    <label for="comments_respond_contributes" class="third-label">Justify Your
                                        Review:</label>
                                    <textarea name="comments_respond_contributes" id="comments_respond_contributes" rows="1"
                                        cols="50" class="form-control"></textarea>
                                </div>
                            </div>

                            <div>
                                <label for="responds_positively" class="second-label">2. Responds positively to suggestions,
                                    instructions,
                                    and
                                    criticism:
                                </label>
                                {{-- <button type="button" class="info-button"
                                    title="Rate Yourself. 0 = Poor, 5 = Excellent.">i</button> --}}

                                <!-- <input type="number" name="responds_positively" id="ir2" min="0" max="5" required
                                            oninput="interpersonalTotalRating()" placeholder="Rate Yourself" class="form-control"> -->
                                <select class="form-select" aria-label="multiple select example" name="responds_positively"
                                    oninput="interpersonalTotalRating()" id="ir2" required>
                                    <option selected disabled>Rate</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>
                                <div class="review-block">
                                    <label for="comments_responds_positively" class="third-label">Justify Your
                                        Review:</label>
                                    <textarea name="comments_responds_positively" id="comments_responds_positively" rows="1"
                                        cols="50" class="form-control"></textarea>
                                </div>
                            </div>

                            <div>
                                <label for="supervisor" class="second-label">3. Keeps supervisor informed of all details:

                                </label>
                                {{-- <button type="button" class="info-button"
                                    title="Rate Yourself. 0 = Poor, 5 = Excellent.">i</button> --}}

                                <!-- <input type="number" name="supervisor" id="ir3" min="0" max="5" required
                                            oninput="interpersonalTotalRating()" placeholder="Rate Yourself" class="form-control"> -->
                                <select class="form-select" aria-label="multiple select example" name="supervisor"
                                    oninput="interpersonalTotalRating()" id="ir3" required>
                                    <option selected disabled>Rate</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>
                                <div class="review-block">
                                    <label for="comments_supervisor" class="third-label">Justify Your Review:
                                    </label>
                                    <textarea name="comments_supervisor" id="comments_supervisor" rows="1" cols="50"
                                        class="form-control"></textarea>
                                </div>
                            </div>

                            <div>
                                <label for="adapts_changing" class="second-label">4. Adapts well to changing circumstances:

                                </label>
                                {{-- <button type="button" class="info-button"
                                    title="Rate Yourself. 0 = Poor, 5 = Excellent.">i</button> --}}
                                <!-- <input type="number" name="adapts_changing" id="ir4" min="0" max="5" required
                                            oninput="interpersonalTotalRating()" placeholder="Rate Yourself" class="form-control"> -->
                                <select class="form-select" aria-label="multiple select example" name="adapts_changing"
                                    oninput="interpersonalTotalRating()" id="ir4" required>
                                    <option selected disabled>Rate</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>
                                <div class="review-block">
                                    <label for="comments_adapts_changing" class="third-label">Justify Your Review:</label>
                                    <textarea name="comments_adapts_changing" id="comments_adapts_changing" rows="1"
                                        cols="50" class="form-control"></textarea>
                                </div>
                            </div>

                            <div>
                                <label for="seeks_feedback" class="second-label">5. Seeks feedback to improve:

                                </label>
                                {{-- <button type="button" class="info-button"
                                    title="Rate Yourself. 0 = Poor, 5 = Excellent.">i</button> --}}
                                <!-- <input type="number" name="seeks_feedback" id="ir5" min="0" max="5" required
                                            oninput="interpersonalTotalRating()" placeholder="Rate Yourself" class="form-control"> -->
                                <select class="form-select" aria-label="multiple select example" name="seeks_feedback"
                                    oninput="interpersonalTotalRating()" id="ir5" required>
                                    <option selected disabled>Rate</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>
                                <div class="review-block">
                                    <label for="comments_seeks_feedback" class="third-label">Justify Your Review:</label>
                                    <textarea name="comments_seeks_feedback" id="comments_seeks_feedback" rows="1" cols="50"
                                        class="form-control"></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Right Section (Total Rating) -->
                        <div class="form-section">
                            <div>
                                <label for="ir_total_rating" class="second-label">Interpersonal Relations Total
                                    Rating:</label>
                                <ol class="breadcrumb breadcrumb-div">
                                    <li class="breadcrumb-item">
                                        <input type="text" name="ir_total_rating" id="ir_total_rating" readonly
                                            class="form-control form-total">

                                    </li>
                                    <li class="breadcrumb-item">25</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Leadership Skills Section -->
                <div class="content-block">
                    <input type="checkbox" name="" id="content6">
                    <label for="content6" class="main-label">E. Leadership</label>
                    <div class="content">
                        <!-- Left Section (Ratings) -->
                        <div class="form-section">
                            <div>
                                <label for="challenges" class="second-label">1. Aspirant to climb up the ladder, accepts
                                    challenges,
                                    new
                                    responsibilities, and
                                    roles
                                </label>
                                <select class="form-select" aria-label="multiple select example" name="challenges"
                                    oninput="leadershipTotalRating()" id="ls1" required>
                                    <option selected disabled>Rate</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                    <option value="7">7</option>
                                    <option value="8">8</option>
                                    <option value="9">9</option>
                                    <option value="10">10</option>
                                </select>
                                <div class="review-block">
                                    <label for="comments_challenges" class="third-label">Justify Your Review:</label>
                                    <textarea name="comments_challenges" id="comments_challenges" class="form-control"
                                        rows="1" cols="50"></textarea>
                                </div>
                            </div>

                            <div>
                                <label for="personal_growth" class="second-label">2. Innovative thinking - contribution to
                                    organizations,
                                    functions, and
                                    personal growth:

                                </label>
                                <select class="form-select" aria-label="multiple select example" name="personal_growth"
                                    oninput="leadershipTotalRating()" id="ls2" required>
                                    <option selected disabled>Rate</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                    <option value="7">7</option>
                                    <option value="8">8</option>
                                    <option value="9">9</option>
                                    <option value="10">10</option>
                                </select>
                                <div class="review-block">
                                    <label for="comments_personal_growth" class="third-label">Justify Your Review:</label>
                                    <textarea name="comments_personal_growth" id="comments_personal_growth"
                                        class="form-control" rows="1" cols="50"></textarea>
                                </div>
                            </div>

                            <div>
                                <label for="work_motivation" class="second-label">3. Work motivation:

                                </label>
                                {{-- <button type="button" class="info-button"
                                    title="Rate Yourself. 0 = Poor, 5 = Excellent.">i</button> --}}
                                <!-- <input type="number" name="work_motivation" id="ls3" min="0" max="5" required
                                            oninput="leadershipTotalRating()" placeholder="Rate Yourself" class="form-control"> -->
                                <select class="form-select" aria-label="multiple select example" name="work_motivation"
                                    oninput="leadershipTotalRating()" id="ls3" required>
                                    <option selected disabled>Rate</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>
                                <div class="review-block">
                                    <label for="comments_work_motivation" class="third-label">Justify Your Review:</label>
                                    <textarea name="comments_work_motivation" id="comments_work_motivation"
                                        class="form-control" rows="1" cols="50"></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Right Section (Total Rating) -->
                        <div class="form-section">
                            <div>
                                <label for="leadership_rating" class="second-label">Leadership Skill Total Rating:</label>
                                <ol class="breadcrumb breadcrumb-div">
                                    <li class="breadcrumb-item">
                                        <input type="text" name="leadership_rating" id="leadership_rating" readonly
                                            class="form-control form-total">

                                    </li>
                                    <li class="breadcrumb-item">25</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>


                {{-- OVERALL PROGRESS --}}
                <div class="content-block">
                    <input type="checkbox" name="" id="content7">
                    <label for="content7" class="main-label">F. Overall Progress</label>
                    <div class="content">
                        <div>
                            <div class="d-flex align-items-center">
                                <label for="progress_unsatisfactory_yes" class="second-label">1.
                                    The employee's performance and learning are unsatisfactory and are failing to improve at
                                    a
                                    satisfactory rate.
                                </label>

                                <div class="radio-button radio-block d-flex align-items-center">
                                    <div class="radio-block__item">
                                        <input type="radio" id="progress_unsatisfactory_yes" name="progress_unsatisfactory"
                                            value="Yes">
                                        <label for="progress_unsatisfactory_yes">Yes</label>
                                    </div>
                                    <div class="radio-block__item">
                                        <input type="radio" id="progress_unsatisfactory_no" name="progress_unsatisfactory"
                                            value="No" checked>
                                        <label for="progress_unsatisfactory_no">No</label>
                                    </div>
                                </div>
                            </div>

                            <label for="comments_unsatisfactory" class="third-label third-label--new">Justify Your
                                Review:</label>
                            <textarea name="comments_unsatisfactory" id="comments_unsatisfactory" class="form-control"
                                rows="1" cols="50"></textarea>
                        </div>

                        <!-- Acceptable Section -->
                        <div>
                            <div class="d-flex align-items-center">
                                <label for="progress_acceptable_yes" class="second-label">2.
                                    Employee performance and learning are acceptable and are improving at a satisfactory
                                    rate.
                                </label>

                                <div class="radio-button radio-block d-flex align-items-center">
                                    <div class="radio-block__item">
                                        <input type="radio" id="progress_acceptable_yes" name="progress_acceptable"
                                            value="Yes">
                                        <label for="progress_acceptable_yes">Yes</label>
                                    </div>
                                    <div class="radio-block__item">
                                        <input type="radio" id="progress_acceptable_no" name="progress_acceptable"
                                            value="No" checked>
                                        <label for="progress_acceptable_no">No</label>
                                    </div>
                                </div>
                            </div>

                            <label for="comments_acceptable" class="third-label third-label--new">Justify Your
                                Review:</label>
                            <textarea name="comments_acceptable" id="comments_acceptable" class="form-control" rows="1"
                                cols="50"></textarea>
                        </div>

                        <!-- Outstanding Section -->
                        <div>
                            <div class="d-flex align-items-center">
                                <label for="progress_outstanding_yes" class="second-label">3.
                                    Employee has successfully demonstrated outstanding overall performance:
                                </label>

                                <div class="radio-button radio-block d-flex align-items-center">
                                    <div class="radio-block__item">
                                        <input type="radio" id="progress_outstanding_yes" name="progress_outstanding"
                                            value="Yes">
                                        <label for="progress_outstanding_yes">Yes</label>
                                    </div>
                                    <div class="radio-block__item">
                                        <input type="radio" id="progress_outstanding_no" name="progress_outstanding"
                                            value="No" checked>
                                        <label for="progress_outstanding_no">No</label>
                                    </div>
                                </div>
                            </div>

                            <label for="comments_outstanding" class="third-label third-label--new">Justify Your
                                Review:</label>
                            <textarea name="comments_outstanding" id="comments_outstanding" class="form-control" rows="1"
                                cols="50"></textarea>
                        </div>
                    </div>
                </div>


                <!-- Scoring System Section -->
                <div class="content-block">
                    <input type="checkbox" name="" id="content4">
                    <label for="content4" class="main-label">Signature Upload</label>
                    <div class="content">
                        <!-- <div class="table-wrapper table-wrapper--modified">
                                    <table class="table table-bordered table-hover client-table scoring-table">
                                        <thead>
                                            <tr>
                                                <th>Attribute</th>
                                                <th>Score</th>
                                                <th>Total</th>
                                                <th class="total-cell">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="attribute-row">
                                                <td>Outstanding</td>
                                                <td class="score-column">12-15</td>
                                                <td rowspan="5" id="total-score" class="total-cell" name="total_scoring_system"></td>

                                            </tr>
                                            <tr class="attribute-row">
                                                <td>Exceeds Requirements</td>
                                                <td class="score-column">8-11</td>
                                            </tr>
                                            <tr class="attribute-row">
                                                <td>Meets Requirements</td>
                                                <td class="score-column">5-7</td>
                                            </tr>
                                            <tr class="attribute-row">
                                                <td>Needs Improvement</td>
                                                <td class="score-column">2-3</td>
                                            </tr>
                                            <tr class="attribute-row">
                                                <td>Unsatisfactory</td>
                                                <td class="score-column">1</td>
                                            </tr>
                                            <tr class="total-row">
                                                <td colspan="2">Total</td>
                                                <td id="total-score" class="total-cell" name="total_scoring_system"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div> -->
                        {{-- <div class="table-wrapper table-wrapper--modified">
                            <table class="table table-bordered table-hover client-table scoring-table">
                                <thead>
                                    <tr>
                                        <th>Attribute</th>
                                        <th>Score</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="attribute-row">
                                        <td>Outstanding</td>
                                        <td class="score-column">12-15</td>
                                    </tr>
                                    <tr class="attribute-row">
                                        <td>Exceeds Requirements</td>
                                        <td class="score-column">8-11</td>
                                    </tr>
                                    <tr class="attribute-row">
                                        <td>Meets Requirements</td>
                                        <td class="score-column">5-7</td>
                                    </tr>
                                    <tr class="attribute-row">
                                        <td>Needs Improvement</td>
                                        <td class="score-column">2-3</td>
                                    </tr>
                                    <tr class="attribute-row">
                                        <td>Unsatisfactory</td>
                                        <td class="score-column">1</td>
                                    </tr>
                                    <tr class="total-row">
                                        <td><strong>Total</strong></td>
                                        <td id="total-score" class="total-cell" name="total_scoring_system"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div> --}}

                        {{-- Recomendation part --}}

                        <!-- Evaluator Recommendations Section -->
                        <div class="">
                            <div>
                                <div class="row">
                                    {{-- <div class="col-12">
                                        <label for="recommendation" class="second-label">Recommendations:</label>
                                        <textarea name="recomendation" id="evalution_recomendation" rows="1" cols="50"
                                            class="form-control"></textarea>
                                    </div> --}}

                                    <div class="col-12 col-md-4">
                                        <label for="evalutors_name" class="second-label">Evaluator's Name:</label>
                                        <input type="text" id="evalutors_name" name="evalutors_name"
                                            placeholder="Enter Name" class="form-control">
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <label for="signatur" class="second-label">Signature:</label>
                                        <input type="file" id="signatur" name="evaluator_signatur" placeholder="Signatur.."
                                            class="form-control">
                                    </div>

                                    <div class="col-12 col-md-4">
                                        <label for="date" class="second-label">Date:</label>
                                        <input type="date" id="evaluator_date" name="evaluator_signatur_date"
                                            placeholder="Select Date" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-section form-section--altered total-block">
                    <label for="ClientTotalReview">Total Score:</label>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item" name="total_scoring_system" id="total-score" readonly></li>
                        <li class="breadcrumb-item">100</li>
                    </ol>
                    <input type="hidden" name="total_scoring_system" id="total_scoring_system">
                </div>
                <div class="d-flex justify-content-center">
                    <button type="submit" class="btn btn-primary" id="evaluationSubmit">Submit</button>
                    <button type="reset" class="btn btn-outlined d-none" id="evaluationCancle">Clear</button>
                </div>
        </form>

        {{-- <div id="confirmationDialog" title="Success" style="display:none;">
            <p id="confirmationMessage"></p>
        </div> --}}
        </div>


        <!-- Loader Overlay -->
        <div id="loaderOverlay"
            style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999; text-align:center; color:white; font-size:24px; padding-top:20%;">
            <span>Please wait...</span>
        </div>




        {{-- otp modal --}}
        <div id="otpModal" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Enter OTP</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="otpForm">
                            @csrf
                            <input type="text" name="otp" class="form-control" placeholder="Enter OTP" required>
                            <button type="submit" class="btn btn-primary mt-3">Verify OTP</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </body>

@endsection

</html>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
       $(document).ready(function () {
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            }
        });

        const sessionEmail = "{{ session('user_email') }}"; // or use 'otp_email' if more accurate

        $("#evaluationSubmit").submit(function (event) {
            event.preventDefault();

            const $submitBtn = $(this).find("button[type='submit']");
            $submitBtn.prop("disabled", true).text("Please wait...");

            let countdown = 5;
            let otpTimer = setInterval(() => {
                countdown--;
                if (countdown <= 0) {
                    clearInterval(otpTimer);
                    $submitBtn.prop("disabled", false).text("Resend OTP");
                } else {
                    $submitBtn.text(`Please wait... (${countdown}s)`);
                }
            }, 1000);

            // Send OTP via AJAX
            $.ajax({
                url: "{{ route('evaluation-send-otp') }}",
                type: "POST",
                data: {
                    email: sessionEmail
                },
                success: function (response) {
                    console.log("OTP Sent Response:", response);
                    if (response.success) {
                        $("#otpModal").modal("show");
                    } else {
                        alert(response.message || "Failed to send OTP!");
                        $submitBtn.prop("disabled", false).text("Save");
                    }
                },
                error: function (xhr) {
                    console.error("OTP Request Error:", xhr.responseText);
                    alert("Something went wrong! Please try again.");
                    $submitBtn.prop("disabled", false).text("Save");
                }
            });
        });

        // OTP Form Submit
        $("#otpForm").submit(function (event) {
            event.preventDefault();

            $.ajax({
                url: "{{ route('evaluation-verify-otp') }}",
                type: "POST",
                data: {
                    email: sessionEmail,
                    otp: $("input[name='otp']").val(),
                    _token: "{{ csrf_token() }}"
                },
                success: function (response) {
                    console.log("OTP Verified Response:", response);
                    if (response.success) {
                        alert("OTP Verified!");
                        $("input[name='otp']").val("");
                        $("#otpModal").modal("hide");

                          $("#loaderOverlay").show();

                        submitEvaluationForm();
                    } else {
                        alert(response.message || "Invalid OTP!");
                    }
                },
                error: function (xhr) {
                    console.error("OTP Verification Error:", xhr.responseText);
                    alert("Enter Valid OTP! Please try again.");
                }
            });
        });

        $(".close").on("click", function () {
            $("#otpModal").modal("hide");
        });

        $("#otpModal").on("hidden.bs.modal", function () {
            $("input[name='otp']").val("");
        });

        // Final form submission with duplicate check
        function submitEvaluationForm() {
            let formData = new FormData($("#evaluationSubmit")[0]);
            formData.append("email", sessionEmail);

            const empId = $("#evaluationSubmit input[name='emp_id']").val();
            const financialYear = $("#evaluationSubmit select[name='financial_year']").val(); // or 'input' if you're using text input

            $.ajax({
                url: "{{ route('check-duplicate-evaluation') }}",
                type: "POST",
                data: {
                    emp_id: empId,
                    financial_year: financialYear,
                    _token: "{{ csrf_token() }}"
                },
                success: function (res) {
                    if (res.exists) {
                        $("#loaderOverlay").hide();
                        Swal.fire({
                            icon: 'warning',
                            title: 'Duplicate Submission',
                            text: res.message
                        });
                        return;
                    }

                    // Submit the final form
                    $.ajax({
                        url: "{{ route('insert-data-evaluation') }}",
                        type: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (response) {

                              $("#loaderOverlay").hide();

                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: response.message
                                }).then(() => {
                                    $("#evaluationSubmit")[0].reset();
                                    window.location.href = response.redirect_url;
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Submission Failed',
                                    text: response.message || "Failed to submit evaluation."
                                });
                            }
                        },
                        error: function (xhr) {
                            $("#loaderOverlay").hide();
                            let errorMessage = "Something went wrong! Please try again.";

                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            } else if (xhr.responseText) {
                                try {
                                    const res = JSON.parse(xhr.responseText);
                                    if (res.message) {
                                        errorMessage = res.message;
                                    }
                                } catch (e) {
                                    console.warn("Failed to parse error JSON");
                                }
                            }

                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: errorMessage
                            });
                        }
                    });
                },
                error: function () {
                      $("#loaderOverlay").hide();
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        text: "Could not validate employee. Please try again."
                    });
                }
            });
        }
    });































    // Utility: safely parse integer from input by id, fallback 0
    function getIntValue(id) {
        const el = document.getElementById(id);
        if (!el) return 0;
        return parseInt(el.value) || 0;
    }

    // Update Quality Work total
    function qualityWorkTotalRating() {
        const total = getIntValue('qw1') + getIntValue('qw2') + getIntValue('qw3');
        document.getElementById('qualityworktotalrating').value = total;
        //   console.log('Quality Work Total:', total);
    }

    // Update Work Habits total
    function workHabitsTotalRating() {
        const total = getIntValue('wh1') + getIntValue('wh2') + getIntValue('wh3') + getIntValue('wh4');
        document.getElementById('work_habits_rating').value = total;
        //   console.log('Work Habits Total:', total);
    }

    // Update Job Knowledge total
    function jobKnowledgeTotalRating() {
        const total = getIntValue('jk1') + getIntValue('jk2') + getIntValue('jk3');
        document.getElementById('jk_total_rating').value = total;
        //   console.log('Job Knowledge Total:', total);
    }

    // Update Interpersonal total
    function interpersonalTotalRating() {
        const total = getIntValue('ir1') + getIntValue('ir2') + getIntValue('ir3') + getIntValue('ir4') + getIntValue('ir5');
        document.getElementById('ir_total_rating').value = total;
        //   console.log('Interpersonal Total:', total);
    }

    // Update Leadership total
    function leadershipTotalRating() {
        const total = getIntValue('ls1') + getIntValue('ls2') + getIntValue('ls3');
        document.getElementById('leadership_rating').value = total;
        //   console.log('Leadership Total:', total);
    }

    // Calculate and display average rating
    function calculateAverageRating() {
        const leadership = parseFloat(document.getElementById("leadership_rating").value) || 0;
        const interpersonal = parseFloat(document.getElementById("ir_total_rating").value) || 0;
        const jobKnowledge = parseFloat(document.getElementById("jk_total_rating").value) || 0;
        const workHabits = parseFloat(document.getElementById("work_habits_rating").value) || 0;
        const qualityWork = parseFloat(document.getElementById("qualityworktotalrating").value) || 0;

        const total = leadership + interpersonal + jobKnowledge + workHabits + qualityWork;
        //   console.log('Total:', total);

        const part1 = (total);
        const average = (part1 / 100) * 100;

        //   console.log('Leadership:', leadership);
        //   console.log('Interpersonal:', interpersonal);
        //   console.log('Job Knowledge:', jobKnowledge);
        //   console.log('Work Habits:', workHabits);
        //   console.log('Quality Work:', qualityWork);
        //   console.log('Average:', average);

        document.getElementById("total-score").innerText = total;
        document.getElementById("total_scoring_system").value = average.toFixed(2);
    }

    // Update all subtotal ratings then calculate average
    function updateAllTotals() {
        qualityWorkTotalRating();
        workHabitsTotalRating();
        jobKnowledgeTotalRating();
        interpersonalTotalRating();
        leadershipTotalRating();
        calculateAverageRating();
    }

    // Setup event listeners on all input fields involved to recalc totals on change/input
    function setupEventListeners() {
        const allInputIds = [
            'qw1', 'qw2', 'qw3',
            'wh1', 'wh2', 'wh3', 'wh4',
            'jk1', 'jk2', 'jk3',
            'ir1', 'ir2', 'ir3', 'ir4', 'ir5',
            'ls1', 'ls2', 'ls3'
        ];

        allInputIds.forEach(id => {
            const input = document.getElementById(id);
            if (input) {
                input.addEventListener('input', updateAllTotals);
            }
        });
    }

    // Initialize on page load
    window.addEventListener('load', () => {
        setupEventListeners();
        updateAllTotals(); // initial calculation in case inputs have default values
    });




</script>