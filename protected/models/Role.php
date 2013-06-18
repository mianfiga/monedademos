<?php

/**
 * This is the model class for table "{{role}}".
 *
 * The followings are the available columns in table '{{role}}':
 * @property string $actor_id
 * @property string $part_id
 * @property string $added
 * @property string $updated
 * @property string $deleted
 *
 * The followings are the available model relations:
 * @property Entity $actor
 * @property Entity $part
 */
class Role extends RoleBase {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return RoleBase the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{role}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('actor_id, part_id', 'length', 'max' => 11),
            array('added, updated, deleted', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('actor_id, part_id, added, updated, deleted', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'actor' => array(self::BELONGS_TO, 'Entity', 'actor_id'),
			'part' => array(self::BELONGS_TO, 'Entity', 'part_id'),
		);
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'actor_id' => 'Actor',
            'part_id' => 'Part',
            'added' => 'Added',
            'updated' => 'Updated',
            'deleted' => 'Deleted',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('actor_id', $this->actor_id, true);
        $criteria->compare('part_id', $this->part_id, true);
        $criteria->compare('added', $this->added, true);
        $criteria->compare('updated', $this->updated, true);
        $criteria->compare('deleted', $this->deleted, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

    protected function beforeSave() {
        if (parent::beforeSave()) {

            if ($this->isNewRecord) {
                $this->added = Common::datetime();
            }
            $this->updated = Common::datetime();
            return true;
        }
        else
            return false;
    }

}