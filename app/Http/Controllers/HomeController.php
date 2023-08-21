<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Mail\ContactMail;
use App\Models\AgentSubmitForm;
use App\Models\LandingPage\LandingPageKey;
use App\Models\Service;
use App\Models\Subscribe;
use App\Models\Testimonial;
use App\Models\TourguideSubmitForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use function Symfony\Component\Translation\t;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $testimonials = Testimonial::whereIsPublished(true)->get();
        $services = Service::whereIsPublished(true)->get();
        $page = LandingPageKey::all();

        // If request has where parameter or from or to parameter then filter the data
        if ($request->has('where') || $request->has('from') || $request->has('to')) {
            $data = [];
            if ($request->has('where') && !in_array($request->where, ['', ' ', null, 'null'], true)) {
                $data['where'] = $request->where;
            }
            if ($request->has('from') && !in_array($request->from, ['', ' ', null, 'null'], true)) {
                $data['from'] = $request->from;
            }
            if ($request->has('to') && !in_array($request->to, ['', ' ', null, 'null'], true)) {
                $data['to'] = $request->to;
            }
            return redirect()->route('agent.resources.tourguides.index', $data);
        }

        return view('pages.home', compact('testimonials', 'services', 'page'));
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function contactSubmit(ContactRequest $request)
    {
        try {
            Mail::to(LandingPageKey::where('key', 'Footer')->first()->contents()->firstWhere('name', 'footer_contact_info_email')?->content)
                ->send(new ContactMail($request->safe()->all()));
            return redirect()->route('contact-us')->with('success', 'Your message has been sent!');
        } catch (\Exception $e) {
            Log::error("Error in HomeController@contactSubmit: {$e->getMessage()} at Line: {$e->getLine()} in File: {$e->getFile()}");
            return redirect()->route('contact-us')->with('error', 'There was an error sending your message. Please try again.');
        }
    }

    public function subscribe(Request $request)
    {
        $request->validate(['email' => 'required|email|unique:subscribes,email']);

        Subscribe::create(['email' => $request->email]);

        return redirect()->route('home')->with('success', 'You have been subscribed successfully!');
    }

    public function tourGuideSubmitForm(Request $request)
    {
        try {
            $request->validate([
                'full_name'     => ['required', 'string'],
                'email'         => ['email', 'required'],
                'phone'         => ['required', 'string'],
                'address'       => ['required', 'string'],
                'gender'        => ['required', 'string', 'in:male,female'],
                'date_of_birth' => ['required', 'date'],
                'languages'     => ['required'],
            ]);

            TourguideSubmitForm::create($request->all());
            return redirect()->route('submit-form')->with('success', 'Your submission has been sent!');
        } catch (\Exception $e) {
            Log::error("Error in HomeController@agentSubmitForm: {$e->getMessage()} at Line: {$e->getLine()} in File: {$e->getFile()}");
            return redirect()->route('submit-form')->with('error', 'There was an error sending your submission. Please try again.');
        }
    }

    public function agentSubmitForm(Request $request)
    {
        try {
            $request->validate([
                'full_name'     => ['required', 'string'],
                'email'         => ['email', 'required'],
                'phone'         => ['required', 'string'],
                'address'       => ['required', 'string'],
                'website'       => ['required', 'string'],
            ]);

            AgentSubmitForm::create($request->all());
            return redirect()->route('submit-form')->with('success', 'Your submission has been sent!');
        } catch (\Exception $e) {
            Log::error("Error in HomeController@tourGuideSubmitForm: {$e->getMessage()} at Line: {$e->getLine()} in File: {$e->getFile()}");
            return redirect()->route('submit-form')->with('error', 'There was an error sending your submission. Please try again.');
        }
    }
}
