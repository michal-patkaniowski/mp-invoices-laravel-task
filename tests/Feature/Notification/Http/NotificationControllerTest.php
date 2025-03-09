<?php

declare(strict_types=1);

namespace Tests\Feature\Notification\Http;

use Illuminate\Foundation\Testing\WithFaker;
use Modules\Invoices\Domain\Models\Invoice;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class NotificationControllerTest extends TestCase
{
    use WithFaker;

    protected function setUp(): void
    {
        $this->setUpFaker();

        parent::setUp();
    }

    #[DataProvider('hookActionProvider')]
    public function testHook(string $action): void
    {
        $tempInvoice = Invoice::firstOrCreate([
            'id' => $this->faker->unique()->uuid
        ], [
            'status' => 'sending',
            'customer_name' => 'test customer',
            'customer_email' => 'test email'
        ]);

        if ($tempInvoice->wasRecentlyCreated) {
            $tempInvoice->save();
        }

        $uri = route('notification.hook', [
            'action' => $action,
            'reference' => $tempInvoice->id,
        ]);

        $this->getJson($uri)->assertOk();

        $tempInvoice->delete();
    }

    public function testInvalid(): void
    {
        $params = [
            'action' => 'dummy',
            'reference' => $this->faker->numberBetween(),
        ];

        $uri = route('notification.hook', $params);
        $this->getJson($uri)->assertNotFound();
    }

    public static function hookActionProvider(): array
    {
        return [
            ['delivered'],
            ['dummy'],
        ];
    }
}
