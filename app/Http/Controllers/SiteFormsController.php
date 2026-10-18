<?php

namespace App\Http\Controllers;

use App\Models\AdmissionApplication;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SiteFormsController extends Controller
{
    public function apply(Request $request): RedirectResponse
    {
        $data = $request->validate(['name' => ['required', 'string', 'max:150'], 'phone' => ['required', 'string', 'max:30'], 'email' => ['nullable', 'email', 'max:190'], 'course' => ['required', 'string', 'max:150'], 'education' => ['nullable', 'string', 'max:180'], 'message' => ['nullable', 'string', 'max:2000']]);
        AdmissionApplication::create($data + ['status' => 'new']);
        return back()->with('success', 'আবেদনটি গ্রহণ করা হয়েছে। ভর্তি অফিস থেকে যোগাযোগ করা হবে।');
    }

    public function contact(Request $request): RedirectResponse
    {
        $data = $request->validate(['name' => ['required', 'string', 'max:150'], 'email' => ['required', 'email', 'max:190'], 'phone' => ['nullable', 'string', 'max:30'], 'subject' => ['nullable', 'string', 'max:180'], 'message' => ['required', 'string', 'max:3000']]);
        ContactMessage::create($data);
        return back()->with('success', 'আপনার বার্তা পাঠানো হয়েছে। ধন্যবাদ।');
    }
}
