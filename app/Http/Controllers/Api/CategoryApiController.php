<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Categories;
use Illuminate\Http\Request;

class CategoryApiController extends Controller
{
	public function index(Request $request)
	{
		$query = Categories::query();

		if (!$request->boolean('all')) {
			$query->where('is_active', true);
		}

		$items = $query
			->orderBy('name')
			->get(['id', 'name', 'slug']);

		return response()->json([
			'data' => $items->map(function (Categories $c) {
				return [
					'id' => $c->id,
					'name' => $c->name,
					'slug' => $c->slug,
				];
			})->values(),
		]);
	}
}
