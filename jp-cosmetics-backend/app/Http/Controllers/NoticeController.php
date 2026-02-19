<?php

namespace App\Http\Controllers;

use App\Http\Requests\NoticeRequest;
use App\Models\Notice;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class NoticeController extends Controller
{
    public function list()
    {
        $notices = Notice::latest()->paginate(10);
        return view('notices.list', compact('notices'));
    }

    public function create()
    {
        return view('notices.create');
    }

    public function store(NoticeRequest $request)
    {
        $data = $request->validated();

        // Always set status = 1 on create
        $data['status'] = 1;

        // is_live will be set from list page, default to 0
        $data['is_live'] = 0;

        Notice::create($data);

        Toastr::success('Notice created successfully. Set it as live from the list page.');
        return redirect()->route('notice.list');
    }


    public function edit(string $id)
    {
        $notice = Notice::findOrFail($id);
        return view('notices.edit', compact('notice'));
    }

    public function update(NoticeRequest $request, string $id)
    {
        $notice = Notice::findOrFail($id);
        $data = $request->validated();

        // Don't allow is_live to be changed from edit form
        // It should only be changed from list page via radio button
        unset($data['is_live']);

        $notice->update($data);

        Toastr::success('Notice updated successfully.');
        return redirect()->route('notice.list');
    }

    public function toggleStatus($id)
    {
        try {
            $notice = Notice::findOrFail($id);

            if ($notice->status) {
                $notice->status = false;
                $notice->save();
            } else {
                $notice->status = true;
                $notice->save();
            }
            
            Toastr::success('Status changed successfully.');
            return redirect()->route('notice.list');
        } catch (\Exception $e) {
            Toastr::error('Status not changed');
            return redirect()->route('notice.list');
        }
    }

    public function setLive($id)
    {
        try {
            $notice = Notice::findOrFail($id);

            // Unset all other live notices
            Notice::where('is_live', 1)->update(['is_live' => 0]);

            // Set this notice as live
            $notice->is_live = true;
            $notice->save();
            
            Toastr::success('Notice set as live successfully.');
            return redirect()->route('notice.list');
        } catch (\Exception $e) {
            Toastr::error('Failed to set notice as live');
            return redirect()->route('notice.list');
        }
    }

    
}
