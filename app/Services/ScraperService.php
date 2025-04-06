<?php

namespace App\Services;

use App\Exceptions\ScrapeFailed;
use App\Models\Account;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;

/**
 * @codeCoverageIgnore
 * This class is not tested because it uses a real browser and network requests.
 */
class ScraperService
{
    /**
     * Scrape Onlyfans account by username.
     */
    public function scrape(string $username): Account
    {
        $driver = $this->getDriver();

        try {
            // open target page
            $driver->get('https://onlyfans.com/'.$username);
            $driver->wait(10)->until(
                WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::cssSelector('.g-user-name'))
            );

            // find <script type="application/ld+json">
            $script = $driver->findElement(WebDriverBy::cssSelector('script[type="application/ld+json"]'));
            $json = json_decode($script->getDomProperty('innerHTML'), true);

            // check for json errors
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Failed to parse JSON: '.json_last_error_msg());
            }

            // fill account data
            $account = new Account([
                'username' => $username,
                'name' => $json['mainEntity']['name'] ?? '',
                'likes' => $json['mainEntity']['interactionStatistic']['0']['userInteractionCount'] ?? 0,
                'bio' => $json['mainEntity']['description'] ?? '',
            ]);
        } catch (\Exception $e) {
            $driver->quit();
            throw new ScrapeFailed(
                message: 'Scrape failed: '.$e->getMessage(),
                previous: $e,
            );
        }

        $driver->quit();

        return $account;
    }

    protected function getDriver(): RemoteWebDriver
    {
        return RemoteWebDriver::create(
            selenium_server_url: 'http://selenium:4444/wd/hub',
            desired_capabilities: DesiredCapabilities::chrome()->setCapability(
                name: ChromeOptions::CAPABILITY,
                value: (new ChromeOptions())->addArguments([
                    '--disable-gpu',
                    '--headless=new',
                    '--start-maximized',
                    '--disable-search-engine-choice-screen',
                    '--disable-smooth-scrolling',
                    '--user-agent=Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/121.0.0.0 Safari/537.36',
                ]),
            ),
        );
    }
}
