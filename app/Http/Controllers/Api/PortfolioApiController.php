<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Portfolios;
use Illuminate\Http\Request;

class PortfolioApiController extends Controller
{
	public function index(Request $request)
	{
		$categoryId = $request->query('category_id');
		$categorySlug = $request->query('category_slug');

		$query = Portfolios::query()
			->with([
				'category:id,name,slug',
				'images:id,portfolio_id,image_path,alt_text,is_thumbnail',
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
			->orderByDesc('published_at')
			->orderByDesc('created_at')
			->get();

		return response()->json([
			'data' => $items->map(function (Portfolios $p) {
				return [
					'id' => $p->id,
					'category' => $p->category ? [
						'id' => $p->category->id,
						'name' => $p->category->name,
						'slug' => $p->category->slug,
					] : null,
					'title' => $p->title,
					'slug' => $p->slug,
					'summary' => $p->summary,
					'description' => $p->description,
					'tech_stack' => $p->tech_stack,
					'project_url' => $p->project_url,
					'is_featured' => (bool) $p->is_featured,
					'is_published' => (bool) $p->is_published,
					'published_at' => optional($p->published_at)->toISOString(),
					'created_at' => optional($p->created_at)->toISOString(),
					'updated_at' => optional($p->updated_at)->toISOString(),
					'images' => $p->images->map(function ($img) {
						return [
							'id' => $img->id,
							'url' => url('storage/' . $img->image_path),
							'alt_text' => $img->alt_text,
							'is_thumbnail' => (bool) $img->is_thumbnail,
						];
					})->values(),
				];
			})->values(),
		]);
	}

	public function show(string $id, Request $request)
	{
		$query = Portfolios::query()
			->with([
				'category:id,name,slug',
				'images:id,portfolio_id,image_path,alt_text,is_thumbnail',
			]);

		if (!$request->boolean('all')) {
			$query->published();
		}

		$portfolio = $query->findOrFail($id);

		return response()->json([
			'data' => [
				'id' => $portfolio->id,
				'category' => $portfolio->category ? [
					'id' => $portfolio->category->id,
					'name' => $portfolio->category->name,
					'slug' => $portfolio->category->slug,
				] : null,
				'title' => $portfolio->title,
				'slug' => $portfolio->slug,
				'summary' => $portfolio->summary,
				'description' => $portfolio->description,
				'tech_stack' => $portfolio->tech_stack,
				'project_url' => $portfolio->project_url,
				'is_featured' => (bool) $portfolio->is_featured,
				'is_published' => (bool) $portfolio->is_published,
				'published_at' => optional($portfolio->published_at)->toISOString(),
				'created_at' => optional($portfolio->created_at)->toISOString(),
				'updated_at' => optional($portfolio->updated_at)->toISOString(),
				'images' => $portfolio->images->map(function ($img) {
					return [
						'id' => $img->id,
						'url' => url('storage/' . $img->image_path),
						'alt_text' => $img->alt_text,
						'is_thumbnail' => (bool) $img->is_thumbnail,
					];
				})->values(),
			],
		]);
	}
}
