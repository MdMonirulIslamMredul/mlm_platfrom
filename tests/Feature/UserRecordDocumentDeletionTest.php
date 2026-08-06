<?php

namespace Tests\Feature;

use App\Models\Document;
use App\Models\User;
use App\Models\UserRecord;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserRecordDocumentDeletionTest extends TestCase
{
    use RefreshDatabase;

    public function test_delete_document_returns_json_for_ajax_requests(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $userRecord = UserRecord::create([
            'name' => 'Test User',
        ]);

        $document = Document::create([
            'user_record_id' => $userRecord->id,
            'document_name' => 'sample.pdf',
            'document_path' => 'uploads/documents/sample.pdf',
            'file_type' => 'pdf',
        ]);

        $response = $this->actingAs($admin)
            ->withHeader('Accept', 'application/json')
            ->deleteJson(route('admin.user-records.delete-document', $document));

        $response->assertOk()
            ->assertJson([
                'success' => true,
            ]);

        $this->assertDatabaseMissing('documents', ['id' => $document->id]);
    }
}
