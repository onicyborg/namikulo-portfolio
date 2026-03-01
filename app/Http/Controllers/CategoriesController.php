<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoriesController extends Controller
{
	public function index()
	{
		$categories = Categories::query()
			->orderBy('created_at', 'desc')
			->get();

		return view('categories.index', compact('categories'));
	}

	public function store(Request $request)
	{
		$validated = $request->validate([
			'name' => ['required', 'string', 'max:150'],
			'description' => ['nullable', 'string'],
			'is_active' => ['nullable', 'boolean'],
		]);

		$slug = Str::slug($validated['name']);
		$originalSlug = $slug;
		$counter = 2;
		while (Categories::query()->where('slug', $slug)->exists()) {
			$slug = $originalSlug . '-' . $counter;
			$counter++;
		}

		Categories::create([
			'name' => $validated['name'],
			'slug' => $slug,
			'description' => $validated['description'] ?? null,
			'is_active' => (bool)($validated['is_active'] ?? false),
			'created_by' => auth()->id(),
		]);

		return redirect()
			->route('categories.index')
			->with('success', 'Category berhasil ditambahkan.');
	}

	public function update(Request $request, string $id)
	{
		$category = Categories::query()->findOrFail($id);

		$validated = $request->validate([
			'name' => ['required', 'string', 'max:150'],
			'description' => ['nullable', 'string'],
			'is_active' => ['nullable', 'boolean'],
		]);

		$slug = Str::slug($validated['name']);
		$originalSlug = $slug;
		$counter = 2;
		while (Categories::query()->where('slug', $slug)->where('id', '!=', $id)->exists()) {
			$slug = $originalSlug . '-' . $counter;
			$counter++;
		}

		$category->name = $validated['name'];
		$category->slug = $slug;
		$category->description = $validated['description'] ?? null;
		$category->is_active = (bool)($validated['is_active'] ?? false);

		$category->save();

		return redirect()
			->route('categories.index')
			->with('success', 'Category berhasil diupdate.');
	}

	public function destroy(string $id)
	{
		$category = Categories::query()->findOrFail($id);
		$category->delete();

		return redirect()
			->route('categories.index')
			->with('success', 'Category berhasil dihapus.');
	}
}
