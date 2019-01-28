<?php

namespace YKuadrat\DatatableBuilder\Helpers;

/**
 * 
 */
class DatatableBuilderHelper
{

	public static function render($source, $config = [])
	{
		if (!isset($config['elOptions']['id'])) {
			throw new \Exception("Property elOptions['id'] is required.", 1);
		}

		$data = self::setupConfig($source, $config);

		return view('datatable-builder::index', $data);	
	}



	private static function setupConfig($source, $config)
	{
		$defaultConfig = config('datatables-view');
		$data = array_merge_recursive($defaultConfig, $config);
		$data['source'] = $source;

		$data['sourceByAjax'] = true; 
		if ($source instanceof Collection) {
			$data['sourceByAjax'] = false;
		}

		$data['htmlOptions'] = self::arrayToHtmlAttribute($data['elOptions']);
		$data = array_merge($data, self::setupColumn($config['columns']));

		return $data;
	}



	private static function setupColumn(Array $columns)
	{
		$data['headerColumns'] = [];
		$data['attributeColumns'] = [];
		foreach ($columns as $column) {
			$data['headerColumns'][] = strtolower(is_array($column) ? $column['label'] : $column);
			$data['attributeColumns'][] = strtolower(is_array($column) ? $column['attribute'] : $column);
		}

		return $data;
	}



	public static function button($button, $url = '#')
	{
		$buttonTemplates = config('datatables-view')['buttonTemplates'];

		if (is_array($button)) {
			$stringButton = '';
			foreach ($button as $buttonName => $url) {
				$stringButton .= str_replace('<<url>>', $url, $buttonTemplates[$buttonName]);
			}

			return $stringButton;
		}

		return str_replace('<<url>>', $url, $buttonTemplates[$button]);
	}



	public static function arrayToHtmlAttribute(Array $elOptions) {
		$htmlAttributes = '';
		foreach ($elOptions as $attribute => $attributeValue) {
			$htmlAttributes .= $attribute . '="' . $attributeValue . '" ';
		}
		return $htmlAttributes;
	}
}