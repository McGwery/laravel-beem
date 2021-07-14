<?php

namespace Bryceandy\Beem\Tests\Feature;

use Bryceandy\Beem\Exceptions\ConfigurationUnavailableException;
use Bryceandy\Beem\Facades\Beem;
use Bryceandy\Beem\Tests\TestCase;

class SmsTest extends TestCase
{
    /** @test */
    public function it_requires_authentication_credentials_to_send_sms()
    {
        $this->expectException(ConfigurationUnavailableException::class);

        Beem::sms(
            'Your verification code: 34990',
            [['recipient_id' => (string) now()->timestamp, 'dest_addr' => '255753820520']]
        );
    }
}
