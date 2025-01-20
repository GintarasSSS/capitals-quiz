<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostCapitalAnswerRequest;
use App\Repositories\CapitalsQuizRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class QuizController extends Controller
{
    public function __construct(private readonly CapitalsQuizRepository $repository)
    {
    }

    public function index(): View
    {
        return view('index');
    }

    public function getRandomCountry(): JsonResponse
    {
        return response()->json(
            $this->repository->getRandomCountry()
        );
    }

    public function postCapitalAnswer(PostCapitalAnswerRequest $request): JsonResponse
    {
        return response()->json(
            $this->repository->getCapitalAnswer($request->validated('capital'))
        );
    }

    public function exit(): JsonResponse
    {
        return response()->json([
            'success' => $this->repository->exitQuiz()
        ]);
    }
}
