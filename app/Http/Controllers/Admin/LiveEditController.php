<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteContent;
use App\Models\SiteSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class LiveEditController extends Controller
{
    /**
     * Save a site setting directly from on-page Live Edit Mode.
     */
    public function saveSetting(Request $request): JsonResponse
    {
        abort_unless(auth()->check() && (auth()->user()?->is_admin || auth()->user()?->can('settings.manage')), 403, 'অনুমতি নেই।');

        $request->validate([
            'key' => ['required', 'string', 'max:190'],
            'value' => ['nullable', 'string', 'max:50000'],
            'image' => ['nullable', 'file', 'mimes:jpeg,png,jpg,gif,svg,webp', 'max:5120'],
        ]);

        $key = $request->input('key');

        if ($request->hasFile('image')) {
            $old = SiteSetting::where('key', $key)->value('value');
            if ($old && !str_starts_with($old, 'assets/')) {
                Storage::disk('public')->delete($old);
                $oldPub = public_path('storage/' . $old);
                if (file_exists($oldPub)) @unlink($oldPub);
            }
            $path = $request->file('image')->store('settings', 'public');
            $this->mirrorToPublic($path);
            SiteSetting::updateOrCreate(['key' => $key], ['value' => $path]);
            $this->clearCaches();

            return response()->json([
                'success' => true,
                'key' => $key,
                'url' => function_exists('storage_asset') ? storage_asset($path) : asset('storage/' . $path),
                'message' => 'ছবি সফলভাবে সংরক্ষিত ও আপডেট হয়েছে!',
            ]);
        }

        $val = (string)($request->input('value') ?? '');
        SiteSetting::updateOrCreate(['key' => $key], ['value' => $val]);
        $this->clearCaches();

        return response()->json([
            'success' => true,
            'key' => $key,
            'value' => $val,
            'message' => 'সফলভাবে সংরক্ষিত হয়েছে!',
        ]);
    }

    /**
     * Save a site content item directly from on-page Live Edit Mode.
     */
    public function saveContent(Request $request): JsonResponse
    {
        abort_unless(auth()->check() && (auth()->user()?->is_admin || auth()->user()?->can('settings.manage') || auth()->user()?->can('projects.manage') || auth()->user()?->can('courses.manage')), 403, 'অনুমতি নেই।');

        $request->validate([
            'id' => ['required', 'integer', 'exists:site_contents,id'],
            'field' => ['required', 'string', 'in:title,category,excerpt,body,admission_fee'],
            'value' => ['nullable', 'string', 'max:50000'],
            'image' => ['nullable', 'file', 'mimes:jpeg,png,jpg,gif,svg,webp', 'max:5120'],
        ]);

        $id = $request->input('id');
        $field = $request->input('field');
        $content = SiteContent::findOrFail($id);

        if ($request->hasFile('image')) {
            if ($content->image) {
                Storage::disk('public')->delete($content->image);
                $oldPub = public_path('storage/' . $content->image);
                if (file_exists($oldPub)) @unlink($oldPub);
            }
            $path = $request->file('image')->store('site-content', 'public');
            $this->mirrorToPublic($path);
            $content->update(['image' => $path]);
            $this->clearCaches();

            return response()->json([
                'success' => true,
                'id' => $id,
                'field' => 'image',
                'url' => function_exists('storage_asset') ? storage_asset($path) : asset('storage/' . $path),
                'message' => 'কনটেন্ট ছবি সফলভাবে সংরক্ষিত হয়েছে!',
            ]);
        }

        $val = (string)($request->input('value') ?? '');
        $content->update([$field => $val]);
        $this->clearCaches();

        return response()->json([
            'success' => true,
            'id' => $id,
            'field' => $field,
            'value' => $val,
            'message' => 'কনটেন্ট সফলভাবে সংরক্ষিত হয়েছে!',
        ]);
    }

    private function clearCaches(): void
    {
        try {
            Artisan::call('view:clear');
            Artisan::call('cache:clear');
        } catch (\Throwable $e) {
            // Ignore cache clearing errors
        }
    }

    private function mirrorToPublic(string $path): void
    {
        $source = storage_path('app/public/' . $path);
        if (!file_exists($source)) {
            return;
        }
        $targets = [
            public_path('storage/' . $path),
            '/home/mctscedu/public_html/storage/' . $path,
        ];
        foreach ($targets as $target) {
            $dir = dirname($target);
            if (!is_dir($dir) && is_dir(dirname($dir))) {
                @mkdir($dir, 0775, true);
            }
            if (is_dir($dir) && !is_link(dirname($dir))) {
                @copy($source, $target);
            }
        }
    }
}
