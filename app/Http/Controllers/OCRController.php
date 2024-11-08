<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Alimranahmed\LaraOCR\Facades\OCR;
use App\Models\OcrResult;
use Spatie\PdfToImage\Pdf; // Import the Pdf class

class OCRController extends Controller
{
    public function ocrImage(Request $request)
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
                $ocrText .= $pageOcrText . "\n"; // Concatenate OCR text with line breaks between pages
        
                // Delete the temporary image file
                unlink($imagePath);
            }
        
            // Delete the temporary PDF file
            Storage::delete($filePath);
        } else {
            // If it's an image, use OCR directly
            $ocrText = OCR::scan(storage_path('app/' . $filePath));

            // Delete the temporary image file
            Storage::delete($filePath);
        }

        OcrResult::create([
            'file_name' => $fileName, // This path is only for reference; the file will be deleted
            'file_path' => $filePath, // This path is only for reference; the file will be deleted
            'extracted_text' => $ocrText,
        ]);

        // Return the OCR text in the response
        return response()->json(['text' => $ocrText]);
    }
}
