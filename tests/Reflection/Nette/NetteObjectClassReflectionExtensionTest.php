<?php declare(strict_types = 1);

namespace PHPStan\Reflection\Nette;

class NetteObjectClassReflectionExtensionTest extends \PHPStan\Testing\PHPStanTestCase
{

	/** @var \PHPStan\Broker\Broker */
	private $broker;

	/** @var \PHPStan\Reflection\Nette\NetteObjectClassReflectionExtension */
	private $extension;

	protected function setUp(): void
	{
		$this->broker = $this->createBroker();
		$this->extension = new NetteObjectClassReflectionExtension();
	}

	/**
	 * @return mixed[]
	 */
	public function dataHasMethod(): array
	{
		$data = [];
		$data[] = [
			\PHPStan\SmartObjectChild::class,
			'onPublicEvent',
			true,
		];
		$data[] = [
			\PHPStan\SmartObjectChild::class,
			'onProtectedEvent',
			false,
		];
		$data[] = [
			'PHPStan\NetteObjectChild',
			'onPublicEvent',
			true,
		];
		$data[] = [
			'PHPStan\NetteObjectChild',
			'onProtectedEvent',
			false,
		];
		return $data;
	}

	/**
	 * @dataProvider dataHasMethod
	 * @param string $className
	 * @param string $method
	 * @param bool $result
	 */
	public function testHasMethod(string $className, string $method, bool $result): void
	{
		if (!class_exists('Nette\LegacyObject')) {
			self::markTestSkipped(sprintf('%s does not exist.', $className));
		}
		$classReflection = $this->broker->getClass($className);
		self::assertSame($result, $this->extension->hasMethod($classReflection, $method));
	}

	/**
	 * @return mixed[]
	 */
	public function dataHasProperty(): array
	{
		$data = [];
		$data[] = [
			\PHPStan\SmartObjectChild::class,
			'foo',
			false,
		];
		$data[] = [
			'PHPStan\NetteObjectChild',
			'staticProperty',
			false,
		];
		$data[] = [
			'PHPStan\NetteObjectChild',
			'publicProperty',
			true,
		];
		$data[] = [
			'PHPStan\NetteObjectChild',
			'protectedProperty',
			false,
		];

		return $data;
	}

	/**
	 * @dataProvider dataHasProperty
	 * @param string $className
	 * @param string $property
	 * @param bool $result
	 */
	public function testHasProperty(string $className, string $property, bool $result): void
	{
		if (!class_exists('Nette\LegacyObject')) {
			self::markTestSkipped(sprintf('%s does not exist.', $className));
		}
		$classReflection = $this->broker->getClass($className);
		self::assertSame($result, $this->extension->hasProperty($classReflection, $property));
	}

}
