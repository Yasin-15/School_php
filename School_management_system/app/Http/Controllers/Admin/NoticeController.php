<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use App\Models\SchoolClass;
use Illuminate\Http\Request;

class NoticeController extends Controller
{
    public function index()
    {
        $notices = Notice::with(['schoolClass', 'createdBy'])->latest()->paginate(15);
        return view('admin.notices.index', compact('notices'));
    }

    public function create()
    {
        $classes = SchoolClass::where('is_active', true)->get();
        return view('admin.notices.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'notice_type' => 'required|in:general,academic,event,holiday,urgent',
            'target_audience' => 'required|in:all,students,teachers,parents,specific_class',
            'school_class_id' => 'required_if:target_audience,specific_class|exists:school_classes,id',
            'publish_date' => 'required|date',
            'expiry_date' => 'nullable|date|after:publish_date',
            'is_urgent' => 'boolean',
            'send_notification' => 'boolean',
        ]);

        Notice::create([
            'title' => $request->title,
            'content' => $request->content,
            'notice_type' => $request->notice_type,
            'target_audience' => $request->target_audience,
            'school_class_id' => $request->target_audience === 'specific_class' ? $request->school_class_id : null,
            'publish_date' => $request->publish_date,
            'expiry_date' => $request->expiry_date,
            'is_urgent' => $request->boolean('is_urgent'),
            'send_notification' => $request->boolean('send_notification'),
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('admin.notices.index')
            ->with('success', 'Notice created successfully.');
    }

    public function show(Notice $notice)
    {
        $notice->load(['schoolClass', 'createdBy']);
        return view('admin.notices.show', compact('notice'));
    }

    public function edit(Notice $notice)
    {
        $classes = SchoolClass::where('is_active', true)->get();
        return view('admin.notices.edit', compact('notice', 'classes'));
    }

    public function update(Request $request, Notice $notice)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'notice_type' => 'required|in:general,academic,event,holiday,urgent',
            'target_audience' => 'required|in:all,students,teachers,parents,specific_class',
            'school_class_id' => 'required_if:target_audience,specific_class|exists:school_classes,id',
            'publish_date' => 'required|date',
            'expiry_date' => 'nullable|date|after:publish_date',
            'is_urgent' => 'boolean',
            'send_notification' => 'boolean',
        ]);

        $notice->update([
            'title' => $request->title,
            'content' => $request->content,
            'notice_type' => $request->notice_type,
            'target_audience' => $request->target_audience,
            'school_class_id' => $request->target_audience === 'specific_class' ? $request->school_class_id : null,
            'publish_date' => $request->publish_date,
            'expiry_date' => $request->expiry_date,
            'is_urgent' => $request->boolean('is_urgent'),
            'send_notification' => $request->boolean('send_notification'),
        ]);

        return redirect()->route('admin.notices.index')
            ->with('success', 'Notice updated successfully.');
    }

    public function destroy(Notice $notice)
    {
        $notice->delete();

        return redirect()->route('admin.notices.index')
            ->with('success', 'Notice deleted successfully.');
    }

    public function toggleStatus(Notice $notice)
    {
        $notice->update(['is_active' => !$notice->is_active]);

        $status = $notice->is_active ? 'activated' : 'deactivated';
        return redirect()->back()
            ->with('success', "Notice {$status} successfully.");
    }
}