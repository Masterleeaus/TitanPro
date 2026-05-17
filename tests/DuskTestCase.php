<?php

namespace Tests;

use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Laravel\Dusk\TestCase as BaseTestCase;

abstract class DuskTestCase extends BaseTestCase
{
    use DatabaseMigrations;

    /**
     * Prepare for Dusk test execution.
     *
     * @beforeClass
     */
    public static function prepare(): void
    {
        if (! static::runningInSail()) {
            static::startChromeDriver(['--port=9515']);
        }
    }

    /**
     * Create the RemoteWebDriver instance.
     */
    protected function driver(): RemoteWebDriver
    {
        $options = (new ChromeOptions)->addArguments(array_filter([
            $this->shouldStartMaximized() ? '--start-maximized' : '--window-size=1920,1080',
            '--disable-search-engine-choice-screen',
            '--disable-smooth-scrolling',
            $this->hasHeadlessDisabled() ? null : '--headless=new',
        ]));

        return RemoteWebDriver::create(
            $_ENV['DUSK_DRIVER_URL'] ?? env('DUSK_DRIVER_URL') ?? 'http://localhost:9515',
            DesiredCapabilities::chrome()->setCapability(
                ChromeOptions::CAPABILITY,
                $options
            )
        );
    }

    /**
     * Activate true `prefers-reduced-motion: reduce` via the Chrome DevTools Protocol.
     *
     * Calls `Emulation.setEmulatedMedia` so the browser genuinely evaluates
     * `@media (prefers-reduced-motion: reduce)` rules, exercising the real media-query
     * code path rather than injecting a CSS override stylesheet.
     *
     * Requires ChromeDriver ≥ 86 (CDP Emulation domain) and
     * php-webdriver/webdriver ≥ 1.15.2 (the version locked in composer.lock).
     */
    protected function withReducedMotion(Browser $browser): void
    {
        $browser->driver->executeCdpCommand('Emulation.setEmulatedMedia', [
            'features' => [
                ['name' => 'prefers-reduced-motion', 'value' => 'reduce'],
            ],
        ]);
    }

    /**
     * Reset the `prefers-reduced-motion` emulation back to the system default.
     *
     * Call this after `withReducedMotion()` to prevent the emulation from
     * leaking into subsequent tests.
     */
    protected function resetMotionEmulation(Browser $browser): void
    {
        $browser->driver->executeCdpCommand('Emulation.setEmulatedMedia', [
            'features' => [],
        ]);
    }
}
