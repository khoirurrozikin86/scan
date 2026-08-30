<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lead;
use Illuminate\Support\Facades\Mail;


class ContactController extends Controller
{
    public function store(Request $r)
    {
        $data = $r->validate([
            'name' => 'required|string|max:120',
            'email' => 'required|email',
            'company' => 'nullable|string|max:120',
            'message' => 'required|string',
        ]);
        $lead = Lead::create($data);


        // optional: kirim notifikasi email
        if (config('mail.mailers.smtp.transport') ?? false) {
            Mail::raw("Lead baru: {$lead->name} <{$lead->email}>\n\n{$lead->message}", function ($m) use ($lead) {
                $m->to(config('mail.from.address'))
                    ->subject('Lead Baru dari Website');
            });
        }
        return back()->with('ok', 'Pesan terkirim!');
    }
}
