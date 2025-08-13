<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class FileUploadController extends Controller
{
    /**
     * Upload course image
     */
    public function uploadCourseImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $image = $request->file('image');
            $filename = $this->generateFileName($image, 'course');
            
            // Resize and optimize image
            $processedImage = Image::make($image)
                ->fit(800, 450, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })
                ->encode('jpg', 85);

            // Store the processed image
            $path = "courses/{$filename}";
            Storage::disk('public')->put($path, $processedImage);

            return response()->json([
                'success' => true,
                'path' => $path,
                'url' => Storage::disk('public')->url($path),
                'message' => 'Course image uploaded successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload image: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload payment proof
     */
    public function uploadPaymentProof(Request $request)
    {
        $request->validate([
            'payment_proof' => 'required|file|mimes:jpeg,png,jpg,gif,pdf|max:5120', // 5MB max
            'registration_id' => 'required|exists:course_registrations,id',
        ]);

        try {
            $file = $request->file('payment_proof');
            $filename = $this->generateFileName($file, 'payment');
            
            $path = $file->storeAs('payment-proofs', $filename, 'public');

            // Here you would typically create a Payment record
            // Payment::create([
            //     'registration_id' => $request->registration_id,
            //     'payment_proof' => $path,
            //     'status' => 'pending',
            //     'payment_date' => now(),
            // ]);

            return response()->json([
                'success' => true,
                'path' => $path,
                'url' => Storage::disk('public')->url($path),
                'message' => 'Payment proof uploaded successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload payment proof: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload profile photo
     */
    public function uploadProfilePhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $photo = $request->file('photo');
            $filename = $this->generateFileName($photo, 'profile');
            
            // Resize and optimize image for profile
            $processedImage = Image::make($photo)
                ->fit(300, 300, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })
                ->encode('jpg', 90);

            // Store the processed image
            $path = "profiles/{$filename}";
            Storage::disk('public')->put($path, $processedImage);

            return response()->json([
                'success' => true,
                'path' => $path,
                'url' => Storage::disk('public')->url($path),
                'message' => 'Profile photo uploaded successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload profile photo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload course materials
     */
    public function uploadCourseMaterial(Request $request)
    {
        $request->validate([
            'material' => 'required|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx|max:10240', // 10MB max
            'class_id' => 'required|exists:classes,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'day' => 'required|integer|min:1',
        ]);

        try {
            $file = $request->file('material');
            $filename = $this->generateFileName($file, 'material');
            
            $path = $file->storeAs('course-materials', $filename, 'public');
            $size = $file->getSize();

            // Here you would typically create a CourseMaterial record
            // CourseMaterial::create([
            //     'course_schedule_id' => $request->class_id,
            //     'title' => $request->title,
            //     'description' => $request->description,
            //     'file_url' => $path,
            //     'day' => $request->day,
            //     'size' => $size,
            //     'is_google_drive' => false,
            // ]);

            return response()->json([
                'success' => true,
                'path' => $path,
                'url' => Storage::disk('public')->url($path),
                'size' => $this->formatFileSize($size),
                'message' => 'Course material uploaded successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload course material: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload certificate template or file
     */
    public function uploadCertificate(Request $request)
    {
        $request->validate([
            'certificate' => 'required|file|mimes:pdf|max:5120', // 5MB max
            'type' => 'required|in:template,issued',
        ]);

        try {
            $file = $request->file('certificate');
            $type = $request->input('type');
            $filename = $this->generateFileName($file, "certificate_{$type}");
            
            $directory = $type === 'template' ? 'certificate-templates' : 'certificates';
            $path = $file->storeAs($directory, $filename, 'public');

            return response()->json([
                'success' => true,
                'path' => $path,
                'url' => Storage::disk('public')->url($path),
                'message' => ucfirst($type) . ' certificate uploaded successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload certificate: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete uploaded file
     */
    public function deleteFile(Request $request)
    {
        $request->validate([
            'path' => 'required|string',
        ]);

        try {
            $path = $request->input('path');
            
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
                
                return response()->json([
                    'success' => true,
                    'message' => 'File deleted successfully'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'File not found'
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete file: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get file information
     */
    public function getFileInfo(Request $request)
    {
        $request->validate([
            'path' => 'required|string',
        ]);

        try {
            $path = $request->input('path');
            
            if (!Storage::disk('public')->exists($path)) {
                return response()->json([
                    'success' => false,
                    'message' => 'File not found'
                ], 404);
            }

            $size = Storage::disk('public')->size($path);
            $lastModified = Storage::disk('public')->lastModified($path);
            $url = Storage::disk('public')->url($path);

            return response()->json([
                'success' => true,
                'data' => [
                    'path' => $path,
                    'url' => $url,
                    'size' => $size,
                    'size_formatted' => $this->formatFileSize($size),
                    'last_modified' => date('Y-m-d H:i:s', $lastModified),
                    'mime_type' => Storage::disk('public')->mimeType($path),
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get file info: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate unique filename
     */
    private function generateFileName($file, $prefix = '')
    {
        $extension = $file->getClientOriginalExtension();
        $timestamp = now()->format('YmdHis');
        $random = Str::random(8);
        
        return $prefix ? "{$prefix}_{$timestamp}_{$random}.{$extension}" : "{$timestamp}_{$random}.{$extension}";
    }

    /**
     * Format file size in human readable format
     */
    private function formatFileSize($bytes, $precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');

        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }

    /**
     * Validate file type and size based on category
     */
    private function validateFileUpload($file, $category)
    {
        $rules = [
            'course_image' => ['image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'payment_proof' => ['mimes:jpeg,png,jpg,gif,pdf', 'max:5120'],
            'profile_photo' => ['image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'course_material' => ['mimes:pdf,doc,docx,ppt,pptx,xls,xlsx', 'max:10240'],
            'certificate' => ['mimes:pdf', 'max:5120'],
        ];

        return isset($rules[$category]) ? $rules[$category] : ['max:5120'];
    }
} 