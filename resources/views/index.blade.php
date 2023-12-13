<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Import and Encrypt Data</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
</head>
<body>

    <div class="container mt-4">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        <div class="row">
            <div class="col-md-6">
                <form action="{{ route('import-and-encrypt') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="file" class="form-label">Upload File:</label>
                        <input type="file" class="form-control" name="file" accept=".xls, .xlsx" required>
                    </div>
        
                    <button type="submit" class="btn btn-primary">Import and Encrypt Data</button>
                </form>
            </div>
            <div class="col-md-6">
                <form action="{{ route('change-encryption-key') }}" method="post">
                    @csrf
                    <div class="mb-3">
                        <label for="encryption_key" class="form-label">Encryption Key:</label>
                        <input type="password" class="form-control" name="encryption_key" value="" required>
                        @error('encryption_key')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
        
                    <button type="submit" class="btn btn-primary">Update Encryption Key</button>
                </form>
            </div>
        </div>


        <br>

        <table id="dataTable" class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Gender</th>
                    <th>Date of Birth</th>
                    <th>Timestamp</th>
                </tr>
            </thead>
            <tbody>
                <!-- Loop through your data and display rows -->
                @foreach($data as $row)
                    <tr>
                        <td>{{ $row->name }}</td>
                        <td>{{ $row->email }}</td>
                        <td>{{ $row->phone_number }}</td>
                        <td>{{ $row->gender }}</td>
                        <td>{{ $row->dob }}</td>
                        <td>{{ $row->created_at }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready( function () {
            $('#dataTable').DataTable();
        });
    </script>

</body>
</html>
