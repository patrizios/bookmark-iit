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

    /**
     * [bootstrapEmailField]
     *
     * @param CModel  $model         the data model
     * @param string  $attribute     the attribute name
     * @param integer $column_length Bootstrap column size (col-lg-X)
     *
     * @return string email field element HTML
     */
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

    /**
     * [bootstrapTextArea]
     *
     * @param CModel  $model         the data model
     * @param string  $attribute     the attribute name
     * @param integer $column_length Bootstrap column size (col-lg-X)
     * @param integer $rows          TextArea row number
     *
     * @return string textarea field element HTML
     */
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
    /**
     * [bootstrapSubmit]
     *
     * @param string $label label of button
     *
     * @return string submit button HTML
     */
    public function bootstrapSubmit($label='submit', $offset = 2)
    {
        if (!is_numeric($offset)) {
            throw new CException('offset param must be a number');
        }

        return CHtml::submitButton(
            $label,
            array(
                'class' => 'btn btn-primary col-md-offset-' . $offset
            )
        );
    }

    /**
     * [boostrapTextButton]
     * Create a bootstrap input group with button
     *
     * @param CModel $model        the data model
     * @param string $attribute    the attribute name
     * @param string $button_label button label
     * @param string $placeholder  input placeholder
     *
     * @return string input group HTML
     */
    public function boostrapTextButton(
        $model,
        $attribute,
        $button_label,
        $placeholder = null
    ) {

        if (!$placeholder) {
            $placeholder = $model->getAttributeLabel($attribute);
        }

        $textField = $this->textField(
            $model,
            $attribute,
            array(
                'class'         => 'form-control',
                'placeholder'   => $placeholder
            )
        );

        $submitButton = CHtml::submitButton(
            $button_label,
            array(
                'class' => 'btn btn-primary'
            )
        );

        return
            "<div class=\"input-group\">
                {$textField}
                <span class=\"input-group-btn\">
                    {$submitButton}
                </span>
            </div>";
    }
}

?>