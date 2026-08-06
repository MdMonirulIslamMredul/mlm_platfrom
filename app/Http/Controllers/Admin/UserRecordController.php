<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserRecord;
use App\Models\Document;
use Illuminate\Http\Request;

class UserRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userRecords = UserRecord::with('documents')->paginate(10);
        return view('admin.user-records.index', compact('userRecords'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.user-records.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'passport' => 'nullable|string|max:255',
            'ircc' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'nid_number' => 'nullable|string|max:255',
            'father_name' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'user_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'documents.*' => 'nullable|mimes:pdf,jpeg,png,jpg,gif,doc,docx|max:5120',
        ]);

        // Handle image upload to public folder
        if ($request->hasFile('user_image')) {
            $image = $request->file('user_image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/user_records'), $imageName);
            $validated['user_image'] = 'uploads/user_records/' . $imageName;
        }

        $userRecord = UserRecord::create($validated);

        // Handle multiple documents to public folder
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $document) {
                $docName = time() . '_' . uniqid() . '.' . $document->getClientOriginalExtension();
                $document->move(public_path('uploads/documents'), $docName);
                Document::create([
                    'user_record_id' => $userRecord->id,
                    'document_name' => $document->getClientOriginalName(),
                    'document_path' => 'uploads/documents/' . $docName,
                    'file_type' => $document->getClientOriginalExtension(),
                ]);
            }
        }

        return redirect()->route('admin.user-records.index')
            ->with('success', 'User record created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(UserRecord $userRecord)
    {
        return view('admin.user-records.show', compact('userRecord'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UserRecord $userRecord)
    {
        return view('admin.user-records.edit', compact('userRecord'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UserRecord $userRecord)
    {
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'passport' => 'nullable|string|max:255',
            'ircc' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'nid_number' => 'nullable|string|max:255',
            'father_name' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'user_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'documents.*' => 'nullable|mimes:pdf,jpeg,png,jpg,gif,doc,docx|max:5120',
        ]);

        // Handle image upload to public folder
        if ($request->hasFile('user_image')) {
            // Delete old image
            if ($userRecord->user_image && file_exists(public_path($userRecord->user_image))) {
                unlink(public_path($userRecord->user_image));
            }
            $image = $request->file('user_image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/user_records'), $imageName);
            $validated['user_image'] = 'uploads/user_records/' . $imageName;
        }

        $userRecord->update($validated);

        // Handle multiple documents to public folder
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $document) {
                $docName = time() . '_' . uniqid() . '.' . $document->getClientOriginalExtension();
                $document->move(public_path('uploads/documents'), $docName);
                Document::create([
                    'user_record_id' => $userRecord->id,
                    'document_name' => $document->getClientOriginalName(),
                    'document_path' => 'uploads/documents/' . $docName,
                    'file_type' => $document->getClientOriginalExtension(),
                ]);
            }
        }

        return redirect()->route('admin.user-records.show', $userRecord)
            ->with('success', 'User record updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserRecord $userRecord)
    {
        // Delete image from public folder
        if ($userRecord->user_image && file_exists(public_path($userRecord->user_image))) {
            unlink(public_path($userRecord->user_image));
        }

        // Delete associated documents from public folder
        foreach ($userRecord->documents as $document) {
            if (file_exists(public_path($document->document_path))) {
                unlink(public_path($document->document_path));
            }
            $document->delete();
        }

        $userRecord->delete();

        return redirect()->route('admin.user-records.index')
            ->with('success', 'User record deleted successfully!');
    }

    /**
     * Delete a specific document
     */
    public function deleteDocument(Request $request, $documentId)
    {
        $document = Document::findOrFail($documentId);

        // Delete document from public folder
        if ($document->document_path && file_exists(public_path($document->document_path))) {
            unlink(public_path($document->document_path));
        }
        $document->delete();

        if ($request->expectsJson() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Document deleted successfully!',
            ]);
        }

        return redirect()->back()->with('success', 'Document deleted successfully!');
    }
}
