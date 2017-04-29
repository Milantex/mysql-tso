<?php
    use Milantex\TSO\FieldStructure;

    class FieldStructureTest extends PHPUnit_Framework_TestCase {
        private $unsignedNumberAutoIncrementPrimaryKey;
        private $unsignedNumberUnique;
        private $signedNumberNormalIndex;
        private $decimalCanBeNullNoIndexDefault;
        private $stringWithCollation;

        private function doSetUpUnsignedNumberAutoIncrementPrimaryKey() {
            $data = (object) [
                'Field' => 'page_id',
                'Type'  => 'int(10) unsigned',
                'Collation' => '',
                'Null' => 'NO',
                'Key' => 'PRI',
                'Default' => '',
                'Extra' => 'auto_increment',
                'Privileges' => 'select,insert,update,references',
                'Comment' => 'ID number'
            ];

            $this->unsignedNumberAutoIncrementPrimaryKey = new FieldStructure($data);
        }

        private function doSetUpUnsignedNumberUnique() {
            $data = (object) [
                'Field' => 'page_id',
                'Type'  => 'int(10) unsigned',
                'Collation' => '',
                'Null' => 'NO',
                'Key' => 'UNI',
                'Default' => '',
                'Extra' => '',
                'Privileges' => 'select,insert,update,references',
                'Comment' => ''
            ];

            $this->unsignedNumberUnique = new FieldStructure($data);
        }

        private function doSetUpSignedNumberNormalIndex() {
            $data = (object) [
                'Field' => 'page_id',
                'Type'  => 'int(10)',
                'Collation' => '',
                'Null' => 'NO',
                'Key' => 'MUL',
                'Default' => '',
                'Extra' => '',
                'Privileges' => 'select,insert,update,references',
                'Comment' => ''
            ];

            $this->signedNumberNormalIndex = new FieldStructure($data);
        }

        private function doSetUpDecimalCanBeNullNoIndexDefault() {
            $data = (object) [
                'Field' => 'price',
                'Type'  => 'decimal(10,4)',
                'Collation' => '',
                'Null' => 'YES',
                'Key' => '',
                'Default' => '2',
                'Extra' => '',
                'Privileges' => 'select,insert,update,references',
                'Comment' => ''
            ];

            $this->decimalCanBeNullNoIndexDefault = new FieldStructure($data);
        }

        private function doSetUpStringWithCollation() {
            $data = (object) [
                'Field' => 'name',
                'Type'  => 'varchar(128)',
                'Collation' => 'utf8_unicode_ci',
                'Null' => 'NO',
                'Key' => '',
                'Default' => '',
                'Extra' => '',
                'Privileges' => 'select,insert,update,references',
                'Comment' => ''
            ];

            $this->stringWithCollation = new FieldStructure($data);
        }

        public function setUp() {
            $this->doSetUpUnsignedNumberAutoIncrementPrimaryKey();
            $this->doSetUpUnsignedNumberUnique();
            $this->doSetUpSignedNumberNormalIndex();
            $this->doSetUpDecimalCanBeNullNoIndexDefault();
            $this->doSetUpStringWithCollation();
        }

        public function testGetName() {
            $this->assertSame('price', $this->decimalCanBeNullNoIndexDefault->getName());
        }

        public function testGetType() {
            $this->assertSame('INT', $this->unsignedNumberAutoIncrementPrimaryKey->getType());
        }

        public function testGetLength() {
            $this->assertEquals(10, $this->signedNumberNormalIndex->getLength());
        }

        public function testGetDecimalPlaces() {
            $this->assertEquals(4, $this->decimalCanBeNullNoIndexDefault->getDecimalPlaces());
        }

        public function testGetDefaultValue() {
            $this->assertEquals(2, $this->decimalCanBeNullNoIndexDefault->getDefaultValue());
        }

        public function testGetComment() {
            $this->assertSame('ID number', $this->unsignedNumberAutoIncrementPrimaryKey->getComment());
        }

        public function testGetCollation() {
            $this->assertSame('utf8_unicode_ci', $this->stringWithCollation->getCollation());
        }

        public function testIsNullable() {
            $this->assertTrue($this->decimalCanBeNullNoIndexDefault->isNullable());
            $this->assertFalse($this->unsignedNumberAutoIncrementPrimaryKey->isNullable());
        }

        public function testIsIndexed() {
            $this->assertTrue($this->unsignedNumberAutoIncrementPrimaryKey->isIndexed());
            $this->assertFalse($this->stringWithCollation->isIndexed());
        }

        public function testIsPrimaryKey() {
            $this->assertTrue($this->unsignedNumberAutoIncrementPrimaryKey->isPrimaryKey());
            $this->assertFalse($this->stringWithCollation->isPrimaryKey());
        }

        public function testIsForeignKey() {
            # Foreign key check is not implemented yet, so it always returns false
            $this->assertFalse($this->unsignedNumberAutoIncrementPrimaryKey->isForeignKey());
        }

        public function testIsUniqueKey() {
            $this->assertTrue($this->unsignedNumberUnique->isUniqueKey());
            $this->assertFalse($this->stringWithCollation->isUniqueKey());
        }

        public function testIsUnsigned() {
            $this->assertTrue($this->unsignedNumberAutoIncrementPrimaryKey->isUnsigned());
            $this->assertFalse($this->signedNumberNormalIndex->isUnsigned());
        }

        public function testIsAutoIncrementable() {
            $this->assertTrue($this->unsignedNumberAutoIncrementPrimaryKey->isAutoIncrementable());
            $this->assertFalse($this->signedNumberNormalIndex->isAutoIncrementable());
        }
    }
