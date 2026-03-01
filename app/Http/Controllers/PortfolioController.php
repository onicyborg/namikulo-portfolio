<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Portfolios;
use App\Models\PortfoliosImages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PortfolioController extends Controller
{
	public function index()
	{
		$categories = Categories::query()
			->orderBy('name')
			->get();

		$portfolios = Portfolios::query()
			->with(['category', 'images'])
			->orderBy('created_at', 'desc')
			->get();

		return view('portfolios.index', compact('categories', 'portfolios'));
	}

	public function store(Request $request)
	{
		$validated = $request->validate([
			'category_id' => ['required', 'uuid', 'exists:categories,id'],
			'title' => ['required', 'string', 'max:200'],
			'summary' => ['nullable', 'string', 'max:255'],
			'description' => ['required', 'string'],
			'tech_stack' => ['nullable', 'string', 'max:255'],
			'project_url' => ['nullable', 'string', 'max:255'],
			'is_featured' => ['nullable', 'boolean'],
			'is_published' => ['nullable', 'boolean'],
			'images' => ['nullable', 'array'],
			'images.*' => ['file', 'image', 'max:5120'],
		]);

		$slug = Str::slug($validated['title']);
		$originalSlug = $slug;
		$counter = 2;
		while (Portfolios::query()->where('slug', $slug)->exists()) {
			$slug = $originalSlug . '-' . $counter;
			$counter++;
		}

		$isPublished = (bool)($validated['is_published'] ?? false);

		$portfolio = Portfolios::create([
			'category_id' => $validated['category_id'],
			'created_by' => auth()->id(),
			'title' => $validated['title'],
			'slug' => $slug,
			'summary' => $validated['summary'] ?? null,
			'description' => $validated['description'],
			'tech_stack' => $validated['tech_stack'] ?? null,
			'project_url' => $validated['project_url'] ?? null,
			'is_featured' => (bool)($validated['is_featured'] ?? false),
			'is_published' => $isPublished,
			'published_at' => $isPublished ? now() : null,
		]);

		$images = $request->file('images', []);
		foreach ($images as $index => $image) {
			$path = $image->store('portfolios', 'public');

			PortfoliosImages::create([
				'portfolio_id' => $portfolio->id,
				'image_path' => $path,
				'alt_text' => $portfolio->title,
				'is_thumbnail' => $index === 0,
			]);
		}

		return redirect()
			->route('portfolios.index')
			->with('success', 'Portfolio berhasil ditambahkan.');
	}

	public function update(Request $request, string $id)
	{
		$portfolio = Portfolios::query()->with('images')->findOrFail($id);

		$validated = $request->validate([
			'category_id' => ['required', 'uuid', 'exists:categories,id'],
			'title' => ['required', 'string', 'max:200'],
			'summary' => ['nullable', 'string', 'max:255'],
			'description' => ['required', 'string'],
			'tech_stack' => ['nullable', 'string', 'max:255'],
			'project_url' => ['nullable', 'string', 'max:255'],
			'is_featured' => ['nullable', 'boolean'],
			'is_published' => ['nullable', 'boolean'],
			'removed_images' => ['nullable', 'array'],
			'removed_images.*' => ['uuid'],
			'images' => ['nullable', 'array'],
			'images.*' => ['file', 'image', 'max:5120'],
		]);

		$slug = Str::slug($validated['title']);
		$originalSlug = $slug;
		$counter = 2;
		while (Portfolios::query()->where('slug', $slug)->where('id', '!=', $id)->exists()) {
			$slug = $originalSlug . '-' . $counter;
			$counter++;
		}

		$isPublished = (bool)($validated['is_published'] ?? false);
		$wasPublished = (bool)$portfolio->is_published;

		$portfolio->category_id = $validated['category_id'];
		$portfolio->title = $validated['title'];
		$portfolio->slug = $slug;
		$portfolio->summary = $validated['summary'] ?? null;
		$portfolio->description = $validated['description'];
		$portfolio->tech_stack = $validated['tech_stack'] ?? null;
		$portfolio->project_url = $validated['project_url'] ?? null;
		$portfolio->is_featured = (bool)($validated['is_featured'] ?? false);
		$portfolio->is_published = $isPublished;
		if ($isPublished && !$wasPublished) {
			$portfolio->published_at = now();
		}
		if (!$isPublished) {
			$portfolio->published_at = null;
		}
		$portfolio->save();

		$removed = $request->input('removed_images', []);
		if (is_array($removed) && count($removed) > 0) {
			$imagesToDelete = PortfoliosImages::query()
				->where('portfolio_id', $portfolio->id)
				->whereIn('id', $removed)
				->get();

			foreach ($imagesToDelete as $img) {
				if ($img->image_path) {
					Storage::disk('public')->delete($img->image_path);
				}
				$img->delete();
			}
		}

		$portfolio->load('images');
		$existingCount = $portfolio->images->count();
		$hasThumbnail = $portfolio->images->contains(function ($img) {
			return (bool)$img->is_thumbnail;
		});

		$newImages = $request->file('images', []);
		foreach ($newImages as $index => $image) {
			$path = $image->store('portfolios', 'public');
			PortfoliosImages::create([
				'portfolio_id' => $portfolio->id,
				'image_path' => $path,
				'alt_text' => $portfolio->title,
				'is_thumbnail' => !$hasThumbnail && $existingCount === 0 && $index === 0,
			]);
		}

		$portfolio->load('images');
		$hasThumbnail = $portfolio->images->contains(function ($img) {
			return (bool)$img->is_thumbnail;
		});
		if (!$hasThumbnail && $portfolio->images->count() > 0) {
			$first = $portfolio->images->first();
			$first->is_thumbnail = true;
			$first->save();
		}

		return redirect()
			->route('portfolios.index')
			->with('success', 'Portfolio berhasil diupdate.');
	}

	public function destroy(string $id)
	{
		$portfolio = Portfolios::query()->with('images')->findOrFail($id);

		foreach ($portfolio->images as $image) {
			if ($image->image_path) {
				Storage::disk('public')->delete($image->image_path);
			}
		}

		$portfolio->delete();

		return redirect()
			->route('portfolios.index')
			->with('success', 'Portfolio berhasil dihapus.');
	}
}
