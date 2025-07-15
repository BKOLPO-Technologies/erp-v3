<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inventory\Tag;
use Illuminate\Support\Str;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tags = Tag::orderBy('id', 'desc')->get();
        $pageTitle = 'Tag List';
        return view('Inventory.tag.index', compact('pageTitle', 'tags'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageTitle = 'Tag Create';
        return view('Inventory.tag.create', compact('pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|boolean',
        ]);

        try {
            $tag = new Tag();
            $tag->name = $validated['name'];
            $tag->slug = Str::slug($validated['name']);
            $tag->description = $validated['description'] ?? null;
            $tag->status = $validated['status'] ?? 1;

            $tag->save();

            return redirect()->route('inventory.tag.index')->with('success', 'Tag created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to create tag: ' . $e->getMessage()]);
        }
    }

    public function store2(Request $request)
    {
        //dd($request->all());
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Generate slug from name
        $slug = Str::slug($request->name);

        // Check if slug already exists and append a number if necessary
        $existingTag = Tag::where('slug', $slug)->first();
        if ($existingTag) {
            $slug = $slug . '-' . (Tag::count() + 1);
        }

        // Store the tag with the unique slug
        $tag = Tag::create([
            'name' => $request->name,
            'slug' => $slug,
            'status' => $request->status ?? 1, // Default to active if not provided
        ]);

        return response()->json([
            'success'  => true,
            'message'  => 'Tag added successfully.',
            'tag' => $tag,
            'all_tags' => Tag::where('status',1)->latest()->get()
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $tag = Tag::findOrFail($id);
        $pageTitle = 'Tag Details';
        return view('Inventory.tag.show', compact('pageTitle', 'tag'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $tag = Tag::findOrFail($id);
        $pageTitle = 'Tag Edit';
        return view('Inventory.tag.edit', compact('pageTitle', 'tag'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|boolean',
        ]);

        try {
            $tag = Tag::findOrFail($id);
            $tag->name = $validated['name'];
            $tag->slug = Str::slug($validated['name']);
            $tag->description = $validated['description'] ?? null;
            $tag->status = $validated['status'] ?? 1;

            $tag->save();

            return redirect()->route('inventory.tag.index')->with('success', 'Tag updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to update tag: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $tag = Tag::findOrFail($id);
            $tag->delete();

            return redirect()->route('inventory.tag.index')->with('success', 'Tag deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to delete tag: ' . $e->getMessage()]);
        }
    }
}
