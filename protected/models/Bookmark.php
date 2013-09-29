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
                'id, url, title, description, content, created, updated',
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
            'criteria'=>$criteria,
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
