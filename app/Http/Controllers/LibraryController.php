<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\BookCategory;
use App\Models\BookIssue;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Staff;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class LibraryController extends Controller
{
    /**
     * Display the library dashboard
     */
    public function dashboard()
    {
        $stats = [
            'total_books' => Book::count(),
            'available_books' => Book::available()->count(),
            'issued_books' => BookIssue::active()->count(),
            'overdue_books' => BookIssue::overdue()->count(),
            'total_categories' => BookCategory::active()->count(),
            'total_issues_this_month' => BookIssue::whereMonth('issue_date', Carbon::now()->month)->count(),
            'total_returns_this_month' => BookIssue::whereMonth('return_date', Carbon::now()->month)->count(),
        ];

        $recentIssues = BookIssue::with(['book', 'student.user', 'teacher.user', 'staff.user'])
            ->latest('issue_date')
            ->limit(10)
            ->get();

        $popularBooks = Book::popular(10)->get();
        $overdueBooks = BookIssue::overdue()
            ->with(['book', 'student.user', 'teacher.user', 'staff.user'])
            ->get();

        return view('library.dashboard', compact(
            'stats',
            'recentIssues',
            'popularBooks',
            'overdueBooks'
        ));
    }

    /**
     * Display a listing of books
     */
    public function index(Request $request)
    {
        $query = Book::with('category');

        // Advanced search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%")
                  ->orWhere('isbn', 'like', "%{$search}%")
                  ->orWhere('publisher', 'like', "%{$search}%")
                  ->orWhere('call_number', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('keywords', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by book type
        if ($request->filled('book_type')) {
            $query->where('book_type', $request->book_type);
        }

        // Filter by availability
        if ($request->filled('availability')) {
            switch ($request->availability) {
                case 'available':
                    $query->where('available_copies', '>', 0);
                    break;
                case 'unavailable':
                    $query->where('available_copies', '=', 0);
                    break;
                case 'overdue':
                    $query->whereHas('issues', function($q) {
                        $q->where('status', 'overdue');
                    });
                    break;
            }
        }

        // Filter by condition
        if ($request->filled('condition')) {
            $query->where('condition', $request->condition);
        }

        // Filter by publication year range
        if ($request->filled('year_from')) {
            $query->where('publication_year', '>=', $request->year_from);
        }
        if ($request->filled('year_to')) {
            $query->where('publication_year', '<=', $request->year_to);
        }

        // Filter by price range
        if ($request->filled('price_from')) {
            $query->where('price', '>=', $request->price_from);
        }
        if ($request->filled('price_to')) {
            $query->where('price', '<=', $request->price_to);
        }

        // Filter by featured books
        if ($request->filled('featured')) {
            $query->where('is_featured', true);
        }

        // Filter by new arrivals
        if ($request->filled('new_arrivals')) {
            $query->where('is_new_arrival', true);
        }

        // Sort options
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        $allowedSortFields = ['title', 'author', 'publication_year', 'created_at', 'total_issues', 'average_rating'];
        if (in_array($sortBy, $allowedSortFields)) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // Pagination
        $perPage = $request->get('per_page', 20);
        $books = $query->paginate($perPage)->appends($request->query());

        // Get filter options
        $categories = BookCategory::active()->orderBy('name')->get();
        $bookTypes = Book::select('book_type')->distinct()->whereNotNull('book_type')->pluck('book_type');
        $conditions = Book::select('condition')->distinct()->whereNotNull('condition')->pluck('condition');
        $statuses = Book::select('status')->distinct()->whereNotNull('status')->pluck('status');
        
        // Get year range for filters
        $yearRange = Book::selectRaw('MIN(publication_year) as min_year, MAX(publication_year) as max_year')
            ->whereNotNull('publication_year')
            ->first();

        return view('library.books.index', compact(
            'books', 
            'categories', 
            'bookTypes', 
            'conditions', 
            'statuses', 
            'yearRange'
        ));
    }

    /**
     * Show the form for creating a new book
     */
    public function create()
    {
        $categories = BookCategory::active()->orderBy('name')->get();
        return view('library.books.create', compact('categories'));
    }

    /**
     * Store a newly created book
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'publisher' => 'required|string|max:255',
            'publication_year' => 'required|integer|min:1800|max:' . (date('Y') + 1),
            'category_id' => 'required|exists:book_categories,id',
            'total_copies' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            $book = Book::create([
                'title' => $request->title,
                'author' => $request->author,
                'publisher' => $request->publisher,
                'publication_year' => $request->publication_year,
                'category_id' => $request->category_id,
                'total_copies' => $request->total_copies,
                'available_copies' => $request->total_copies,
                'created_by' => auth()->id(),
            ]);

            DB::commit();

            return redirect()->route('library.books.index')
                ->with('success', 'Book created successfully.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Failed to create book: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Display the specified book
     */
    public function show(Book $book)
    {
        $book->load(['category', 'issues.borrower']);
        $book->incrementViewCount();

        return view('library.books.show', compact('book'));
    }

    /**
     * Show the form for editing the specified book
     */
    public function edit(Book $book)
    {
        $categories = BookCategory::active()->orderBy('name')->get();
        return view('library.books.edit', compact('book', 'categories'));
    }

    /**
     * Update the specified book
     */
    public function update(Request $request, Book $book)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'publisher' => 'required|string|max:255',
            'publication_year' => 'required|integer|min:1800|max:' . (date('Y') + 1),
            'category_id' => 'required|exists:book_categories,id',
            'total_copies' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            $book->update([
                'title' => $request->title,
                'author' => $request->author,
                'publisher' => $request->publisher,
                'publication_year' => $request->publication_year,
                'category_id' => $request->category_id,
                'total_copies' => $request->total_copies,
                'updated_by' => auth()->id(),
            ]);

            $book->updateAvailabilityCounts();

            DB::commit();

            return redirect()->route('library.books.show', $book)
                ->with('success', 'Book updated successfully.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Failed to update book: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Remove the specified book
     */
    public function destroy(Book $book)
    {
        try {
            if ($book->activeIssues()->exists()) {
                return back()->withErrors(['error' => 'Cannot delete book with active issues.']);
            }

            $book->delete();

            return redirect()->route('library.books.index')
                ->with('success', 'Book deleted successfully.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to delete book: ' . $e->getMessage()]);
        }
    }

    /**
     * Display a listing of book issues
     */
    public function issues(Request $request)
    {
        $query = BookIssue::with(['book', 'borrower']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('book_id')) {
            $query->where('book_id', $request->book_id);
        }

        if ($request->filled('borrower_type')) {
            $query->where('borrower_type', $request->borrower_type);
        }

        $issues = $query->orderBy('issue_date', 'desc')->paginate(20);
        $books = Book::active()->orderBy('title')->get();

        return view('library.issues', compact('issues', 'books'));
    }

    /**
     * Issue a book to a borrower
     */
    public function issueBook(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'book_id' => 'required|exists:books,id',
            'borrower_id' => 'required|integer',
            'borrower_type' => 'required|in:student,teacher,staff',
            'due_date' => 'required|date|after:today',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            $book = Book::findOrFail($request->book_id);
            
            if ($book->available_copies <= 0) {
                return back()->withErrors(['error' => 'No copies available for this book.']);
            }

            $issue = BookIssue::create([
                'book_id' => $request->book_id,
                'borrower_id' => $request->borrower_id,
                'borrower_type' => $request->borrower_type,
                'issue_date' => Carbon::now(),
                'due_date' => $request->due_date,
                'status' => 'active',
                'issued_by' => auth()->id(),
                'created_by' => auth()->id(),
            ]);

            $book->decrement('available_copies');
            $book->increment('issued_copies');

            DB::commit();

            return redirect()->route('library.issues')
                ->with('success', 'Book issued successfully.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Failed to issue book: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Return a book
     */
    public function returnBook(BookIssue $issue)
    {
        try {
            DB::beginTransaction();

            $issue->update([
                'status' => 'returned',
                'actual_return_date' => Carbon::now(),
                'returned_by' => auth()->id(),
            ]);

            $book = $issue->book;
            $book->increment('available_copies');
            $book->decrement('issued_copies');

            DB::commit();

            return redirect()->route('library.issues')
                ->with('success', 'Book returned successfully.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Failed to return book: ' . $e->getMessage()]);
        }
    }

    /**
     * Renew a book
     */
    public function renewBook(BookIssue $issue)
    {
        try {
            $newDueDate = Carbon::parse($issue->due_date)->addDays(14);
            
            $issue->update([
                'due_date' => $newDueDate,
                'updated_by' => auth()->id(),
            ]);

            return redirect()->route('library.issues')
                ->with('success', 'Book renewed successfully. New due date: ' . $newDueDate->format('M d, Y'));

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to renew book: ' . $e->getMessage()]);
        }
    }

    /**
     * Display library reports
     */
    public function reports(Request $request)
    {
        $dateFrom = $request->get('date_from', Carbon::now()->startOfMonth());
        $dateTo = $request->get('date_to', Carbon::now()->endOfMonth());

        $stats = [
            'total_books' => Book::count(),
            'available_books' => Book::available()->count(),
            'issued_books' => BookIssue::active()->count(),
            'overdue_books' => BookIssue::overdue()->count(),
            'total_issues' => BookIssue::whereBetween('issue_date', [$dateFrom, $dateTo])->count(),
            'total_returns' => BookIssue::whereBetween('actual_return_date', [$dateFrom, $dateTo])->count(),
            'overdue_count' => BookIssue::overdue()->count(),
        ];

        $popularBooks = Book::withCount('issues')
            ->orderBy('issues_count', 'desc')
            ->limit(10)
            ->get();

        $categoryStats = Book::withCount('issues')
            ->with('category')
            ->get()
            ->groupBy('category.name')
            ->map(function ($books) {
                return [
                    'count' => $books->count(),
                    'issues' => $books->sum('issues_count'),
                ];
            });

        $monthlyIssues = BookIssue::selectRaw('DATE_FORMAT(issue_date, "%Y-%m") as month, COUNT(*) as count')
            ->whereBetween('issue_date', [Carbon::now()->subMonths(11), Carbon::now()])
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $overdueIssues = BookIssue::overdue()
            ->with(['book', 'borrower'])
            ->get();

        return view('library.reports', compact(
            'stats',
            'popularBooks',
            'categoryStats',
            'monthlyIssues',
            'overdueIssues',
            'dateFrom',
            'dateTo'
        ));
    }

    /**
     * Search books via AJAX
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        $limit = $request->get('limit', 10);

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $books = Book::with('category')
            ->search($query)
            ->active()
            ->limit($limit)
            ->get()
            ->map(function ($book) {
                return [
                    'id' => $book->id,
                    'title' => $book->title,
                    'author' => $book->author,
                    'isbn' => $book->isbn,
                    'category' => $book->category->name ?? 'Uncategorized',
                    'available_copies' => $book->available_copies,
                    'cover_image' => $book->cover_image ? Storage::url($book->cover_image) : null,
                    'url' => route('library.books.show', $book),
                ];
            });

        return response()->json($books);
    }

    /**
     * Get book suggestions for autocomplete
     */
    public function suggestions(Request $request)
    {
        $query = $request->get('q', '');
        $type = $request->get('type', 'all'); // all, title, author, isbn

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $suggestions = collect();

        switch ($type) {
            case 'title':
                $suggestions = Book::select('title')
                    ->where('title', 'like', "%{$query}%")
                    ->distinct()
                    ->limit(10)
                    ->pluck('title');
                break;
            case 'author':
                $suggestions = Book::select('author')
                    ->where('author', 'like', "%{$query}%")
                    ->distinct()
                    ->limit(10)
                    ->pluck('author');
                break;
            case 'isbn':
                $suggestions = Book::select('isbn')
                    ->where('isbn', 'like', "%{$query}%")
                    ->whereNotNull('isbn')
                    ->distinct()
                    ->limit(10)
                    ->pluck('isbn');
                break;
            default:
                $titles = Book::select('title')
                    ->where('title', 'like', "%{$query}%")
                    ->distinct()
                    ->limit(5)
                    ->pluck('title');
                
                $authors = Book::select('author')
                    ->where('author', 'like', "%{$query}%")
                    ->distinct()
                    ->limit(5)
                    ->pluck('author');
                
                $suggestions = $titles->concat($authors)->unique()->take(10);
                break;
        }

        return response()->json($suggestions->values());
    }

    /**
     * Export books to CSV
     */
    public function export(Request $request)
    {
        $query = Book::with('category');

        // Apply same filters as index method
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%")
                  ->orWhere('isbn', 'like', "%{$search}%")
                  ->orWhere('publisher', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $books = $query->get();

        $filename = 'books_export_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($books) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'Title', 'Author', 'Publisher', 'ISBN', 'Publication Year',
                'Category', 'Status', 'Condition', 'Total Copies', 'Available Copies',
                'Issued Copies', 'Price', 'Call Number', 'Created At'
            ]);

            // CSV data
            foreach ($books as $book) {
                fputcsv($file, [
                    $book->title,
                    $book->author,
                    $book->publisher,
                    $book->isbn,
                    $book->publication_year,
                    $book->category->name ?? 'Uncategorized',
                    $book->status,
                    $book->condition,
                    $book->total_copies,
                    $book->available_copies,
                    $book->issued_copies,
                    $book->price,
                    $book->call_number,
                    $book->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
