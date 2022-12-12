<?php

use App\Models\Question;
use App\Services\QuestionService;
use Tests\TestCase;
use Symfony\Component\HttpFoundation\Response;

class ContactsTest extends TestCase
{
    /**
     * @return void
     */
    public function testSuccessfulQuestionPageVisit(): void
    {
        $this->get(route('contacts.index'))->assertStatus(Response::HTTP_OK);
    }

    /**
     * @return void
     */
    public function testSuccessfulQuestionSubmit(): void
    {
        $data = $this->getQuestionData();

        $this->post(route('contacts.submit'), $data)->assertStatus(Response::HTTP_FOUND);

        $createdQuestion = Question::all()->last();

        $this->assertEquals($data['email'], $createdQuestion->email);
        $this->assertEquals($data['question_text'], $createdQuestion->question_text);
        $this->assertEquals(false, $createdQuestion->verified);
        $this->assertEquals('127.0.0.1', $createdQuestion->ip);
    }

    /**
     * @return void
     */
    public function testUnsuccessfulQuestionSubmit(): void
    {
        $data = ['email' => 'wrong email', 'question_text' => ''];

        $this->post(route('contacts.submit'), $data)
            ->assertStatus(Response::HTTP_FOUND)->assertSessionHasErrors(array_keys($data));

        $questionsQuantity = Question::all()->count();
        $this->assertEquals(0, $questionsQuantity);
    }

    /**
     * @return void
     */
    public function testUnsuccessfulQuestionFastSecondTimeSubmit(): void
    {
        $data = $this->getQuestionData();

        $this->post(route('contacts.submit'), $data)->assertStatus(Response::HTTP_FOUND);
        $this->post(route('contacts.submit'), $data)->assertStatus(Response::HTTP_FOUND);

        $questionsQuantity = Question::all()->count();
        $this->assertEquals(1, $questionsQuantity);
    }

    /**
     * @return void
     */
    public function testUnsuccessfulQuestionSecondTimeSubmit(): void
    {
        $data = $this->getQuestionData();

        $this->post(route('contacts.submit'), $data)->assertStatus(Response::HTTP_FOUND);

        // travel forward
        $this->travel(QuestionService::SECONDS_TO_WAIT_FOR_NEXT_MESSAGE_WITH_THE_SAME_IP + 1)->seconds();

        $this->post(route('contacts.submit'), $data)->assertStatus(Response::HTTP_FOUND);

        $questionsQuantity = Question::all()->count();
        $this->assertEquals(2, $questionsQuantity);
    }

    private function getQuestionData(): array
    {
        return ['email' => 'test@test.com', 'question_text' => 'How are you?'];
    }
}
