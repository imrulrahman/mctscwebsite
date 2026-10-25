<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteContent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ContentController extends Controller
{
    public const SECTIONS = ['courses', 'projects', 'notices', 'events', 'gallery', 'partners', 'hero_slides'];
    public const LABELS = ['courses' => 'Courses', 'projects' => 'Projects', 'notices' => 'Notices', 'events' => 'Events', 'gallery' => 'Gallery', 'partners' => 'Development Partners', 'hero_slides' => 'Hero Slider Banners'];

    private function check(string $section): void
    {
        abort_unless(in_array($section, self::SECTIONS, true), 404);

        if (in_array($section, ['partners', 'hero_slides'], true)) {
            $user = auth()->user();
            $canAccess = $user?->is_admin
                || $user?->can('settings.manage')
                || $user?->can('projects.manage')
                || $user?->can('partners.manage')
                || $user?->can('hero_slides.manage');
            abort_unless($canAccess, 403, 'এই সেকশনে এক্সেস করার অনুমতি আপনার অ্যাকাউন্টে নেই।');
            return;
        }

        $permission = match ($section) {
            'courses' => 'courses.manage',
            'projects' => 'projects.manage',
            'notices' => 'notices.manage',
            'events' => 'events.manage',
            'gallery' => 'gallery.manage',
            default => null,
        };

        if ($permission) {
            abort_unless(auth()->user()?->can($permission), 403, 'এই সেকশনে এক্সেস করার অনুমতি আপনার অ্যাকাউন্টে নেই।');
        }
    }

    public function index(string $section): View
    {
        $this->check($section);
        return view('admin.content.index', ['section' => $section, 'label' => self::LABELS[$section], 'items' => SiteContent::where('section', $section)->orderBy('sort_order')->latest('updated_at')->paginate(15)]);
    }

    public function create(string $section): View
    {
        $this->check($section);
        return view('admin.content.form', ['section' => $section, 'label' => self::LABELS[$section], 'item' => new SiteContent()]);
    }

    public function store(Request $request, string $section): RedirectResponse
    {
        $this->check($section);
        $data = $this->validated($request, $section);
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('site-content', 'public');
            $this->mirrorToPublic($data['image']);
        }
        $data['section'] = $section;
        $data['slug'] = $this->uniqueSlug($data['title']);
        SiteContent::create($data);
        $this->clearCaches();
        return redirect()->route('admin.content.index', $section)->with('success', 'নতুন কনটেন্ট সংরক্ষিত হয়েছে।');
    }

    public function edit(string $section, SiteContent $content): View
    {
        $this->check($section);
        abort_unless($content->section === $section, 404);
        return view('admin.content.form', ['section' => $section, 'label' => self::LABELS[$section], 'item' => $content]);
    }

    public function update(Request $request, string $section, SiteContent $content): RedirectResponse
    {
        $this->check($section);
        abort_unless($content->section === $section, 404);
        $data = $this->validated($request, $section);
        if ($request->hasFile('image')) {
            if ($content->image) {
                Storage::disk('public')->delete($content->image);
                $oldPub = public_path('storage/' . $content->image);
                if (file_exists($oldPub)) @unlink($oldPub);
            }
            $data['image'] = $request->file('image')->store('site-content', 'public');
            $this->mirrorToPublic($data['image']);
        }
        $data['slug'] = $this->uniqueSlug($data['title'], $content->id);
        $content->update($data);
        $this->clearCaches();
        return redirect()->route('admin.content.index', $section)->with('success', 'পরিবর্তন সংরক্ষিত হয়েছে।');
    }

    public function destroy(string $section, SiteContent $content): RedirectResponse
    {
        $this->check($section);
        abort_unless($content->section === $section, 404);
        if ($content->image) {
            Storage::disk('public')->delete($content->image);
            $oldPub = public_path('storage/' . $content->image);
            if (file_exists($oldPub)) @unlink($oldPub);
        }
        $content->delete();
        $this->clearCaches();
        return back()->with('success', 'কনটেন্ট মুছে ফেলা হয়েছে।');
    }

    private function clearCaches(): void
    {
        try {
            \Illuminate\Support\Facades\Artisan::call('view:clear');
            \Illuminate\Support\Facades\Artisan::call('cache:clear');
        } catch (\Throwable $e) {
            // Ignore if artisan calls fail
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

    private function validated(Request $request, string $section): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'category' => ['nullable', 'string', 'max:120'],
            'admission_fee' => ['nullable', 'numeric', 'min:0'],
            'excerpt' => ['nullable', 'string', 'max:1000'],
            'body' => ['nullable', 'string', 'max:20000'],
            'status' => ['required', Rule::in(['published', 'draft'])],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'image' => ['nullable', 'file', 'mimes:jpeg,png,jpg,gif,svg,webp', 'max:5120'],
        ]) + ['sort_order' => $request->integer('sort_order', 0)];
    }

    private function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title) ?: 'content';
        $slug = $base;
        $i = 2;
        while (SiteContent::where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) $slug = $base.'-'.$i++;
        return $slug;
    }
}
