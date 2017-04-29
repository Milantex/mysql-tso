<?php
    namespace Milantex\TSO;

    /**
     * The FieldStructure class
     *
     * @author Milantex
     */
    class FieldStructure {
        /**
         * The name of the field
         * @var string
         */
        private $name;

        /**
         * The field type. There are a number of predefined types.
         * @var string
         */
        private $type;

        /**
         * The total length of the field content.
         * Some field types do not specify lengths. I.e. the TIMESTAMP.
         * For fields like these, the length is 0.
         * @var int
         */
        private $length = 0;

        /**
         * The number of decimal places.
         * Some field types do not specify this, such as INTEGER.
         * @var int
         */
        private $decimalPlaces = 0;

        /**
         * The default value for this field.
         * The default value is always stored as a string.
         * If there is no default value, an empty string is returned.
         * @var string
         */
        private $defaultValue = '';

        /**
         * The comment associated with this field.
         * @var string
         */
        private $comment = '';

        /**
         * The collation name of the content of this field.
         * Only fields of textual types have collations defined.
         * Otherwise, this field is an empty string.
         * @var string
         */
        private $collation = '';

        /**
         * True if this field is indexed.
         * This can be for primary and foreign keys, and for
         * unique or normal indices.
         * @var bool
         */
        private $isIndexed = FALSE;

        /**
         * True if this field can be NULL.
         * @var bool
         */
        private $isNullable = TRUE;

        /**
         * True is this field is a part of the primary key.
         * @var bool
         */
        private $isPrimaryKey = FALSE;

        /**
         * True is this field is a part of the foreign key.
         * @var bool
         */
        private $isForeignKey = FALSE;

        /**
         * True is this field is a part of the unique key.
         * @var bool
         */
        private $isUniqueKey = FALSE;

        /**
         * True is this field is unsigned.
         * This property can be True only for types that support
         * unsigned flags, such as INTEGER etc.
         * @var bool
         */
        private $isUnsigned = FALSE;

        /**
         * True is this field is assigned a default value automatically
         * from a sequence (like primary keys with AUTO_INCREMENT flag).
         * @var bool
         */
        private $isAutoIncrementable = FALSE;

        /**
         * Returns the name of the field
         * @return string
         */
        public function getName() : string {
            return $this->name;
        }

        /**
         * Returns the type of the field.
         * There are a number of predefined types.
         * @return string
         */
        public function getType() : string {
            return $this->type;
        }

        /**
         * Returns the total length of the field content.
         * Some field types do not specify lengths. I.e. the TIMESTAMP.
         * For fields like these, the length is 0.
         * @return int
         */
        public function getLength() : int {
            return $this->length;
        }

        /**
         * Returns the number of decimal places.
         * Some field types do not specify this, such as INTEGER.
         * @return int
         */
        public function getDecimalPlaces() : int {
            return $this->decimalPlaces;
        }

        /**
         * Returns the default value for this field.
         * The default value is always stored as a string.
         * If there is no default value, an empty string is returned.
         * @return string
         */
        public function getDefaultValue() : string {
            return $this->defaultValue;
        }

        /**
         * Returns the comment associated with this field.
         * @return string
         */
        public function getComment() : string {
            return $this->comment;
        }

        /**
         * Returns the collation name of the content of this field.
         * Only fields of textual types have collations defined.
         * Otherwise, this field is an empty string.
         * @return string
         */
        public function getCollation() : string {
            return $this->collation;
        }

        /**
         * Returns True if this field can be NULL.
         * @return bool
         */
        public function isNullable() : bool {
            return $this->isNullable;
        }

        /**
         * Returns True if this field is indexed.
         * This can be for primary and foreign keys, and for
         * unique or normal indices.
         * @return bool
         */
        public function isIndexed() : bool {
            return $this->isIndexed;
        }

        /**
         * Returns True is this field is a part of the primary key.
         * @return bool
         */
        public function isPrimaryKey() : bool {
            return $this->isPrimaryKey;
        }

        /**
         * Returns True is this field is a part of the foreign key.
         * @return bool
         */
        public function isForeignKey() : bool {
            return $this->isForeignKey;
        }

        /**
         * Returns True is this field is a part of the unique key.
         * @return bool
         */
        public function isUniqueKey() : bool {
            return $this->isUniqueKey;
        }

        /**
         * Returns True is this field is unsigned.
         * This property can be True only for types that support
         * unsigned flags, such as INTEGER etc.
         * @return bool
         */
        public function isUnsigned() : bool {
            return $this->isUnsigned;
        }

        /**
         * Returns True is this field is assigned a default value automatically
         * from a sequence (like primary keys with AUTO_INCREMENT flag).
         * @return bool
         */
        public function isAutoIncrementable() : bool {
            return $this->isAutoIncrementable;
        }

        /**
         * The FieldStructure class constructor preforms analysis of the
         * field data object and fills out this object's table field
         * information properties.
         * @param \stdClass $fieldData
         */
        function __construct(\stdClass &$fieldData) {
            $this->name = $fieldData->Field;
            $this->collation = $fieldData->Collation;
            $this->comment = $fieldData->Comment;
            $this->defaultValue = $fieldData->Default;

            $this->doType($fieldData);
            $this->doKeysAndIndices($fieldData);
            $this->doExtras($fieldData);
        }

        /**
         * Checks the type of the field and sets appropriate properties
         * and values, such as the key, length and decimal places.
         * @param \stdClass $fieldData
         */
        private function doType(\stdClass &$fieldData) {
            if (preg_match('/unsigned/', $fieldData->Type)) {
                $this->isUnsigned = TRUE;
            }

            $parts = [];
            preg_match('/^([a-z]+)(?:\(([0-9]+)(?:,([0-9]+))?\))?/', $fieldData->Type, $parts);

            $this->type = strtoupper($parts[1]);
            $this->length = $parts[2] ?? '0';
            $this->decimalPlaces = $parts[3] ?? '0';
        }

        /**
         * Checks keys and indices and sets appropriate properties.
         * @param \stdClass $fieldData
         */
        private function doKeysAndIndices(\stdClass &$fieldData) {
            $this->isNullable = FALSE;

            if ($fieldData->Null == 'YES') {
                $this->isNullable = TRUE;
            }

            if ($fieldData->Key == 'PRI') {
                $this->isPrimaryKey = TRUE;
                $this->isIndexed = TRUE;
            }

            if ($fieldData->Key == 'UNI') {
                $this->isUniqueKey = TRUE;
                $this->isIndexed = TRUE;
            }

            if ($fieldData->Key == 'MUL') {
                $this->isIndexed = TRUE;
            }
        }

        /**
         * Checks the Extras information about the field and sets
         * appropriate properties. Currently, this is only the flag
         * for AUTO_INCREMENT sequence value.
         * @param \stdClass $fieldData
         */
        private function doExtras(\stdClass &$fieldData) {
            if (preg_match('/auto_increment/', $fieldData->Extra)) {
                $this->isAutoIncrementable = TRUE;
            }
        }
    }
