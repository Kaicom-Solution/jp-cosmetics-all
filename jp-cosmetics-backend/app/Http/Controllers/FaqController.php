<?php

namespace App\Http\Controllers;

use App\Http\Requests\FaqRequest;
use App\Models\Faq;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function list()
    {
        $faqs = Faq::orderBy('order', 'asc')->paginate(10);
        return view('faqs.list', compact('faqs'));
    }

    public function create()
    {
        return view('faqs.create');
    }

    public function store(FaqRequest $request)
    {
        $data = $request->validated();

        // Always set status = 1 on create
        $data['status'] = 1;

        // If order is not provided, set it to highest + 1
        if (!isset($data['order']) || empty($data['order'])) {
            $maxOrder = Faq::max('order');
            $data['order'] = $maxOrder ? $maxOrder + 1 : 1;
        }

        Faq::create($data);

        Toastr::success('FAQ created successfully.');
        return redirect()->route('faq.list');
    }

    public function edit(string $id)
    {
        $faq = Faq::findOrFail($id);
        return view('faqs.edit', compact('faq'));
    }

    public function update(FaqRequest $request, string $id)
    {
        $faq = Faq::findOrFail($id);
        $data = $request->validated();

        $faq->update($data);

        Toastr::success('FAQ updated successfully.');
        return redirect()->route('faq.list');
    }

    public function toggleStatus($id)
    {
        try {
            $faq = Faq::findOrFail($id);

            if ($faq->status) {
                $faq->status = false;
                $faq->save();
            } else {
                $faq->status = true;
                $faq->save();
            }
            
            Toastr::success('Status changed successfully.');
            return redirect()->route('faq.list');
        } catch (\Exception $e) {
            Toastr::error('Status not changed');
            return redirect()->route('faq.list');
        }
    }

}
