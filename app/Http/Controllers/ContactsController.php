<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuestionRequest;
use App\Services\QuestionService;
use DomainException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class ContactsController extends Controller
{
    private QuestionService $questionService;

    /**
     * @param QuestionService $questionService
     */
    public function __construct(QuestionService $questionService)
    {
        $this->questionService = $questionService;
    }

    /**
     * @return Factory|View|Application
     */
    public function index(): Factory|View|Application
    {
        return view('contacts.form');
    }

    /**
     * @param QuestionRequest $questionRequest
     * @return RedirectResponse
     */
    public function submit(QuestionRequest $questionRequest): RedirectResponse
    {
        try {
            $this->questionService->create($questionRequest->validated(), $questionRequest->ip(), false);
        } catch (DomainException $e) {
            return redirect()->back()->withInput()->with(['error' => $e->getMessage()]);
        }

        return redirect()->back()->with(['success' => __('Your question has been submitted successfully.')]);
    }
}
