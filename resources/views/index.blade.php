@extends('layouts.main')
@section('content')
    <div class="container mt-5">
        <h2 class="text-center mb-4">OCR File Upload</h2>

        <!-- The form to upload files -->
        <form action="{{ route('ocr.image') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="image" class="form-label">Select Image or PDF:</label>
                <input type="file" name="image" id="image" accept=".png, .jpeg, .jpg, .pdf" class="form-control"
                    required>
            </div>

            <div class="text-left">
                <button type="submit" class="btn btn-primary">Upload and Scan</button>
            </div>
        </form>
        <div class="text-center">
            <a href="/search" class="btn btn-success mt-4">History and Uploaded File</a>
        </div>

        <!-- Display OCR result -->
        <div id="ocr-result" class="mt-4">
            <h5>OCR Result:</h5>
            <pre id="ocr-text" class="border p-3" style="white-space: pre-wrap;"></pre>
        </div>
    </div>

    <!-- JavaScript to display the result (optional) -->
    <script>
        document.querySelector('form').onsubmit = function(e) {
            e.preventDefault();

            let formData = new FormData(this);
            fetch("{{ route('ocr.image') }}", {
                    method: "POST",
                    body: formData,
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    document.getElementById("ocr-text").textContent = data.text;
                })
                .catch(error => console.error('Error:', error));
        };
    </script>
@endsection
