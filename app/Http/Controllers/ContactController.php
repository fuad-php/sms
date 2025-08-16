<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContactInquiry;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactFormSubmission;
use App\Mail\ContactFormConfirmation;

class ContactController extends Controller
{
    /**
     * Show the contact form
     */
    public function index()
    {
        return view('contact.index');
    }

    /**
     * Handle contact form submission
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
            'department' => 'nullable|string|max:100',
            'g-recaptcha-response' => 'nullable|string',
        ]);

        // Create contact inquiry
        $inquiry = ContactInquiry::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'subject' => $request->subject,
            'message' => $request->message,
            'department' => $request->department,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Send notification email to admin
        try {
            Mail::to(config('mail.admin_email', 'admin@school.com'))
                ->send(new ContactFormSubmission($inquiry));
        } catch (\Exception $e) {
            // Log the error but don't fail the form submission
            \Log::error('Failed to send contact form notification: ' . $e->getMessage());
        }

        // Send confirmation email to user
        try {
            Mail::to($request->email)
                ->send(new ContactFormConfirmation($inquiry));
        } catch (\Exception $e) {
            // Log the error but don't fail the form submission
            \Log::error('Failed to send contact form confirmation: ' . $e->getMessage());
        }

        return redirect()->route('contact.index')
            ->with('success', 'Thank you for your message! We will get back to you soon.');
    }

    /**
     * Display contact inquiries (admin only)
     */
    public function adminIndex()
    {
        $inquiries = ContactInquiry::orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.contact.index', compact('inquiries'));
    }

    /**
     * Show a specific contact inquiry
     */
    public function show(ContactInquiry $inquiry)
    {
        // Mark as read if not already
        if (!$inquiry->is_read) {
            $inquiry->update(['is_read' => true]);
        }

        return view('admin.contact.show', compact('inquiry'));
    }

    /**
     * Mark inquiry as read/unread
     */
    public function toggleRead(ContactInquiry $inquiry)
    {
        $inquiry->update(['is_read' => !$inquiry->is_read]);

        return redirect()->back()
            ->with('success', 'Inquiry status updated successfully!');
    }

    /**
     * Delete a contact inquiry
     */
    public function destroy(ContactInquiry $inquiry)
    {
        $inquiry->delete();

        return redirect()->route('admin.contact.index')
            ->with('success', 'Contact inquiry deleted successfully!');
    }

    /**
     * Get contact statistics for dashboard
     */
    public function getStats()
    {
        $stats = [
            'total' => ContactInquiry::count(),
            'unread' => ContactInquiry::where('is_read', false)->count(),
            'today' => ContactInquiry::whereDate('created_at', today())->count(),
            'this_week' => ContactInquiry::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
        ];

        return response()->json($stats);
    }

    /**
     * Export contact inquiries
     */
    public function export(Request $request)
    {
        $format = $request->get('format', 'csv');
        $inquiries = ContactInquiry::orderBy('created_at', 'desc')->get();

        if ($format === 'csv') {
            return $this->exportToCsv($inquiries);
        }

        return response()->json($inquiries);
    }

    /**
     * Export to CSV
     */
    private function exportToCsv($inquiries)
    {
        $filename = 'contact_inquiries_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($inquiries) {
            $file = fopen('php://output', 'w');
            
            // Add headers
            fputcsv($file, [
                'ID', 'Name', 'Email', 'Phone', 'Subject', 'Message', 
                'Department', 'Status', 'Created At'
            ]);

            // Add data
            foreach ($inquiries as $inquiry) {
                fputcsv($file, [
                    $inquiry->id,
                    $inquiry->name,
                    $inquiry->email,
                    $inquiry->phone,
                    $inquiry->subject,
                    $inquiry->message,
                    $inquiry->department,
                    $inquiry->is_read ? 'Read' : 'Unread',
                    $inquiry->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
