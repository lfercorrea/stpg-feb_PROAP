<!DOCTYPE html>
<html>
<head>
    <title>Importar CSV</title>
</head>
<body>

    @if ($message = Session::get('success'))
        <div>
            <strong>{{ $message }}</strong>
        </div>
    @endif

    <form action="{{ route('import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div>
            <label for="file">Arquivo CSV</label>
            <input type="file" name="file" required>
        </div>
        <div>
            <button type="submit">Importar</button>
        </div>
    </form>

</body>
</html>
