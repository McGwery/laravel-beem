<?php

namespace Bryceandy\Beem\Tests\Feature;

use Bryceandy\Beem\Facades\Beem;
use Bryceandy\Beem\Tests\TestCase;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Http;

class SmsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Fake all sms requests to the API
        Http::fake([
           'https://apisms.beem.africa/v1/send' => Http::response(json_decode(
               file_get_contents(__DIR__ . '/../stubs/sms_response_200.json'),
               true
           )),
            'https://apisms.beem.africa/public/v1/vendors/balance' => Http::response(json_encode(
                file_get_contents(__DIR__ . '/../stubs/sms_balance_response_200.json'),
                true
            )),
            'https://apisms.beem.africa/public/v1/sender-names' => Http::response(json_encode(
                file_get_contents(__DIR__ . '/../stubs/sms_sender_names_response_200.json'),
                true
            )),
        ]);
    }

    /**
     * Define environment setup.
     *
     * @param Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('beem.api_key', '12345');
        $app['config']->set('beem.secret_key', 'abc');
    }

    /** @test */
    public function it_can_send_an_sms()
    {
        $request = Beem::sms(
            'Your verification code: 34990',
            [['recipient_id' => (string) now()->timestamp, 'dest_addr' => '255753820520']]
        );

        $this->assertTrue($request->successful());
    }

    /** @test */
    public function it_can_send_a_scheduled_sms()
    {
        $request = Beem::smsWithSchedule(
            'Your new message',
            [['recipient_id' => (string) now()->timestamp, 'dest_addr' => '255753820520']],
            now()
        );

        $this->assertTrue($request->successful());
    }

    /** @test */
    public function it_can_check_sms_balance()
    {
        $request = Beem::smsBalance();

        $this->assertTrue($request->successful());
    }

    /** @test */
    public function it_can_fetch_sender_names()
    {
        $request = Beem::smsSenderNames();

        $this->assertTrue($request->successful());
    }
}
