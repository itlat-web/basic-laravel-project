<?php

namespace Admin;

use App\Models\Question;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class QuestionTest extends TestCase
{
    /**
     * @return void
     */
    public function testUnsuccessfulSimpleGuestVisit(): void
    {
        // questions index page - must be 302 redirect
        $this->get(route('admin.questions.index'))->assertStatus(Response::HTTP_FOUND);
    }

    /**
     * @return void
     */
    public function testSuccessfulAuthorizedUserVisit(): void
    {
        $this->actingAsAuthorizedUser();

        // questions index page - must be 200 status
        $this->get(route('admin.questions.index'))->assertStatus(Response::HTTP_OK);
    }

    /**
     * @return void
     */
    public function testSuccessfulQuestionUpdate(): void
    {
        $this->actingAsAuthorizedUser();

        $question = $this->createQuestion();
        $this->assertEquals(false, $question->verified);

        // send question update request
        $questionUpdateResponse = $this->patch(route('admin.questions.update', $question), ['verified' => true]);

        $questionUpdateResponse->assertStatus(Response::HTTP_FOUND);

        $updatedQuestion = Question::where('id', $question->id)->first();
        $this->assertEquals(true, $updatedQuestion->verified);
    }

    /**
     * @return void
     */
    public function testUnsuccessfulQuestionUpdate(): void
    {
        $this->actingAsAuthorizedUser();

        $question = $this->createQuestion();
        $this->assertEquals(false, $question->verified);

        // send question update request
        $questionUpdateResponse = $this->patch(route('admin.questions.update', $question));

        $questionUpdateResponse->assertStatus(Response::HTTP_FOUND)->assertSessionHasErrors(['verified']);

        $notUpdatedQuestion = Question::where('id', $question->id)->first();
        $this->assertEquals(false, $notUpdatedQuestion->verified);
    }

    /**
     * @return void
     */
    public function testSuccessfulQuestionDelete(): void
    {
        $this->actingAsAuthorizedUser();

        $question = $this->createQuestion();

        // send question delete request
        $questionDeleteResponse = $this->delete(route('admin.questions.destroy', $question));

        // there is no requested question (404 error)
        $questionDeleteResponse->assertStatus(Response::HTTP_FOUND);

        $questionsQuantity = Question::all()->count();
        $this->assertEquals(0, $questionsQuantity);
    }

    /**
     * @return void
     */
    public function testUnsuccessfulQuestionDelete(): void
    {
        $this->actingAsAuthorizedUser();

        // send question delete request
        $questionDeleteResponse = $this->delete(route('admin.questions.destroy', ['question' => PHP_INT_MAX]));

        // there is no requested question (404 error)
        $questionDeleteResponse->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /**
     * @return Question
     */
    private function createQuestion(): Question
    {
        $this->post(route('contacts.submit'), ['email' => 'test@test.com', 'question_text' => 'How are you?']);

        return Question::all()->last();
    }
}
