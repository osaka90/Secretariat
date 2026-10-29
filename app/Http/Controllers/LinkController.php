<?php

namespace App\Http\Controllers;

use App\Models\Link;
use Illuminate\Http\Request;

class LinkController extends Controller
{
    public function index()
    {
        return view('links');
    }

    public function list(Request $request)
    {
        $parentId = $request->query('parent_id');
        $items = Link::where('parent_id', $parentId)->orderBy('type', 'desc')->orderBy('name')->get();
        return response()->json($items);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'type'      => 'required|in:link,folder',
            'url'       => 'nullable|string|max:2048',
            'parent_id' => 'nullable|exists:links,id',
        ]);

        if ($validated['type'] === 'folder') {
            $validated['url'] = null;
        }

        $link = Link::create($validated);
        return response()->json($link, 201);
    }

    public function update(Request $request, Link $link)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:link,folder',
            'url'  => 'nullable|string|max:2048',
        ]);

        if ($validated['type'] === 'folder') {
            $validated['url'] = null;
        }

        $link->update($validated);
        return response()->json($link);
    }

    public function destroy(Link $link)
    {
        // Cascade is handled by the DB foreign key (onDelete cascade)
        $link->delete();
        return response()->json(null, 204);
    }
}
