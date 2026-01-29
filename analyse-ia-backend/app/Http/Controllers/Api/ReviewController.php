<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Process;
use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Services\ReviewAnalyzer;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ReviewController extends Controller
{
    public function __construct(private ReviewAnalyzer $analyzer) {}

    public function index(Request $request)
    {
        $reviews = Review::with('user')->orderBy('id', 'desc')->get();
        return response()->json($reviews, Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'content' => 'required|string',
        ]);

        $analysis = $this->analyzer->analyze($data['content']);

        $review = Review::create([
            'user_id'   => $request->user()->id,
            'content'   => $data['content'],
            'sentiment' => $analysis['sentiment'],
            'score'     => $analysis['score'],
            'topics'    => $analysis['topics'],
        ]);

        return response()->json($review, Response::HTTP_CREATED);
    }

    public function show(Review $review)
    {
        return response()->json($review, Response::HTTP_OK);
    }

    public function update(Request $request, Review $review)
    {
        $data = $request->validate([
            'content' => 'sometimes|required|string',
        ]);

        if (isset($data['content'])) {
            $analysis = $this->analyzer->analyze($data['content']);
            $review->update([
                'content'   => $data['content'],
                'sentiment' => $analysis['sentiment'],
                'score'     => $analysis['score'],
                'topics'    => $analysis['topics'],
            ]);
        }

        return response()->json($review, Response::HTTP_OK);
    }

    public function destroy(Review $review)
    {
        $review->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

   public function generateVisualReport()
{
    $reviews = Review::all();

    // Sur Windows, on utilise "python"."
    $result = Process::input($reviews->toJson())
        ->run("python " . base_path('scripts/audit_viz.py'));

    if ($result->successful()) {
        return response()->json([
            // asset() génère l'URL publique vers public/storage/audit_chart.png
            'image_url' => asset('storage/audit_chart.png'), 
            'message' => 'Graphique généré avec succès'
        ]);
    }

    // Si ça échoue, on renvoie l'erreur Python précise
    return response()->json([
        'error' => 'Erreur Python : ' . $result->errorOutput()
    ], 500);
}
}
