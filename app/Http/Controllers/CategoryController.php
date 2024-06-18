<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryrRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $categories = Category::orderByDesc('id')->get();
        // dd($categories);

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        // $validated = $request->validate([
        //     'name' => ['required', 'string', 'max:255'],
        //     'icon' => ['required', 'image', 'mimes:png,jpg,jpeg,svg']
        // ]);

        DB::transaction(function () use ($request) {

            $validated = $request->validated();

            if ($request->hasFile('icon')) {
                $iconPath = $request->file('icon')->store('icon', 'public');
                $validated['icon'] = $iconPath;
            } else {
                $iconPath = 'images/icon-default.png';
            }

            $validated['slug'] = Str::slug($validated['name']);
            // web design -> web-design

            $category = Category::create($validated);
        });

        return redirect()->route('admin.categories.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryrRequest $request, Category $category)
    {
        //
        DB::transaction(function () use ($request, $category) {

            $validated = $request->validated();

            if ($request->hasFile('icon')) {
                $iconPath = $request->file('icon')->store('icon', 'public');
                $validated['icon'] = $iconPath;
            }

            $validated['slug'] = Str::slug($validated['name']);
            // web design -> web-design

            $category->update($validated);
        });

        return redirect()->route('admin.categories.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        //
        DB::beginTransaction();

        try {
            $category->delete();
            DB::commit();

            return redirect()->route('admin.categories.index');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('admin.categories.index')->with('error', 'Terjadi sebuah error');
        }
    }
}
