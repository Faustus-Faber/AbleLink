<?php
//F11 - Akida Lisi

//Akida - F11

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Education\Course;
use App\Models\Education\CourseMedia;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CourseMediaController extends Controller
{
    public function create(Course $course): View
    {
        return view('admin.courses.media.create', compact('course'));
    }

    public function store(Request $request, Course $course): RedirectResponse
    {
        $data = $request->validate([
            'kind' => ['required', Rule::in(CourseMedia::KINDS)],
            'title' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:1000000'],
            'is_primary' => ['nullable', 'boolean'],

            'external_url' => ['nullable', 'url', 'max:2048'],
            'media_file' => ['nullable', 'file', 'max:102400'], // 100MB

            'captions_language' => ['nullable', 'string', 'max:20'],
            'captions_file' => ['nullable', 'file', 'mimes:vtt,txt', 'max:10240'],

            'audio_description_file' => ['nullable', 'file', 'mimes:mp3,wav,m4a', 'max:51200'],
            'transcript' => ['nullable', 'string'],
        ]);

        $hasUrl = ! empty($data['external_url']);
        $hasFile = $request->hasFile('media_file');
        if ($data['kind'] === CourseMedia::KIND_LINK && ! $hasUrl) {
            return back()->withErrors(['external_url' => 'A link media item requires an external URL.'])->withInput();
        }
        if (! $hasUrl && ! $hasFile && $data['kind'] !== CourseMedia::KIND_LINK) {
            return back()->withErrors(['media_file' => 'Upload a file or provide an external URL.'])->withInput();
        }

        $media = new CourseMedia();
        $media->course_id = $course->id;
        $media->kind = $data['kind'];
        $media->title = $data['title'] ?? null;
        $media->sort_order = $data['sort_order'] ?? 0;
        $media->is_primary = (bool) ($data['is_primary'] ?? false);
        $media->external_url = $data['external_url'] ?? null;
        $media->captions_language = $data['captions_language'] ?? null;
        $media->transcript = $data['transcript'] ?? null;

        if ($request->hasFile('media_file')) {
            $file = $request->file('media_file');
            $media->mime_type = $file->getMimeType();
            $media->media_path = $file->store("courses/{$course->id}/media", 'public');
        }

        if ($request->hasFile('captions_file')) {
            $file = $request->file('captions_file');
            $media->captions_path = $file->store("courses/{$course->id}/captions", 'public');
        }

        if ($request->hasFile('audio_description_file')) {
            $file = $request->file('audio_description_file');
            $media->audio_description_path = $file->store("courses/{$course->id}/audio-descriptions", 'public');
        }

        if ($media->is_primary) {
            CourseMedia::where('course_id', $course->id)->update(['is_primary' => false]);
        }

        $media->save();

        return redirect()->route('admin.courses.edit', $course)->with('status', 'Media added.');
    }

    public function edit(CourseMedia $media): View
    {
        $media->load('course');

        return view('admin.courses.media.edit', compact('media'));
    }

    public function update(Request $request, CourseMedia $media): RedirectResponse
    {
        $data = $request->validate([
            'kind' => ['required', Rule::in(CourseMedia::KINDS)],
            'title' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:1000000'],
            'is_primary' => ['nullable', 'boolean'],

            'external_url' => ['nullable', 'url', 'max:2048'],
            'media_file' => ['nullable', 'file', 'max:102400'],

            'captions_language' => ['nullable', 'string', 'max:20'],
            'captions_file' => ['nullable', 'file', 'mimes:vtt,txt', 'max:10240'],

            'audio_description_file' => ['nullable', 'file', 'mimes:mp3,wav,m4a', 'max:51200'],
            'transcript' => ['nullable', 'string'],

            'remove_media_file' => ['nullable', 'boolean'],
            'remove_captions_file' => ['nullable', 'boolean'],
            'remove_audio_description_file' => ['nullable', 'boolean'],
        ]);

        $media->kind = $data['kind'];
        $media->title = $data['title'] ?? null;
        $media->sort_order = $data['sort_order'] ?? 0;
        $media->is_primary = (bool) ($data['is_primary'] ?? false);
        $media->external_url = $data['external_url'] ?? null;
        $media->captions_language = $data['captions_language'] ?? null;
        $media->transcript = $data['transcript'] ?? null;

        if ($media->kind === CourseMedia::KIND_LINK && empty($media->external_url)) {
            return back()->withErrors(['external_url' => 'A link media item requires an external URL.'])->withInput();
        }

        if (($data['remove_media_file'] ?? false) && $media->media_path) {
            Storage::disk('public')->delete($media->media_path);
            $media->media_path = null;
            $media->mime_type = null;
        }

        if (($data['remove_captions_file'] ?? false) && $media->captions_path) {
            Storage::disk('public')->delete($media->captions_path);
            $media->captions_path = null;
        }

        if (($data['remove_audio_description_file'] ?? false) && $media->audio_description_path) {
            Storage::disk('public')->delete($media->audio_description_path);
            $media->audio_description_path = null;
        }

        if ($request->hasFile('media_file')) {
            if ($media->media_path) {
                Storage::disk('public')->delete($media->media_path);
            }

            $file = $request->file('media_file');
            $media->mime_type = $file->getMimeType();
            $media->media_path = $file->store("courses/{$media->course_id}/media", 'public');
        }

        if ($request->hasFile('captions_file')) {
            if ($media->captions_path) {
                Storage::disk('public')->delete($media->captions_path);
            }

            $file = $request->file('captions_file');
            $media->captions_path = $file->store("courses/{$media->course_id}/captions", 'public');
        }

        if ($request->hasFile('audio_description_file')) {
            if ($media->audio_description_path) {
                Storage::disk('public')->delete($media->audio_description_path);
            }

            $file = $request->file('audio_description_file');
            $media->audio_description_path = $file->store("courses/{$media->course_id}/audio-descriptions", 'public');
        }

        if ($media->is_primary) {
            CourseMedia::where('course_id', $media->course_id)->whereKeyNot($media->id)->update(['is_primary' => false]);
        }

        $media->save();

        return back()->with('status', 'Media updated.');
    }

    public function destroy(CourseMedia $media): RedirectResponse
    {
        $courseId = $media->course_id;

        if ($media->media_path) {
            Storage::disk('public')->delete($media->media_path);
        }
        if ($media->captions_path) {
            Storage::disk('public')->delete($media->captions_path);
        }
        if ($media->audio_description_path) {
            Storage::disk('public')->delete($media->audio_description_path);
        }

        $media->delete();

        return redirect()->route('admin.courses.edit', $courseId)->with('status', 'Media deleted.');
    }
}

