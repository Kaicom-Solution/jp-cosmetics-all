<?php

namespace App\Http\Controllers;

use App\Http\Requests\PromotionPopupRequest;
use App\Models\PromotionPopup;
use App\Services\FileStorageService;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class PromotionPopupController extends Controller
{
    protected $fileStorageService;

    public function __construct(FileStorageService $fileStorageService)
    {
        $this->fileStorageService = $fileStorageService;
    }

    public function list()
    {
        $popups = PromotionPopup::latest()->paginate(10);
        return view('promotion-popups.list', compact('popups'));
    }

    public function create()
    {
        return view('promotion-popups.create');
    }

    public function store(PromotionPopupRequest $request)
    {
        $data = $request->validated();

        // Always set status = 1 on create
        $data['status'] = 1;

        // is_live will be set from list page, default to 0
        $data['is_live'] = 0;

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $cloudImageUploadResponse = $this->fileStorageService->uploadImageToCloud($image, 'promotion-popup');
            $data['image'] = $cloudImageUploadResponse['public_path'];
        }

        PromotionPopup::create($data);

        Toastr::success('Promotion popup created successfully. Set it as live from the list page.');
        return redirect()->route('promotion-popup.list');
    }

    public function edit(string $id)
    {
        $popup = PromotionPopup::findOrFail($id);
        return view('promotion-popups.edit', compact('popup'));
    }

    public function update(PromotionPopupRequest $request, string $id)
    {
        $popup = PromotionPopup::findOrFail($id);
        $data = $request->validated();

        // Don't allow is_live to be changed from edit form
        // It should only be changed from list page via radio button
        unset($data['is_live']);

        // Handle image upload
        if ($request->hasFile('image')) {
            if ($popup->image) {
                $newFile = $request->file('image');
                $fileToDelete = $popup->image;
                $imageUploadResponse = $this->fileStorageService->updateFileFromCloud($fileToDelete, $newFile);
                $data['image'] = $imageUploadResponse['public_path'];
            } else {
                $image = $request->file('image');
                $imageUploadResponse = $this->fileStorageService->uploadImageToCloud($image, 'promotion-popup');
                $data['image'] = $imageUploadResponse['public_path'];
            }
        }

        $popup->update($data);

        Toastr::success('Promotion popup updated successfully.');
        return redirect()->route('promotion-popup.list');
    }

    public function toggleStatus($id)
    {
        try {
            $popup = PromotionPopup::findOrFail($id);

            if ($popup->status) {
                $popup->status = false;
                $popup->save();
            } else {
                $popup->status = true;
                $popup->save();
            }
            
            Toastr::success('Status changed successfully.');
            return redirect()->route('promotion-popup.list');
        } catch (\Exception $e) {
            Toastr::error('Status not changed');
            return redirect()->route('promotion-popup.list');
        }
    }

    public function setLive($id)
    {
        try {
            $popup = PromotionPopup::findOrFail($id);

            // Unset all other live popups
            PromotionPopup::where('is_live', 1)->update(['is_live' => 0]);

            // Set this popup as live
            $popup->is_live = true;
            $popup->save();
            
            Toastr::success('Promotion popup set as live successfully.');
            return redirect()->route('promotion-popup.list');
        } catch (\Exception $e) {
            Toastr::error('Failed to set popup as live');
            return redirect()->route('promotion-popup.list');
        }
    }
}
