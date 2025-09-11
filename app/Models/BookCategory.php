<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class BookCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'slug',
        'description',
        'color',
        'icon',
        'parent_id',
        'sort_order',
        'is_active',
        'is_featured',
        'notes',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get the parent category
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(BookCategory::class, 'parent_id');
    }

    /**
     * Get the child categories
     */
    public function children(): HasMany
    {
        return $this->hasMany(BookCategory::class, 'parent_id')->orderBy('sort_order');
    }

    /**
     * Get all descendants (children, grandchildren, etc.)
     */
    public function descendants(): HasMany
    {
        return $this->children()->with('descendants');
    }

    /**
     * Get the books in this category
     */
    public function books(): HasMany
    {
        return $this->hasMany(Book::class, 'category_id');
    }

    /**
     * Get active books in this category
     */
    public function activeBooks(): HasMany
    {
        return $this->hasMany(Book::class, 'category_id')
            ->where('is_active', true);
    }

    /**
     * Scope for active categories
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for featured categories
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true)
                    ->where('is_active', true);
    }

    /**
     * Scope for root categories (no parent)
     */
    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Scope for child categories
     */
    public function scopeChildren($query, $parentId)
    {
        return $query->where('parent_id', $parentId);
    }

    /**
     * Scope ordered by sort order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    /**
     * Get the full category path (e.g., "Fiction > Science Fiction")
     */
    public function getFullPathAttribute(): string
    {
        $path = [$this->name];
        $parent = $this->parent;
        
        while ($parent) {
            array_unshift($path, $parent->name);
            $parent = $parent->parent;
        }
        
        return implode(' > ', $path);
    }

    /**
     * Get the category depth level
     */
    public function getDepthAttribute(): int
    {
        $depth = 0;
        $parent = $this->parent;
        
        while ($parent) {
            $depth++;
            $parent = $parent->parent;
        }
        
        return $depth;
    }

    /**
     * Get the category breadcrumb
     */
    public function getBreadcrumbAttribute(): array
    {
        $breadcrumb = [];
        $current = $this;
        
        while ($current) {
            array_unshift($breadcrumb, [
                'id' => $current->id,
                'name' => $current->name,
                'slug' => $current->slug,
            ]);
            $current = $current->parent;
        }
        
        return $breadcrumb;
    }

    /**
     * Get the books count in this category (including subcategories)
     */
    public function getTotalBooksCountAttribute(): int
    {
        $count = $this->books()->count();
        
        foreach ($this->children as $child) {
            $count += $child->total_books_count;
        }
        
        return $count;
    }

    /**
     * Get the active books count in this category (including subcategories)
     */
    public function getActiveBooksCountAttribute(): int
    {
        $count = $this->activeBooks()->count();
        
        foreach ($this->children as $child) {
            $count += $child->active_books_count;
        }
        
        return $count;
    }

    /**
     * Get the available books count in this category (including subcategories)
     */
    public function getAvailableBooksCountAttribute(): int
    {
        $count = $this->books()->available()->count();
        
        foreach ($this->children as $child) {
            $count += $child->available_books_count;
        }
        
        return $count;
    }

    /**
     * Get the category color with fallback
     */
    public function getColorAttribute($value): string
    {
        return $value ?: '#3B82F6';
    }

    /**
     * Get the category icon with fallback
     */
    public function getIconAttribute($value): string
    {
        return $value ?: 'book-open';
    }

    /**
     * Check if category has children
     */
    public function hasChildren(): bool
    {
        return $this->children()->exists();
    }

    /**
     * Check if category is a leaf (no children)
     */
    public function isLeaf(): bool
    {
        return !$this->hasChildren();
    }

    /**
     * Check if category is root (no parent)
     */
    public function isRoot(): bool
    {
        return is_null($this->parent_id);
    }

    /**
     * Get all ancestor categories
     */
    public function getAncestors(): array
    {
        $ancestors = [];
        $parent = $this->parent;
        
        while ($parent) {
            $ancestors[] = $parent;
            $parent = $parent->parent;
        }
        
        return array_reverse($ancestors);
    }

    /**
     * Get all descendant categories (flattened)
     */
    public function getAllDescendants(): array
    {
        $descendants = [];
        
        foreach ($this->children as $child) {
            $descendants[] = $child;
            $descendants = array_merge($descendants, $child->getAllDescendants());
        }
        
        return $descendants;
    }

    /**
     * Get category tree structure
     */
    public function getTreeStructure(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'slug' => $this->slug,
            'description' => $this->description,
            'color' => $this->color,
            'icon' => $this->icon,
            'sort_order' => $this->sort_order,
            'is_active' => $this->is_active,
            'is_featured' => $this->is_featured,
            'books_count' => $this->books()->count(),
            'active_books_count' => $this->activeBooks()->count(),
            'children' => $this->children->map->getTreeStructure(),
        ];
    }

    /**
     * Boot method to handle model events
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (!$category->slug) {
                $category->slug = Str::slug($category->name);
            }
            if (!$category->code) {
                $category->code = strtoupper(Str::slug($category->name, '_'));
            }
        });

        static::updating(function ($category) {
            if ($category->isDirty('name') && !$category->isDirty('slug')) {
                $category->slug = Str::slug($category->name);
            }
        });
    }
}