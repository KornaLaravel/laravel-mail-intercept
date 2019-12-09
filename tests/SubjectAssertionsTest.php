<?php

namespace Tests;

use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\WithFaker;
use KirschbaumDevelopment\MailIntercept\WithMailInterceptor;

class SubjectAssertionsTest extends TestCase
{
    use WithFaker;
    use WithMailInterceptor;

    public function testMailSubject()
    {
        $this->interceptMail();

        $subject = $this->faker->sentence;

        Mail::send([], [], function ($message) use ($subject) {
            $message->subject($subject);
        });

        $mail = $this->interceptedMail()->first();

        $this->assertMailSubject($subject, $mail);
    }

    public function testMailSubjectOnMultipleEmails()
    {
        $this->interceptMail();

        $emailCount = mt_rand(1, 5);

        $subject = $this->faker->sentence;

        for ($i = 0; $i < $emailCount; $i++) {
            Mail::send([], [], function ($message) use ($subject) {
                $message->subject($subject);
            });
        }

        $mails = $this->interceptedMail();

        $this->assertCount($emailCount, $mails);

        foreach ($mails as $mail) {
            $this->assertMailSubject($subject, $mail);
        }
    }

    public function testMailNotSubject()
    {
        $this->interceptMail();

        $subject = $this->faker->unique()->sentence;

        Mail::send([], [], function ($message) {
            $message->subject($this->faker->unique()->sentence);
        });

        $mail = $this->interceptedMail()->first();

        $this->assertMailNotSubject($subject, $mail);
    }

    public function testMailNotSubjectOnMultipleEmails()
    {
        $this->interceptMail();

        $emailCount = mt_rand(1, 5);

        $subject = $this->faker->unique()->sentence;

        for ($i = 0; $i < $emailCount; $i++) {
            Mail::send([], [], function ($message) {
                $message->subject($this->faker->unique()->sentence);
            });
        }

        $mails = $this->interceptedMail();

        $this->assertCount($emailCount, $mails);

        foreach ($mails as $mail) {
            $this->assertMailNotSubject($subject, $mail);
        }
    }
}
