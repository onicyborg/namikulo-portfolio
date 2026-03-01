<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Testimonials;
use App\Models\TestimonialsImages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TestimonialController extends Controller
{
	public function index()
	{
		$categories = Categories::query()
			->orderBy('name')
			->get();

		$testimonials = Testimonials::query()
			->with(['category', 'images'])
			->orderBy('created_at', 'desc')
			->get();

		return view('testimonials.index', compact('categories', 'testimonials'));
	}

	public function store(Request $request)
	{
		$validated = $request->validate([
			'category_id' => ['nullable', 'uuid', 'exists:categories,id'],
			'client_name' => ['required', 'string', 'max:150'],
			'client_position' => ['nullable', 'string', 'max:150'],
			'company_name' => ['nullable', 'string', 'max:150'],
			'content' => ['required', 'string'],
			'rating' => ['nullable', 'integer', 'min:1', 'max:5'],
			'is_featured' => ['nullable', 'boolean'],
			'is_published' => ['nullable', 'boolean'],
			'images' => ['nullable', 'array'],
			'images.*' => ['file', 'image', 'max:5120'],
		]);

		$testimonial = Testimonials::create([
			'category_id' => $validated['category_id'] ?? null,
			'created_by' => auth()->id(),
			'client_name' => $validated['client_name'],
			'client_position' => $validated['client_position'] ?? null,
			'company_name' => $validated['company_name'] ?? null,
			'content' => $validated['content'],
			'rating' => $validated['rating'] ?? null,
			'is_featured' => (bool)($validated['is_featured'] ?? false),
			'is_published' => (bool)($validated['is_published'] ?? false),
		]);

		$images = $request->file('images', []);
		foreach ($images as $image) {
			$path = $image->store('testimonials', 'public');
			TestimonialsImages::create([
				'testimonial_id' => $testimonial->id,
				'image_path' => $path,
			]);
		}

		return redirect()
			->route('testimonials.index')
			->with('success', 'Testimoni berhasil ditambahkan.');
	}

	public function update(Request $request, string $id)
	{
		$testimonial = Testimonials::query()->with('images')->findOrFail($id);

		$validated = $request->validate([
			'category_id' => ['nullable', 'uuid', 'exists:categories,id'],
			'client_name' => ['required', 'string', 'max:150'],
			'client_position' => ['nullable', 'string', 'max:150'],
			'company_name' => ['nullable', 'string', 'max:150'],
			'content' => ['required', 'string'],
			'rating' => ['nullable', 'integer', 'min:1', 'max:5'],
			'is_featured' => ['nullable', 'boolean'],
			'is_published' => ['nullable', 'boolean'],
			'removed_images' => ['nullable', 'array'],
			'removed_images.*' => ['uuid'],
			'images' => ['nullable', 'array'],
			'images.*' => ['file', 'image', 'max:5120'],
		]);

		$testimonial->category_id = $validated['category_id'] ?? null;
		$testimonial->client_name = $validated['client_name'];
		$testimonial->client_position = $validated['client_position'] ?? null;
		$testimonial->company_name = $validated['company_name'] ?? null;
		$testimonial->content = $validated['content'];
		$testimonial->rating = $validated['rating'] ?? null;
		$testimonial->is_featured = (bool)($validated['is_featured'] ?? false);
		$testimonial->is_published = (bool)($validated['is_published'] ?? false);
		$testimonial->save();

		$removed = $request->input('removed_images', []);
		if (is_array($removed) && count($removed) > 0) {
			$imagesToDelete = TestimonialsImages::query()
				->where('testimonial_id', $testimonial->id)
				->whereIn('id', $removed)
				->get();

			foreach ($imagesToDelete as $img) {
				if ($img->image_path) {
					Storage::disk('public')->delete($img->image_path);
				}
				$img->delete();
			}
		}

		$newImages = $request->file('images', []);
		foreach ($newImages as $image) {
			$path = $image->store('testimonials', 'public');
			TestimonialsImages::create([
				'testimonial_id' => $testimonial->id,
				'image_path' => $path,
			]);
		}

		return redirect()
			->route('testimonials.index')
			->with('success', 'Testimoni berhasil diupdate.');
	}

	public function destroy(string $id)
	{
		$testimonial = Testimonials::query()->with('images')->findOrFail($id);

		foreach ($testimonial->images as $image) {
			if ($image->image_path) {
				Storage::disk('public')->delete($image->image_path);
			}
		}

		$testimonial->delete();

		return redirect()
			->route('testimonials.index')
			->with('success', 'Testimoni berhasil dihapus.');
	}
}
