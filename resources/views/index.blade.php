<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OCR File Upload</title>
</head>
<body>
    <!-- The form to upload files -->
    <form action="{{ route('ocr.image') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div>
            <label for="image">Select Image or PDF:</label>
            <input type="file" name="image" id="image" accept="image/*,application/pdf" required>
        </div>

        <div>
            <button type="submit">Upload and Scan</button>
        </div>
    </form>

    <!-- Display OCR result (optional, can be added with JavaScript) -->
    <div id="ocr-result" style="margin-top: 20px;">
        <pre id="ocr-text"></pre>
    </div>

    <!-- JavaScript to display the result (optional) -->
    <script>
        // If the response is available after the form submission, you can use AJAX to display it
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
</body>
</html>
