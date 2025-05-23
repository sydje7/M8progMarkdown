<?php

namespace WPForms\Vendor\Core\Tests\Mocking;

use WPForms\Vendor\Core\ApiCall;
use WPForms\Vendor\Core\Client;
use WPForms\Vendor\Core\ClientBuilder;
use WPForms\Vendor\Core\Logger\Configuration\LoggingConfiguration;
use WPForms\Vendor\Core\Logger\Configuration\RequestConfiguration;
use WPForms\Vendor\Core\Logger\Configuration\ResponseConfiguration;
use WPForms\Vendor\Core\Request\Parameters\AdditionalHeaderParams;
use WPForms\Vendor\Core\Request\Parameters\HeaderParam;
use WPForms\Vendor\Core\Request\Parameters\TemplateParam;
use WPForms\Vendor\Core\Response\ResponseHandler;
use WPForms\Vendor\Core\Response\Types\ErrorType;
use WPForms\Vendor\Core\Tests\Mocking\Authentication\FormAuthManager;
use WPForms\Vendor\Core\Tests\Mocking\Authentication\HeaderAuthManager;
use WPForms\Vendor\Core\Tests\Mocking\Authentication\QueryAuthManager;
use WPForms\Vendor\Core\Tests\Mocking\Logger\MockLogger;
use WPForms\Vendor\Core\Tests\Mocking\Other\MockChild1;
use WPForms\Vendor\Core\Tests\Mocking\Other\MockChild2;
use WPForms\Vendor\Core\Tests\Mocking\Other\MockChild3;
use WPForms\Vendor\Core\Tests\Mocking\Other\MockClass;
use WPForms\Vendor\Core\Tests\Mocking\Other\MockException1;
use WPForms\Vendor\Core\Tests\Mocking\Other\MockException2;
use WPForms\Vendor\Core\Tests\Mocking\Response\MockResponse;
use WPForms\Vendor\Core\Tests\Mocking\Types\MockCallback;
use WPForms\Vendor\Core\Tests\Mocking\Types\MockFileWrapper;
use WPForms\Vendor\Core\Types\CallbackCatcher;
use WPForms\Vendor\Core\Utils\JsonHelper;
use WPForms\Vendor\Psr\Log\LoggerInterface;
use WPForms\Vendor\Psr\Log\LogLevel;
class MockHelper
{
    /**
     * @var Client
     */
    private static $client;
    /**
     * @var JsonHelper
     */
    private static $jsonHelper;
    /**
     * @var MockResponse
     */
    private static $response;
    /**
     * @var CallbackCatcher
     */
    private static $callbackCatcher;
    /**
     * @var MockFileWrapper
     */
    private static $fileWrapper;
    /**
     * @var MockFileWrapper
     */
    private static $urlFileWrapper;
    /**
     * @var MockLogger
     */
    private static $logger;
    public static function getClient() : Client
    {
        if (!isset(self::$client)) {
            $clientBuilder = ClientBuilder::init(new MockHttpClient())->converter(new MockConverter())->apiCallback(self::getCallbackCatcher())->loggingConfiguration(self::getLoggingConfiguration())->serverUrls(['Server1' => 'http://my/path:3000/{one}', 'Server2' => 'https://my/path/{two}'], 'Server1')->jsonHelper(self::getJsonHelper())->globalConfig([TemplateParam::init('one', 'v1')->dontEncode(), TemplateParam::init('two', 'v2')->dontEncode(), HeaderParam::init('additionalHead1', 'headVal1'), HeaderParam::init('additionalHead2', 'headVal2')])->globalRuntimeParam(AdditionalHeaderParams::init(['key5' => 890.098]))->globalErrors([\strval(400) => ErrorType::init('Exception num 1', MockException1::class), \strval(401) => ErrorType::init('Exception num 2', MockException2::class), \strval(403) => ErrorType::init('Exception num 3')])->authManagers(["header" => new HeaderAuthManager('someAuthToken', 'accessToken'), "headerWithNull" => new HeaderAuthManager('someAuthToken', null), "headerWithEmpty" => new HeaderAuthManager('', 'accessToken'), "query" => new QueryAuthManager('someAuthToken', 'accessToken'), "queryWithNull" => new QueryAuthManager(null, 'accessToken'), "form" => new FormAuthManager('someAuthToken', 'accessToken'), "formWithNull" => new FormAuthManager('newAuthToken', null)])->userAgent("{language}|{version}|{engine}|{engine-version}|{os-info}")->userAgentConfig(['{language}' => 'my lang', '{version}' => '1.*.*']);
            self::$client = $clientBuilder->build();
            // @phan-suppress-next-next-line PhanPluginDuplicateAdjacentStatement Following duplicated line will
            // call `addUserAgentToGlobalHeaders` again to see test if its added again or not
            self::$client = $clientBuilder->build();
        }
        return self::$client;
    }
    public static function newApiCall() : ApiCall
    {
        return new ApiCall(self::getClient());
    }
    public static function responseHandler() : ResponseHandler
    {
        return self::getClient()->getGlobalResponseHandler();
    }
    public static function getJsonHelper() : JsonHelper
    {
        if (!isset(self::$jsonHelper)) {
            self::$jsonHelper = new JsonHelper([MockClass::class => [MockChild1::class, MockChild2::class, MockChild3::class]], ['disc' => 'my field', 'disc1' => 'This is 1', 'disc2' => 'This is 2'], 'addAdditionalProperty', 'WPForms\\Vendor\\Core\\Tests\\Mocking\\Other');
        }
        return self::$jsonHelper;
    }
    public static function getResponse() : MockResponse
    {
        if (!isset(self::$response)) {
            self::$response = new MockResponse();
        }
        return self::$response;
    }
    public static function getCallback() : MockCallback
    {
        return new MockCallback();
    }
    public static function getCallbackCatcher() : CallbackCatcher
    {
        if (!isset(self::$callbackCatcher)) {
            self::$callbackCatcher = new CallbackCatcher();
        }
        return self::$callbackCatcher;
    }
    public static function getFileWrapper() : MockFileWrapper
    {
        if (!isset(self::$fileWrapper)) {
            $filePath = \realpath(__DIR__ . '/Other/testFile.txt');
            self::$fileWrapper = MockFileWrapper::createFromPath($filePath, 'text/plain', 'My Text');
        }
        return self::$fileWrapper;
    }
    public static function getFileWrapperFromUrl() : MockFileWrapper
    {
        if (!isset(self::$urlFileWrapper)) {
            $filePath = MockFileWrapper::getDownloadedRealFilePath('https://gist.githubusercontent.com/asadali214' . '/0a64efec5353d351818475f928c50767/raw/8ad3533799ecb4e01a753aaf04d248e6702d4947/testFile.txt');
            self::$urlFileWrapper = MockFileWrapper::createFromPath($filePath, 'text/plain', 'My Text');
        }
        return self::$urlFileWrapper;
    }
    public static function getMockLogger() : MockLogger
    {
        if (!isset(self::$logger)) {
            self::$logger = new MockLogger();
        }
        return self::$logger;
    }
    public static function getLoggingConfiguration(?string $logLevel = null, ?bool $maskSensitiveHeaders = null, ?RequestConfiguration $requestConfig = null, ?ResponseConfiguration $responseConfig = null, ?LoggerInterface $logger = null) : LoggingConfiguration
    {
        return new LoggingConfiguration($logger ?? self::getMockLogger(), $logLevel ?? LogLevel::INFO, $maskSensitiveHeaders ?? \true, $requestConfig ?? self::getRequestLoggingConfiguration(), $responseConfig ?? self::getResponseLoggingConfiguration());
    }
    public static function getRequestLoggingConfiguration(bool $includeQueryInPath = \false, bool $logBody = \false, bool $logHeaders = \false, array $headersToInclude = [], array $headersToExclude = [], array $headersToUnmask = []) : RequestConfiguration
    {
        return new RequestConfiguration($includeQueryInPath, $logBody, $logHeaders, $headersToInclude, $headersToExclude, $headersToUnmask);
    }
    public static function getResponseLoggingConfiguration(bool $logBody = \false, bool $logHeaders = \false, array $headersToInclude = [], array $headersToExclude = [], array $headersToUnmask = []) : ResponseConfiguration
    {
        return new ResponseConfiguration($logBody, $logHeaders, $headersToInclude, $headersToExclude, $headersToUnmask);
    }
}
