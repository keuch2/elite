<!-- resources/views/report-config/create.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Create Report Template</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Create Report Template</h1>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('report-config.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Report Name</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>

            <h3>Select Fields</h3>
            @foreach ($fields as $section => $fieldList)
                <h4>{{ $section }}</h4>
                @foreach ($fieldList as $key => $label)
                    <div class="form-check">
                        <input type="checkbox" name="fields[]" value="{{ $key }}" id="{{ $key }}" class="form-check-input">
                        <label for="{{ $key }}" class="form-check-label">{{ $label }}</label>
                    </div>
                @endforeach
            @endforeach

            <button type="submit" class="btn btn-primary mt-3">Save Configuration</button>
        </form>

        <h2>Saved Configurations</h2>
        <ul>
            @foreach ($configs as $config)
                <li>
                    {{ $config->name }} 
                    <a href="{{ route('report-config.download', $config->id) }}" class="btn btn-sm btn-success">Download Template</a>
                </li>
            @endforeach
        </ul>
    </div>
</body>
</html>