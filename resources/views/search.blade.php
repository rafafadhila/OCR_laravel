@extends('layouts.main')
@section('content')
    <div class="container mt-5">
        <h2 class="text-center mb-4">History OCR File Upload</h2>

        <!-- The form to upload files -->
        <form action="{{ route('ocr.search') }}" method="GET" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="text" class="form-label">Search Uploaded File:</label>
                <input type="text" name="image" id="image" accept=".png, .jpeg, .jpg, .pdf" class="form-control"
                    required>
            </div>

            <div class="text-left">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
        </form>

        <div class="text-center">
            <a href="/" class="btn btn-success mt-4">Upload and Scan</a>
        </div>

        <!-- Display OCR result -->
        <div id="ocr-result" class="mt-4">
            <h5>History Uploaded:</h5>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th scope="col">File Name</th>
                        {{-- <th scope="col">File Path</th> --}}
                        <th scope="col">Extracted Text</th>
                        <th scope="col">Page Number</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($results as $result)
                        <tr>
                            <td>{{ $result->file_name }}</td>
                            {{-- <td>{{ $result->file_path }}</td> --}}
                            <td>{{ Str::limit($result->extracted_text, 100) }}</td> <!-- Limit text to 100 chars -->
                            <td>{{ $result->page_number }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
