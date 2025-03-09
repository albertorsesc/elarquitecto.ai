<?php

namespace App\Http\Controllers;

use App\Http\Requests\TimelineRequest;
use App\Models\Timeline;
use App\Models\Tag;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TimelineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $timelines = Timeline::with('author')->latest()->paginate(10);

        return Inertia::render('Timeline/Index', [
            'timelines' => $timelines
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tags = Tag::select('id', 'name')->get();

        return Inertia::render('Timeline/Create', [
            'tags' => $tags
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TimelineRequest $request)
    {
        $timeline = Timeline::create($request->validated());

        if ($request->has('tags')) {
            $timeline->tags()->sync($request->tags);
        }

        return redirect()->route('timeline.index')
            ->with('success', 'Timeline entry created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Timeline $timeline)
    {
        $timeline->load('author', 'tags');

        return Inertia::render('Timeline/Show', [
            'timeline' => $timeline
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Timeline $timeline)
    {
        $timeline->load('tags');
        $tags = Tag::select('id', 'name')->get();

        return Inertia::render('Timeline/Edit', [
            'timeline' => $timeline,
            'tags' => $tags
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TimelineRequest $request, Timeline $timeline)
    {
        $timeline->update($request->validated());

        if ($request->has('tags')) {
            $timeline->tags()->sync($request->tags);
        }

        return redirect()->route('timeline.index')
            ->with('success', 'Timeline entry updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Timeline $timeline)
    {
        $timeline->delete();

        return redirect()->route('timeline.index')
            ->with('success', 'Timeline entry deleted successfully.');
    }
}