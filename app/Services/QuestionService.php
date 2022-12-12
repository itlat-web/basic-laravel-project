<?php

namespace App\Services;

use App\Models\Question;
use Carbon\Carbon;
use DomainException;

class QuestionService
{
    public const SECONDS_TO_WAIT_FOR_NEXT_MESSAGE_WITH_THE_SAME_IP = 180;

    /**
     * @param array $data ['email' => 'string', 'question_text' => 'string']
     * @param string $ip
     * @param bool $verified
     * @return Question
     */
    public function create(array $data, string $ip, bool $verified): Question
    {
        $timeBefore = Carbon::now()->subSeconds(self::SECONDS_TO_WAIT_FOR_NEXT_MESSAGE_WITH_THE_SAME_IP);
        $questionsQty = Question::where('ip', $ip)->where('created_at', '>=', $timeBefore)->count();

        if ($questionsQty > 0) {
            $message = sprintf(
                __('You are allowed to ask question only 1 time per %d seconds.'),
                self::SECONDS_TO_WAIT_FOR_NEXT_MESSAGE_WITH_THE_SAME_IP
            );
            throw new DomainException($message);
        }

        return Question::create([
            'email'         => $data['email'],
            'question_text' => $data['question_text'],
            'ip'            => $ip,
            'verified'      => $verified
        ]);
    }

    /**
     * @param Question $question
     * @param array $data ['verified' => bool]
     * @return void
     */
    public function update(Question $question, array $data): void
    {
        $question->update(['verified' => $data['verified']]);
    }

    /**
     * @param Question $question
     * @return void
     */
    public function destroy(Question $question): void
    {
        $question->delete();
    }
}
