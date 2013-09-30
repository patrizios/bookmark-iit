<?php

/**
 * This is the model class for table "bookmark".
 *
 * The followings are the available columns in table 'bookmark':
 *
 * @property string $id
 * @property string $url
 * @property string $title
 * @property string $description
 * @property string $content
 * @property string $created
 * @property string $updated
 */
class Bookmark extends CActiveRecord
{
    public $full_search;

    /**
     * [tableName]
     *
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'bookmark';
    }

    /**
     * [rules]
     *
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('url', 'required'),
            array('url, title', 'length', 'max'=>255),
            array('description, content, created, updated', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array(
                'id, url, title, description, content, created, updated, full_search',
                'safe',
                'on'=>'search'
            ),
        );
    }

    /**
     * [relations]
     *
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * [attributeLabels]
     *
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id'            => 'ID',
            'url'           => 'Url',
            'title'         => 'Title',
            'description'   => 'Description',
            'content'       => 'Content',
            'created'       => 'Created',
            'updated'       => 'Updated',
            'full_search'   => 'Search something'
        );
    }

    public function beforeSave()
    {
        $now = date('Y-m-d H:i:s');

        if ($this->isNewRecord && !$this->created) {
            $this->created = $now;
        }

        if (!$this->updated) {
            $this->updated = $now;
        }

        return parent::beforeSave();
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria=new CDbCriteria;

        $criteria->compare('id', $this->id, true);
        $criteria->compare('url', $this->url, true);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('content', $this->content, true);
        $criteria->compare('created', $this->created, true);
        $criteria->compare('updated', $this->updated, true);

        return new CActiveDataProvider(
            $this,
            array(
                'criteria'  =>$criteria,
                'sort'      =>array('defaultOrder'=>'updated DESC')
            )
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function fullSearch()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.
        $criteria       = new CDbCriteria;

        $partialMatch   = true;
        $operator       = 'OR';

        $fields_to_compare = array(
            'id',
            'url',
            'title',
            'description',
            'content',
            'created',
            'updated',
        );

        foreach ($fields_to_compare as $field) {
            $criteria->compare(
                $field,
                $this->full_search,
                $partialMatch,
                $operator
            );
        }

        return new CActiveDataProvider(
            $this,
            array(
                'criteria'  =>$criteria,
                'sort'      =>array('defaultOrder'=>'updated DESC')
            )
        );
    }
    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your
     * CActiveRecord descendants!
     *
     * @param string $className active record class name.
     *
     * @return Bookmark the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}
