<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Book extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'isbn', 'title', 'subtitle', 'author', 'co_authors', 'publisher',
        'publication_year', 'edition', 'language', 'description', 'summary',
        'cover_image', 'pages', 'binding_type', 'price', 'currency',
        'dimensions', 'weight', 'call_number', 'shelf_location', 'rack_number',
        'row_number', 'column_number', 'total_copies', 'available_copies',
        'issued_copies', 'reserved_copies', 'damaged_copies', 'lost_copies',
        'category_id', 'subject', 'keywords', 'tags', 'academic_level',
        'curriculum', 'grade_level', 'is_textbook', 'is_reference',
        'is_fiction', 'is_non_fiction', 'ebook_url', 'audio_url',
        'has_digital_copy', 'digital_format', 'status', 'is_active',
        'is_featured', 'is_new_arrival', 'is_bestseller', 'is_recommended',
        'acquisition_date', 'acquisition_source', 'acquisition_cost',
        'donor_name', 'acquisition_notes', 'condition', 'condition_notes',
        'last_maintenance_date', 'maintenance_notes', 'total_issues',
        'total_reservations', 'total_views', 'total_downloads',
        'average_rating', 'rating_count', 'created_by', 'updated_by',
        'notes', 'metadata'
    ];

    protected $casts = [
        'publication_year' => 'integer',
        'price' => 'decimal:2',
        'acquisition_cost' => 'decimal:2',
        'acquisition_date' => 'date',
        'last_maintenance_date' => 'date',
        'average_rating' => 'decimal:2',
        'weight' => 'decimal:2',
        'is_textbook' => 'boolean',
        'is_reference' => 'boolean',
        'is_fiction' => 'boolean',
        'is_non_fiction' => 'boolean',
        'has_digital_copy' => 'boolean',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'is_new_arrival' => 'boolean',
        'is_bestseller' => 'boolean',
        'is_recommended' => 'boolean',
        'metadata' => 'array',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(BookCategory::class, 'category_id');
    }

    public function issues(): HasMany
    {
        return $this->hasMany(BookIssue::class);
    }

    public function activeIssues(): HasMany
    {
        return $this->hasMany(BookIssue::class)->where('status', 'issued');
    }

    public function overdueIssues(): HasMany
    {
        return $this->hasMany(BookIssue::class)
            ->where('status', 'overdue')
            ->where('due_date', '<', Carbon::today());
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(BookIssue::class)
            ->where('is_reservation', true)
            ->where('reservation_fulfilled', false);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeAvailable($query)
    {
        return $query->where('available_copies', '>', 0)
                    ->where('status', 'available')
                    ->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true)->where('is_active', true);
    }

    public function scopeNewArrivals($query)
    {
        return $query->where('is_new_arrival', true)->where('is_active', true);
    }

    public function scopeBestsellers($query)
    {
        return $query->where('is_bestseller', true)->where('is_active', true);
    }

    public function scopeRecommended($query)
    {
        return $query->where('is_recommended', true)->where('is_active', true);
    }

    public function scopeTextbooks($query)
    {
        return $query->where('is_textbook', true)->where('is_active', true);
    }

    public function scopeReference($query)
    {
        return $query->where('is_reference', true)->where('is_active', true);
    }

    public function scopeFiction($query)
    {
        return $query->where('is_fiction', true)->where('is_active', true);
    }

    public function scopeNonFiction($query)
    {
        return $query->where('is_non_fiction', true)->where('is_active', true);
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeByAcademicLevel($query, $level)
    {
        return $query->where('academic_level', $level);
    }

    public function scopeByGradeLevel($query, $grade)
    {
        return $query->where('grade_level', $grade);
    }

    public function scopeByCurriculum($query, $curriculum)
    {
        return $query->where('curriculum', $curriculum);
    }

    public function scopeByCondition($query, $condition)
    {
        return $query->where('condition', $condition);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByPublicationYear($query, $year)
    {
        return $query->where('publication_year', $year);
    }

    public function scopeByAuthor($query, $author)
    {
        return $query->where('author', 'like', "%{$author}%");
    }

    public function scopeByPublisher($query, $publisher)
    {
        return $query->where('publisher', 'like', "%{$publisher}%");
    }

    public function scopeWithDigitalCopy($query)
    {
        return $query->where('has_digital_copy', true);
    }

    public function scopeRecentlyAdded($query, $days = 30)
    {
        return $query->where('created_at', '>=', Carbon::now()->subDays($days));
    }

    public function scopePopular($query, $limit = 10)
    {
        return $query->orderBy('total_issues', 'desc')->limit($limit);
    }

    public function scopeSearch($query, $searchTerm)
    {
        return $query->where(function($q) use ($searchTerm) {
            $q->where('title', 'like', "%{$searchTerm}%")
              ->orWhere('author', 'like', "%{$searchTerm}%")
              ->orWhere('isbn', 'like', "%{$searchTerm}%")
              ->orWhere('publisher', 'like', "%{$searchTerm}%")
              ->orWhere('call_number', 'like', "%{$searchTerm}%")
              ->orWhere('description', 'like', "%{$searchTerm}%")
              ->orWhere('keywords', 'like', "%{$searchTerm}%")
              ->orWhereHas('category', function($categoryQuery) use ($searchTerm) {
                  $categoryQuery->where('name', 'like', "%{$searchTerm}%");
              });
        });
    }

    public function scopeByAvailability($query, $availability)
    {
        switch ($availability) {
            case 'available':
                return $query->where('available_copies', '>', 0);
            case 'unavailable':
                return $query->where('available_copies', '=', 0);
            case 'overdue':
                return $query->whereHas('issues', function($q) {
                    $q->where('status', 'overdue');
                });
            case 'reserved':
                return $query->where('reserved_copies', '>', 0);
            default:
                return $query;
        }
    }

    public function scopeByYearRange($query, $fromYear, $toYear = null)
    {
        $query->where('publication_year', '>=', $fromYear);
        if ($toYear) {
            $query->where('publication_year', '<=', $toYear);
        }
        return $query;
    }

    public function scopeByPriceRange($query, $minPrice, $maxPrice = null)
    {
        $query->where('price', '>=', $minPrice);
        if ($maxPrice) {
            $query->where('price', '<=', $maxPrice);
        }
        return $query;
    }

    public function scopeByRating($query, $minRating)
    {
        return $query->where('average_rating', '>=', $minRating)
                    ->where('rating_count', '>', 0);
    }    

    public function scopeWithIssues($query)
    {
        return $query->where('total_issues', '>', 0);
    }

    public function scopeWithoutIssues($query)
    {
        return $query->where('total_issues', '=', 0);
    }

    public function scopeHighlyRated($query, $minRating = 4.0)
    {
        return $query->where('average_rating', '>=', $minRating)
                    ->where('rating_count', '>', 0);
    }

    // Accessors
    public function getAvailabilityStatusAttribute(): string
    {
        if ($this->available_copies > 0) {
            return 'available';
        } elseif ($this->reserved_copies > 0) {
            return 'reserved';
        } elseif ($this->issued_copies >= $this->total_copies) {
            return 'all_issued';
        } else {
            return 'unavailable';
        }
    }

    public function getAvailabilityStatusBadgeClassAttribute(): string
    {
        return match($this->availability_status) {
            'available' => 'bg-green-100 text-green-800',
            'reserved' => 'bg-yellow-100 text-yellow-800',
            'all_issued' => 'bg-blue-100 text-blue-800',
            'unavailable' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getConditionBadgeClassAttribute(): string
    {
        return match($this->condition) {
            'excellent' => 'bg-green-100 text-green-800',
            'good' => 'bg-blue-100 text-blue-800',
            'fair' => 'bg-yellow-100 text-yellow-800',
            'poor' => 'bg-orange-100 text-orange-800',
            'damaged' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            'available' => 'bg-green-100 text-green-800',
            'unavailable' => 'bg-red-100 text-red-800',
            'maintenance' => 'bg-yellow-100 text-yellow-800',
            'retired' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getBookTypeDisplayAttribute(): string
    {
        $types = [];
        if ($this->is_textbook) $types[] = 'Textbook';
        if ($this->is_reference) $types[] = 'Reference';
        if ($this->is_fiction) $types[] = 'Fiction';
        if ($this->is_non_fiction) $types[] = 'Non-Fiction';
        
        return implode(', ', $types) ?: 'General';
    }

    public function getFullTitleAttribute(): string
    {
        return $this->subtitle ? "{$this->title}: {$this->subtitle}" : $this->title;
    }

    public function getAuthorDisplayAttribute(): string
    {
        return $this->co_authors ? "{$this->author}, {$this->co_authors}" : $this->author;
    }

    public function getShelfLocationDisplayAttribute(): string
    {
        $location = $this->call_number;
        if ($this->shelf_location) {
            $location .= " | {$this->shelf_location}";
        }
        if ($this->rack_number) {
            $location .= " | Rack: {$this->rack_number}";
        }
        if ($this->row_number) {
            $location .= " | Row: {$this->row_number}";
        }
        if ($this->column_number) {
            $location .= " | Col: {$this->column_number}";
        }
        return $location;
    }

    public function getAcquisitionCostDisplayAttribute(): string
    {
        if (!$this->acquisition_cost) return 'N/A';
        return $this->currency . ' ' . number_format($this->acquisition_cost, 2);
    }

    public function getPriceDisplayAttribute(): string
    {
        if (!$this->price) return 'N/A';
        return $this->currency . ' ' . number_format($this->price, 2);
    }

    public function getPublicationYearDisplayAttribute(): string
    {
        return $this->publication_year ? (string) $this->publication_year : 'N/A';
    }

    public function getRatingDisplayAttribute(): string
    {
        if (!$this->average_rating || $this->rating_count === 0) {
            return 'No ratings';
        }
        return number_format($this->average_rating, 1) . ' (' . $this->rating_count . ' ratings)';
    }

    public function getIssueRateAttribute(): float
    {
        if ($this->total_copies === 0) return 0;
        return round($this->total_issues / $this->total_copies, 2);
    }

    // Helper methods
    public function isAvailableForIssue(): bool
    {
        return $this->available_copies > 0 && 
               $this->status === 'available' && 
               $this->is_active;
    }

    public function canBeReserved(): bool
    {
        return $this->available_copies === 0 && 
               $this->status === 'available' && 
               $this->is_active;
    }

    public function hasOverdueIssues(): bool
    {
        return $this->overdueIssues()->exists();
    }

    public function getOverdueIssuesCountAttribute(): int
    {
        return $this->overdueIssues()->count();
    }

    public function updateAvailabilityCounts(): void
    {
        $this->issued_copies = $this->activeIssues()->count();
        $this->reserved_copies = $this->reservations()->count();
        $this->available_copies = $this->total_copies - $this->issued_copies - $this->damaged_copies - $this->lost_copies;
        $this->save();
    }

    public function incrementViewCount(): void
    {
        $this->increment('total_views');
    }

    public function incrementDownloadCount(): void
    {
        $this->increment('total_downloads');
    }

    public function updateRating(float $rating): void
    {
        $this->rating_count++;
        $this->average_rating = (($this->average_rating * ($this->rating_count - 1)) + $rating) / $this->rating_count;
        $this->save();
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($book) {
            if (!$book->call_number) {
                $book->call_number = 'BK-' . str_pad(Book::max('id') + 1, 6, '0', STR_PAD_LEFT);
            }
        });

        static::saving(function ($book) {
            $book->available_copies = $book->total_copies - $book->issued_copies - $book->damaged_copies - $book->lost_copies;
        });
    }
}