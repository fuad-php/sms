<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('isbn')->unique()->nullable();
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->string('author');
            $table->string('co_authors')->nullable();
            $table->string('publisher');
            $table->year('publication_year');
            $table->string('edition')->nullable();
            $table->string('language')->default('English');
            $table->text('description')->nullable();
            $table->text('summary')->nullable();
            $table->string('cover_image')->nullable();
            
            // Physical properties
            $table->integer('pages')->nullable();
            $table->string('binding_type')->nullable(); // hardcover, paperback, spiral, etc.
            $table->decimal('price', 10, 2)->nullable();
            $table->string('currency', 3)->default('USD');
            $table->string('dimensions')->nullable(); // e.g., "8.5 x 11 inches"
            $table->decimal('weight', 8, 2)->nullable(); // in grams
            
            // Library management
            $table->string('call_number')->unique();
            $table->string('shelf_location')->nullable();
            $table->string('rack_number')->nullable();
            $table->string('row_number')->nullable();
            $table->string('column_number')->nullable();
            
            // Inventory
            $table->integer('total_copies')->default(1);
            $table->integer('available_copies')->default(1);
            $table->integer('issued_copies')->default(0);
            $table->integer('reserved_copies')->default(0);
            $table->integer('damaged_copies')->default(0);
            $table->integer('lost_copies')->default(0);
            
            // Classification
            $table->unsignedBigInteger('category_id');
            $table->string('subject')->nullable();
            $table->string('keywords')->nullable(); // comma-separated keywords
            $table->string('tags')->nullable(); // comma-separated tags
            
            // Academic information
            $table->string('academic_level')->nullable(); // primary, secondary, university, etc.
            $table->string('curriculum')->nullable(); // CBSE, ICSE, IB, etc.
            $table->string('grade_level')->nullable(); // 1-12, undergraduate, graduate, etc.
            $table->boolean('is_textbook')->default(false);
            $table->boolean('is_reference')->default(false);
            $table->boolean('is_fiction')->default(false);
            $table->boolean('is_non_fiction')->default(true);
            
            // Digital properties
            $table->string('ebook_url')->nullable();
            $table->string('audio_url')->nullable();
            $table->boolean('has_digital_copy')->default(false);
            $table->string('digital_format')->nullable(); // PDF, EPUB, etc.
            
            // Status and availability
            $table->enum('status', ['available', 'unavailable', 'maintenance', 'retired'])->default('available');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_new_arrival')->default(false);
            $table->boolean('is_bestseller')->default(false);
            $table->boolean('is_recommended')->default(false);
            
            // Acquisition information
            $table->date('acquisition_date');
            $table->string('acquisition_source')->nullable(); // purchase, donation, exchange, etc.
            $table->decimal('acquisition_cost', 10, 2)->nullable();
            $table->string('donor_name')->nullable();
            $table->text('acquisition_notes')->nullable();
            
            // Condition and maintenance
            $table->enum('condition', ['excellent', 'good', 'fair', 'poor', 'damaged'])->default('excellent');
            $table->text('condition_notes')->nullable();
            $table->date('last_maintenance_date')->nullable();
            $table->text('maintenance_notes')->nullable();
            
            // Statistics
            $table->integer('total_issues')->default(0);
            $table->integer('total_reservations')->default(0);
            $table->integer('total_views')->default(0);
            $table->integer('total_downloads')->default(0);
            $table->decimal('average_rating', 3, 2)->nullable();
            $table->integer('rating_count')->default(0);
            
            // Metadata
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->text('notes')->nullable();
            $table->json('metadata')->nullable(); // for additional custom fields
            
            $table->timestamps();
            $table->softDeletes();
            
            // Foreign keys
            $table->foreign('category_id')->references('id')->on('book_categories')->onDelete('cascade');
            
            // Indexes
            $table->index(['title', 'author']);
            $table->index(['isbn']);
            $table->index(['call_number']);
            $table->index(['category_id']);
            $table->index(['status', 'is_active']);
            $table->index(['publication_year']);
            $table->index(['acquisition_date']);
            $table->index(['is_featured', 'is_new_arrival']);
            $table->fullText(['title', 'author', 'description', 'keywords']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};