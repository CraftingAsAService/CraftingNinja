<?php

namespace App\Providers\Html;

use Carbon\Carbon;
use Illuminate\Support\Facades\Request;

class FormBuilder extends \Collective\Html\FormBuilder
{

	private function modify_options($options = [], $string = '')
	{
		if ( ! isset($options['class'])) $options['class'] = '';
		$options['class'] .= ' ' . $string;
		$options['class'] = trim($options['class']);

		return $options;
	}

	private function add_bootstrap_class($options = [], $type = '')
	{
		if ( ! in_array($type, ['checkbox', 'radio']))
        	$options = $this->modify_options($options, 'form-control');

		return $options;
	}

 	/**
     * Create a form label element.
     *
     * @param  string $name
     * @param  string $value
     * @param  array  $options
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function label($name, $value = null, $options = [])
    {
        $this->labels[] = $name;

        // Always add the bootstrap class to the label
        $options = $this->modify_options($options, 'form-control-label');

        $options = $this->html->attributes($options);

        $value = e($this->formatLabel($name, $value));

        return $this->toHtmlString('<label for="' . $name . '"' . $options . '>' . $value . '</label>');
    }

	/**
	 * Alter all textarea input field.
	 *
	 * @param  string  $name
	 * @param  string  $value
	 * @param  array   $options
	 * @return string
	 */
	public function textarea($name, $value = null, $options = [])
	{
		return parent::textarea($name, $value, $this->add_bootstrap_class($options, 'textarea'));
	}

	/**
	 * Alter all input fields.
	 *
	 * @param  string  $type
	 * @param  string  $name
	 * @param  string  $value
	 * @param  array   $options
	 * @return string
	 */
	public function input($type, $name, $value = null, $options = [])
	{
	    return parent::input($type, $name, $value, $this->add_bootstrap_class($options, $type));
	}

	/**
	 * Alter the select box field.
	 *
	 * @param  string  $name
	 * @param  array   $list
	 * @param  string  $selected
	 * @param  array   $options
	 * @return string
	 */
	public function select($name, $list = [], $selected = null, $options = [])
	{
		// dd($name, $list, $selected);
	    return parent::select($name, $list, $selected, $this->add_bootstrap_class($options));
	}

}