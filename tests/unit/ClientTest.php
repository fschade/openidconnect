<?php

namespace OCA\OpenIdConnect\Tests\Unit;

use OCA\OpenIdConnect\Client;
use OCP\IConfig;
use OCP\ISession;
use OCP\IURLGenerator;
use PHPUnit\Framework\MockObject\MockObject;
use Test\TestCase;

class ClientTest extends TestCase {

	/**
	 * @var MockObject | Client
	 */
	private $client;
	/**
	 * @var MockObject | ISession
	 */
	private $session;
	/**
	 * @var MockObject | IURLGenerator
	 */
	private $urlGenerator;
	/**
	 * @var MockObject | IConfig
	 */
	private $config;

	protected function setUp(): void {
		parent::setUp();
		$this->config = $this->createMock(IConfig::class);
		$this->urlGenerator = $this->createMock(IURLGenerator::class);
		$this->session = $this->createMock(ISession::class);
//		$this->client = new Client($this->config, $this->urlGenerator, $this->session);
		$this->client = $this->getMockBuilder(Client::class)
			->setConstructorArgs([$this->config, $this->urlGenerator, $this->session])
			->setMethods(['fetchURL'])
			->getMock();
	}

	public function testGetConfig(): void {
		$this->config->expects(self::once())->method('getSystemValue')->willReturn('foo');
		$return = $this->client->getOpenIdConfig();
		self::assertEquals('foo', $return);
	}

	public function testGetWellKnown(): void {
		$this->client->setProviderURL('https://example.net');
		$this->client->expects(self::once())->method('fetchURL')->with('https://example.net/.well-known/openid-configuration')->willReturn('{"foo": "bar"}');
		$return = $this->client->getWellKnownConfig();
		self::assertEquals((object)['foo' => 'bar'], $return);
	}
}
