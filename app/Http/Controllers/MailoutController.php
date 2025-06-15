<?php

namespace App\Http\Controllers;

use App\Models\Athlete;
use App\Models\Institution;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class MailoutController extends Controller
{
    /**
     * Show the mailout form
     */
    public function index()
    {
        $institutions = Institution::orderBy('name')->get();
        return view('mailouts.index', compact('institutions'));
    }
    
    /**
     * Get athletes by institution for the mailout form
     */
    public function getAthletesByInstitution(Request $request)
    {
        $institutionId = $request->institution_id;
        
        if (!$institutionId) {
            return response()->json(['error' => 'Institution ID is required'], 400);
        }
        
        // Get athletes with their latest report
        $athletes = Athlete::with(['athleteProfile', 'reports' => function($query) {
                $query->latest()->limit(1);
            }])
            ->where('institution_id', $institutionId)
            ->get()
            ->map(function($athlete) {
                $latestReport = $athlete->reports->first();
                return [
                    'id' => $athlete->id,
                    'name' => $athlete->athleteProfile ? $athlete->athleteProfile->first_name . ' ' . $athlete->athleteProfile->last_name : 'Unknown',
                    'sport' => $athlete->sport,
                    'category' => $athlete->category,
                    'has_report' => $latestReport ? true : false,
                    'report_id' => $latestReport ? $latestReport->id : null,
                    'report_date' => $latestReport ? $latestReport->created_at->format('d/m/Y') : null,
                ];
            });
            
        return response()->json($athletes);
    }
    
    /**
     * Send emails to selected athletes
     */
    public function send(Request $request)
    {
        $request->validate([
            'athlete_ids' => 'required|array',
            'athlete_ids.*' => 'required|exists:athletes,id',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);
        
        $athleteIds = $request->athlete_ids;
        $subject = $request->subject;
        $message = $request->message;
        $emailsSent = 0;
        $emailsFailed = 0;
        
        foreach ($athleteIds as $athleteId) {
            $athlete = Athlete::with(['athleteProfile', 'reports' => function($query) {
                $query->latest()->limit(1);
            }])->find($athleteId);
            
            if (!$athlete || !$athlete->athleteProfile) {
                $emailsFailed++;
                continue;
            }
            
            $latestReport = $athlete->reports->first();
            if (!$latestReport) {
                $emailsFailed++;
                continue;
            }
            
            // Generate a signed URL for public access to the report
            $publicUrl = URL::temporarySignedRoute(
                'public.report', 
                now()->addDays(30), // Link valid for 30 days
                ['report' => $latestReport->id]
            );
            
            // Update the report to mark it as sent
            $latestReport->update([
                'sent_to_tutor' => true,
                'sent_to_institution' => true,
            ]);
            
            // Send the email
            try {
                Mail::send('emails.report', [
                    'athlete' => $athlete,
                    'message' => $message,
                    'reportUrl' => $publicUrl,
                ], function ($mail) use ($athlete, $subject) {
                    // Send to athlete's tutor if available
                    if ($athlete->athleteProfile->tutor && $athlete->athleteProfile->tutor->email) {
                        $mail->to($athlete->athleteProfile->tutor->email, 
                            $athlete->athleteProfile->tutor->first_name . ' ' . $athlete->athleteProfile->tutor->last_name);
                    } 
                    // Send to institution if available
                    else if ($athlete->institution && $athlete->institution->email) {
                        $mail->to($athlete->institution->email, $athlete->institution->name);
                    }
                    // Otherwise, can't send
                    else {
                        throw new \Exception('No recipient email found');
                    }
                    
                    $mail->subject($subject);
                });
                
                $emailsSent++;
            } catch (\Exception $e) {
                $emailsFailed++;
            }
        }
        
        return redirect()->route('mailouts.index')
            ->with('success', "Emails sent: $emailsSent. Failed: $emailsFailed.");
    }
    
    /**
     * Show a public report (accessible via signed URL)
     */
    public function publicReport(Request $request, Report $report)
    {
        // Check if the URL signature is valid
        if (!$request->hasValidSignature()) {
            abort(401, 'This link has expired or is invalid.');
        }
        
        $template = $report->template;
        
        return view('mailouts.public-report', compact('report', 'template'));
    }
}
