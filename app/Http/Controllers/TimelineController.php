<?php

namespace App\Http\Controllers;

use App\Http\Requests\TimelineRequest;
use App\Models\Timeline;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TimelineController extends Controller
{
    /**
     * Display a listing of the resource.
     * @public
     */
    public function index() : Response
    {
        $timelines = Timeline::with(['author', 'tags'])->latest()->paginate(10);

        return Inertia::render('Timeline/Index', [
            'timelines' => $timelines
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * @root
     */
    public function create() : Response
    {
        $tags = Tag::select('id', 'name')->get();

        return Inertia::render('Timeline/Create', [
            'tags' => $tags
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @root
     */
    public function store(TimelineRequest $request) : RedirectResponse
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
     * @public
     */
    public function show(Timeline $timeline) : Response
    {
        $timeline->load('author', 'tags');

        return Inertia::render('Timeline/Show', [
            'timeline' => $timeline
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     * @root
     */
    public function edit(Timeline $timeline) : Response
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
     * @root
     */
    public function update(TimelineRequest $request, Timeline $timeline) : RedirectResponse
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
     * @root
     */
    public function destroy(Timeline $timeline) : RedirectResponse
    {
        $timeline->delete();

        return redirect()->route('timeline.index')
            ->with('success', 'Timeline entry deleted successfully.');
    }
}
