<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Testimonials;
use Illuminate\Http\Request;

class TestimonialApiController extends Controller
{
	public function index(Request $request)
	{
		$categoryId = $request->query('category_id');
		$categorySlug = $request->query('category_slug');

		$query = Testimonials::query()
			->with([
				'category:id,name,slug',
				'images:id,testimonial_id,image_path',
			]);

		if (!$request->boolean('all')) {
			$query->published();
		}

		if ($categoryId) {
			$query->where('category_id', $categoryId);
		}

		if ($categorySlug) {
			$query->whereHas('category', function ($q) use ($categorySlug) {
				$q->where('slug', $categorySlug);
			});
		}

		$items = $query
			->orderByDesc('created_at')
			->get();

		return response()->json([
			'data' => $items->map(function (Testimonials $t) {
				return [
					'id' => $t->id,
					'category' => $t->category ? [
						'id' => $t->category->id,
						'name' => $t->category->name,
						'slug' => $t->category->slug,
					] : null,
					'client_name' => $t->client_name,
					'client_position' => $t->client_position,
					'company_name' => $t->company_name,
					'content' => $t->content,
					'rating' => $t->rating,
					'is_featured' => (bool) $t->is_featured,
					'is_published' => (bool) $t->is_published,
					'created_at' => optional($t->created_at)->toISOString(),
					'updated_at' => optional($t->updated_at)->toISOString(),
					'images' => $t->images->map(function ($img) {
						return [
							'id' => $img->id,
							'url' => url('storage/' . $img->image_path),
						];
					})->values(),
				];
			})->values(),
		]);
	}

	public function show(string $id, Request $request)
	{
		$query = Testimonials::query()
			->with([
				'category:id,name,slug',
				'images:id,testimonial_id,image_path',
			]);

		if (!$request->boolean('all')) {
			$query->published();
		}

		$testimonial = $query->findOrFail($id);

		return response()->json([
			'data' => [
				'id' => $testimonial->id,
				'category' => $testimonial->category ? [
					'id' => $testimonial->category->id,
					'name' => $testimonial->category->name,
					'slug' => $testimonial->category->slug,
				] : null,
				'client_name' => $testimonial->client_name,
				'client_position' => $testimonial->client_position,
				'company_name' => $testimonial->company_name,
				'content' => $testimonial->content,
				'rating' => $testimonial->rating,
				'is_featured' => (bool) $testimonial->is_featured,
				'is_published' => (bool) $testimonial->is_published,
				'created_at' => optional($testimonial->created_at)->toISOString(),
				'updated_at' => optional($testimonial->updated_at)->toISOString(),
				'images' => $testimonial->images->map(function ($img) {
					return [
						'id' => $img->id,
						'url' => url('storage/' . $img->image_path),
					];
				})->values(),
			],
		]);
	}
}
