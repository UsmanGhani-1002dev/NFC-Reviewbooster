<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Mail\ContactFormMail;
use App\Models\ContactSubmission;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    public function index()
    {
        return view('contact');
    }

    public function submit(Request $request)
    {
        // Validate the form data
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'business_name' => 'nullable|string|max:255',
            'inquiry_type' => 'required|in:general,support,sales,billing,enterprise,partnership,feedback',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
            'privacy_policy' => 'required|accepted',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Store the submission in the database
            $submission = ContactSubmission::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'business_name' => $request->business_name,
                'inquiry_type' => $request->inquiry_type,
                'subject' => $request->subject,
                'message' => $request->message,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            // Get all admin users' emails
            $adminEmails = User::where('role', 'admin')->pluck('email')->toArray();
            Log::info('Attempting to send contact emails', [
                'admin_emails' => $adminEmails,
                'user_email' => $request->email
            ]);

            if (!empty($adminEmails)) {
                // Send email to all admins
                Mail::to($adminEmails)->send(new ContactFormMail($submission));
                Log::info('Admin notification email sent.');
            } else {
                Log::warning('No admin users found to send contact notification.');
            }

            // Send confirmation email to user
            Mail::to($request->email)->send(new ContactFormMail($submission, true));
            Log::info('User confirmation email sent.');

            return redirect()->back()->with('success', 'Thank you for your message! We\'ll get back to you within 24 hours.');

        } catch (\Exception $e) {
            Log::error('Contact form submission failed: ' . $e->getMessage(), [
                'email' => $request->email,
                'subject' => $request->subject,
            ]);

            return redirect()->back()
                ->with('error', 'Sorry, there was an error sending your message. Please try again or contact us directly.')
                ->withInput();
        }
    }

     public function submissions(Request $request)
    {
        $query = ContactSubmission::query();

        // Apply search filter
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%$search%")
                ->orWhere('last_name', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%")
                ->orWhere('message', 'like', "%$search%")
                ->orWhere('subject', 'like', "%$search%")
                ->orWhere('inquiry_type', 'like', "%$search%")
                ->orWhere('business_name', 'like', "%$search%")
                ->orWhereRaw("CONCAT(first_name, ' ', last_name, ' ', email) LIKE ?", ["%$search%"]);
            });
        }

        // Export to CSV
        if ($request->has('export') && $request->input('export') === 'csv') {
            $filename = 'contact_submissions_' . now()->format('Y-m-d_H-i-s') . '.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"$filename\"",
            ];

            $callback = function () use ($query) {
                $handle = fopen('php://output', 'w');

                fputcsv($handle, [
                    'Name',
                    'Email',
                    'Subject',
                    'Inquiry Type',
                    'Business Name',
                    'Message',
                    'Submitted At'
                ]);

                foreach ($query->latest()->cursor() as $submission) {
                    fputcsv($handle, [
                        $submission->first_name . ' ' . $submission->last_name,
                        $submission->email,
                        $submission->subject,
                        $submission->inquiry_type,
                        $submission->business_name,
                        strip_tags($submission->message),
                        $submission->created_at->format('Y-m-d H:i:s'),
                    ]);
                }

                fclose($handle);
            };

            return response()->stream($callback, 200, $headers);
        }

        $submissions = $query->latest()->paginate(20)->appends($request->only('search'));

        return view('admin.contact-submissions.index', compact('submissions'));
    }


    public function suggestions(Request $request)
    {
        $term = $request->input('term');

        if (!$term) return [];

        $results = ContactSubmission::query()
            ->where('first_name', 'like', "%{$term}%")
            ->orWhere('last_name', 'like', "%{$term}%")
            ->orWhere('email', 'like', "%{$term}%")
            ->orWhere('subject', 'like', "%{$term}%")
            ->orWhere('inquiry_type', 'like', "%{$term}%")
            ->orWhere('business_name', 'like', "%{$term}%")
            ->limit(10)
            ->get();

        $suggestions = [];

        foreach ($results as $item) {
           $suggestions[] = [
                'label' => "{$item->first_name} {$item->last_name} ({$item->email})",
                'value' => "{$item->first_name} {$item->last_name} {$item->email}", // now includes both
            ];
        }

        return response()->json($suggestions);
    }

    public function view_contact($id)
    {
        $contacts = ContactSubmission::findOrFail($id);
        return view('admin.contact-submissions.view', compact('contacts'));
    }

    
    public function update_sub_Status(Request $request, $id)
    {
        $submission = ContactSubmission::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:new,in_progress,resolved',
        ]);

        $submission->status = $validated['status'];
        $submission->save();

        return redirect()->route('admin.contact-submissions.index')->with('success', 'Status updated successfully.');
    }


   public function destroy(ContactSubmission $submission)
    {
        $submission->delete();
        return redirect()->route('admin.contact-submissions.index')->with('success', 'Submission deleted.');
    }


}
