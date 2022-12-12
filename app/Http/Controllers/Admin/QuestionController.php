<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Question\QuestionUpdateRequest;
use App\Models\Question;
use App\Services\QuestionService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class QuestionController extends Controller
{
    /**
     * @var QuestionService
     */
    private QuestionService $questionService;

    /**
     * @param QuestionService $questionService
     */
    public function __construct(QuestionService $questionService)
    {
        $this->questionService = $questionService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index(): View|Factory|Application
    {
        $questions = Question::orderBy('id', 'desc')->paginate(10);

        return view('admin.question.index', compact('questions'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Question $question
     * @return Application|Factory|View
     */
    public function edit(Question $question): View|Factory|Application
    {
        return view('admin.question.edit', compact('question'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param QuestionUpdateRequest $request
     * @param Question $question
     * @return RedirectResponse
     */
    public function update(QuestionUpdateRequest $request, Question $question): RedirectResponse
    {
        $this->questionService->update($question, $request->validated());

        return redirect()->route('admin.questions.edit', $question)
            ->with('success', __('Question updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Question $question
     * @return RedirectResponse
     */
    public function destroy(Question $question): RedirectResponse
    {
        $this->questionService->destroy($question);

        return redirect()->route('admin.questions.index')
            ->with('success', __('Question deleted successfully.'));
    }
}
