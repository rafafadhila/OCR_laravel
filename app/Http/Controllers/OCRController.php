<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Alimranahmed\LaraOCR\Facades\OCR;
use App\Models\OcrResult;
use Spatie\PdfToImage\Pdf; // Import the Pdf class

class OCRController extends Controller
{
    public function store(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'image' => 'required|file|mimes:jpeg,png,jpg,gif,svg,pdf',
        ]);

        // Get the uploaded file
        $file = $request->file('image');

        // Store the uploaded file temporarily
        $filePath = $file->store('temp_files');

        $fileName = $file->getClientOriginalName();

        $ocrText = '';

        // Check if the file is a PDF
        if ($file->getClientOriginalExtension() === 'pdf') {
            $pdf = new Pdf(storage_path('app/' . $filePath));
            $numberOfPages = $pdf->pageCount();
        
            // Loop through each page of the PDF
            for ($page = 1; $page <= $numberOfPages; $page++) {
                // Set the path for the temporary image for each page
                $imagePath = storage_path("app/public/pdf_page_{$page}.jpg");
        
                // Convert each page to an image
                $pdf->selectPage($page)->save($imagePath);
        
                // Use OCR to extract text from the image
                $pageOcrText = OCR::scan($imagePath);  
                $ocrText .= $pageOcrText . "\f"; // Concatenate OCR text with line breaks between pages

                $pages = explode("\f", $ocrText);
        
                // Delete the temporary image file
                unlink($imagePath);
            }
        
            // Delete the temporary PDF file
            Storage::delete($filePath);
        } else {
            // If it's an image, use OCR directly
            $ocrText = OCR::scan(storage_path('app/' . $filePath));

            $pages = explode("\f", $ocrText);

            // Delete the temporary image file
            Storage::delete($filePath);
        }

        foreach ($pages as $pageNumber => $pageText) { 
            if (!empty(trim($pageText))) { // Skip empty parts
                OcrResult::create([
                    'file_name' => $fileName,
                    'file_path' => $filePath,
                    'page_number' => $pageNumber + 1,       
                    'extracted_text' => $pageText,
                ]);
            }
        }

        // Return the OCR text in the response
        return response()->json(['text' => $ocrText]);
    }
    
    public function show(Request $request)
    {
         // Check if there's a search term in the request
    $searchTerm = $request->input('image');

    // If there's a search term, filter the results by file name, otherwise get all results
    if ($searchTerm) {
        $results = OcrResult::where('file_name', 'like', '%' . $searchTerm . '%')->orWhere('extracted_text', 'like', '%' . $searchTerm . '%')->latest()->get();
    } else {
        $results = OcrResult::latest()->get();
    }

        return view('search', compact('results')); // Pass results to the view
    }
}
