<?php
/**
 */
class ActiveForm extends CActiveForm
{
    /**
     * [bootstrapLabel]
     * create a custom label HTML element with Bootstrap style
     *
     * @param CModel  $model         the data model
     * @param string  $attribute     the attribute name
     * @param integer $column_length Bootstrap column size (col-lg-X)
     *
     * @return string label element HTML
     */
    public function bootstrapLabel(
        CModel $model,
        $attribute,
        $column_length = 2
    ) {
        if (!is_numeric($column_length)) {
            throw new CException('column length param must be a number');
        }

        return $this->labelEx(
            $model,
            $attribute,
            array('class' => 'col-lg-2 control-label')
        );
    }

    /**
     * [bootstrapTextField]
     * create a custom textfield HTML element with Bootstrap style
     *
     * @param CModel  $model         the data model
     * @param string  $attribute     the attribute name
     * @param integer $column_length Bootstrap column size (col-lg-X)
     *
     * @return string text field element HTML
     */
    public function bootstrapTextField(
        CModel $model,
        $attribute,
        $column_length = 4
    ) {
        if (!is_numeric($column_length)) {
            throw new CException('column length param must be a number');
        }

        $textField = $this->textField(
            $model,
            $attribute,
            array(
                'class'         => 'form-control',
                'placeholder'   => $model->getAttributeLabel($attribute)
            )
        );

        $errorMessage = $this->error($model, $attribute);

        return '<div class="input col-lg-' . $column_length . '">'
            . $textField
            . ' '
            . $errorMessage
            . '</div>';
    }

    /**
     * [bootstrapPasswordField]
     *
     * @param CModel  $model         the data model
     * @param string  $attribute     the attribute name
     * @param integer $column_length Bootstrap column size (col-lg-X)
     *
     * @return string password field element HTML
     */
    public function bootstrapPasswordField(
        CModel $model,
        $attribute,
        $column_length = 4
    ) {
        if (!is_numeric($column_length)) {
            throw new CException('column length param must be a number');
        }

        $textField = $this->passwordField(
            $model,
            $attribute,
            array(
                'class'         => 'form-control',
                'placeholder'   => $model->getAttributeLabel($attribute)
            )
        );

        $errorMessage = $this->error($model, $attribute);

        return '<div class="input col-lg-' . $column_length . '">'
            . $textField
            . ' '
            . $errorMessage
            . '</div>';
    }

    public function bootstrapEmailField(
        CModel $model,
        $attribute,
        $column_length = 4
    ) {
        if (!is_numeric($column_length)) {
            throw new CException('column length param must be a number');
        }

        $textField = $this->textField(
            $model,
            $attribute,
            array(
                'class'         => 'form-control',
                'placeholder'   => $model->getAttributeLabel($attribute)
            )
        );

        $textField = '<div class="input-group">'
            . '<span class="input-group-addon">@</span>'
            . $textField
            . '</div>';

        $errorMessage = $this->error($model, $attribute);

        return '<div class="input col-lg-' . $column_length . '">'
            . $textField
            . ' '
            . $errorMessage
            . '</div>';
    }

    public function bootstrapTextArea(
        CModel $model,
        $attribute,
        $column_length  = 8,
        $rows           = 10
    ) {
        if (!is_numeric($column_length)) {
            throw new CException('column length param must be a number');
        }

        if (!is_numeric($rows)) {
            throw new CException('rows param must be a number');
        }

        $textArea = $this->textArea(
            $model,
            $attribute,
            array(
                'class' => 'form-control',
                'rows'  => $rows,
            )
        );

        $errorMessage = $this->error($model, $attribute);

        return '<div class="input col-lg-' . $column_length . '">'
            . $textArea
            . ' '
            . $errorMessage
            . '</div>';
    }

    public function bootstrapSubmit($label='submit')
    {
        return CHtml::submitButton(
            $label,
            array(
                'class' => 'btn btn-primary col-md-offset-2'
            )
        );
    }

}

?>