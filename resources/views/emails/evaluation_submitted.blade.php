<!DOCTYPE html>
<html>
<head>
    <title>Evaluation Submitted</title>
</head>
<body>
    <h2>New Evaluation Submitted</h2>
    <p><strong>Employee Name:</strong> {{ $evaluationData['employee_name'] }}</p>
    <p><strong>Employee ID:</strong> {{ $evaluationData['emp_id'] }}</p>
    {{-- <p><strong>Department:</strong> {{ $evaluationData['designation'] }}</p> --}}
    <p><strong>Designation:</strong> {{ $evaluationData['designation'] }}</p>
    <p><strong>Submitted By:</strong> {{ $evaluationData['evalutors_name'] }}</p>

    {{-- <p>Please log in to the system to view the full evaluation details.</p> --}}
    <p>Please fill your review for the employee.</p>
</body>
</html>
