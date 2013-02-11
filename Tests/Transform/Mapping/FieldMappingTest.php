<?php
namespace Oro\Bundle\DataFlowBundle\Tests\Model\Mapping;

use Oro\Bundle\DataFlowBundle\Transform\Mapping\FieldMapping;

/**
 * Test related class
 *
 * @author    Romain Monceau <romain@akeneo.com>
 * @copyright 2012 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/MIT MIT
 *
 */
class FieldMappingTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var FieldMappping
     */
    protected $field;

    /**
     * Create a Field Mapping
     *
     * @return \Oro\Bundle\DataFlowBundle\Model\Mapping\FieldMappping
     */
    protected function createFieldMapping()
    {
        $this->field = new FieldMapping();

        return $this->field;
    }

    /**
     * Test get/set methods of related class
     */
    public function testGetterSetter()
    {
        $this->createFieldMapping()
             ->setSource('test-source')
             ->setDestination('test-destination')
             ->setIsIdentifier(true);

        $this->assertEquals('test-source', $this->field->getSource());
        $this->assertEquals('test-destination', $this->field->getDestination());
        $this->assertTrue($this->field->getIsIdentifier());
    }
}
