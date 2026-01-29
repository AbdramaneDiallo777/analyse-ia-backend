<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ReviewAnalyzer;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AnalyzeController extends Controller
{
    public function __construct(private ReviewAnalyzer $analyzer) {}

    public function __invoke(Request $request)
    {
        $data = $request->validate([
            'text' => 'required|string',
        ]);

        $result = $this->analyzer->analyze($data['text']);

        return response()->json([
            'sentiment' => $result['sentiment'],
            'score'     => $result['score'],
            'topics'    => $result['topics'],
        ], Response::HTTP_OK);
    }
}

