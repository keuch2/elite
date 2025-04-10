<!-- resources/views/import.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Import Athletes</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Upload Athlete Data</h1>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form action="{{ route('import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="file">Select Excel File</label>
                <input type="file" name="file" id="file" class="form-control" accept=".xlsx, .xls">
            </div>
            <button type="submit" class="btn btn-primary mt-3">Upload</button>
        </form>
    </div>
</body>
</html>