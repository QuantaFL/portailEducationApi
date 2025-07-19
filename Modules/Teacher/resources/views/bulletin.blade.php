
<!DOCTYPE html>
<html>
<head>
    <title>Bulletin de Notes</title>
    <style>
        body {
            font-family: sans-serif;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .student-info {
            margin-bottom: 20px;
        }
        .student-info table {
            width: 100%;
            border-collapse: collapse;
        }
        .student-info th, .student-info td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        .subjects table {
            width: 100%;
            border-collapse: collapse;
        }
        .subjects th, .subjects td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .subjects th {
            background-color: #f2f2f2;
        }
        .footer {
            margin-top: 20px;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Bulletin de Notes</h1>
    </div>

    <div class="student-info">
        <table>
            <tr>
                <th>Nom & Prénom(s)</th>
                <td>{{ $student->user->first_name ?? '' }} {{ $student->user->last_name ?? '' }}</td>
            </tr>
            <tr>
                <th>Matricule</th>
                <td>{{ $student->student_id_number }}</td>
            </tr>
            <tr>
                <th>Date de Naissance</th>
                <td>{{ $student->user->date_of_birth }}</td>
            </tr>
            <tr>
                <th>Classe</th>
                <td>{{ $class->name }}</td>
            </tr>
            <tr>
                <th>Période</th>
                <td>{{ $period }}</td>
            </tr>
        </table>
    </div>

    <div class="subjects">
        <table>
            <thead>
                <tr>
                    <th>Matière</th>
                    <th>Coefficient</th>
                    <th>Moyenne</th>
                </tr>
            </thead>
            <tbody>
            @foreach($subjects as $subjectName => $data)
                <tr>
                    <td>{{ $subjectName }}</td>
                    <td>{{ $data['coefficient'] }}</td>
                    <td>{{ number_format($data['average'], 2, ',', ' ') }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p><strong>Moyenne Générale :</strong> {{ $general_average }}</p>
        <p><strong>Rang :</strong> {{ $rank }}</p>
        <p><strong>Appréciation :</strong> {{ $appreciation }}</p>
        <p><strong>Mention :</strong> {{ $mention }}</p>
    </div>
</body>
</html>
