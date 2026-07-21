<?php

namespace App\Http\Controllers;

use App\Models\Contacts;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Mail;
use App\Mail\ContactFormMail;

class ContactController extends Controller
{
    /**
     * Store a new contact inquiry
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate form data
        $validated = $request->validate([
            'firstName' => 'required|string|max:50',
            'lastName' => 'required|string|max:50',
            'email' => 'required|email|max:100',
            'company' => 'nullable|string|max:100',
            'inquiryType' => 'required|string|max:50',
            'message' => 'required|string|max:1000',
        ]);
        
        // Generate reference ID
        $referenceId = 'DV-' . Str::upper(Str::random(8));
        
        // Create new contact record
        $contact = Contacts::create([
            'first_name' => $validated['firstName'],
            'last_name' => $validated['lastName'],
            'email' => $validated['email'],
            'company' => $validated['company'] ?? null,
            'inquiry_type' => $validated['inquiryType'],
            'message' => $validated['message'],
            'reference_id' => $referenceId,
        ]);
        $MailData=[
            'first_name' => $validated['firstName'],
            'last_name' => $validated['lastName'],
            'email' => $validated['email'],
            'company' => $validated['company'] ?? null,
            'inquiry_type' => $validated['inquiryType'],
            'message' => $validated['message'],
            'reference_id' => $referenceId,
        ]; 
        
        // You could send notification email to admin here
        
        Mail::to(env("SUPPORT_MAIL"))->send(new ContactFormMail($MailData));
        
        // Return success response
        return response()->json([
            'success' => true,
            'message' => 'Contact inquiry submitted successfully',
            'reference_id' => $referenceId,
        ]);
    }

    
}