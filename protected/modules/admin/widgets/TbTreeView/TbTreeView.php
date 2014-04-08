<?php
/**
 * User: megakuzmitch
 * Date: 03.04.14
 * Time: 13:56
 */

class TbTreeView extends CWidget
{
	/*
	 *
	 */
	public $data;

	/*
	 *
	 */
	public $openedId;

	/*
	 *
	 */
	public $url;

	/*
	 *
	 */
	public $clientOptions = array();

	/*
	 *
	 */
	public $cssFile;

	/*
	 * $node['label'] = eval($node)
	 */
	public $nodeContent;

	public $htmlOptions = array();

	/**
	 * Initializes the widget.
	 * This method registers all needed client scripts and renders
	 * the tree view content.
	 */
	public function init()
	{
		if($this->url!==null)
			$this->url=CHtml::normalizeUrl($this->url);

		if(isset($this->htmlOptions['id']))
			$id=$this->htmlOptions['id'];
		else
			$id=$this->htmlOptions['id']=$this->getId();

		$this->htmlOptions['class'] .= 'tree';

		$options=$this->getClientOptions();

		$options=$options===array()?'{}' : CJavaScript::encode($options);

		$assetsPath = $this->getScriptUrl();
		$cs=Yii::app()->getClientScript();
		$cs->registerScriptFile($assetsPath.'/bootstrap-tree.js', CClientScript::POS_END);
		$cs->registerScript('self.TbTreeView#'.$id, "jQuery('#{$id}').tbtreeview({$options});");
		if($this->cssFile===null)
			$cs->registerCssFile($assetsPath.'/bootstrap-tree.css');
		elseif($this->cssFile!==false)
			$cs->registerCssFile($this->cssFile);

		echo TbHtml::openTag('div', $this->htmlOptions);
		echo TbHtml::openTag('ul');

		$branch = array();
		self::findOpenedBranch($this->data, $this->openedId, $branch);
		$options = array(
			'nodeContent' => $this->nodeContent,
		);
		echo self::saveDataAsHtml($this->data, $branch, $options);
	}



	protected function getScriptUrl()
	{
		$path = realpath(__DIR__.DIRECTORY_SEPARATOR.'assets');
		return Yii::app()->assetManager->publish($path, false, -1, true);
	}



	public function run()
	{
		echo TbHtml::closeTag('ul');
		echo TbHtml::closeTag('div');
	}



	/**
	 * @return array the javascript options
	 */
	protected function getClientOptions()
	{
		return $this->clientOptions;
	}



	//
	/* @param array $data
	 * @param int $opened_node
	 * @param array $stack
	 * @return void Поиск полного пути от узла с $id¸ равным $opened_node до корня
	 */
	public static function findOpenedBranch($data, $opened_node, &$stack)
	{
		if ( is_array($data) ) {
			foreach ( $data as $node ) {
				if ( !isset($node['id']) )
					break;

				if ( $node['id'] == $opened_node ) {
					$stack[] = $node['id'];
					break;
				}

				if ( isset($node['items']) ) {
					self::findOpenedBranch($node['items'], $opened_node, $stack);
					if ( in_array($opened_node, $stack) ) {
						$stack[] = $node['id'];
						break;
					}
				}
			}
		};
	}



	/**
	 * Generates tree view nodes in HTML from the data array.
	 * @param array $data the data for the tree view (see {@link data} for possible data structure).
	 * @return string the generated HTML for the tree view
	 */
	public static function saveDataAsHtml($data, &$open_branch = array(), &$widgetOptions = array())
	{
		$html='';
		if(is_array($data))
		{
			foreach($data as $node)
			{
				if(!isset($node['label']))
					continue;

				$css = '';
				$icon = '';

				if(!empty($node['items']))
				{
					$css = 'hasChildren ';
					if ( in_array($node['id'], $open_branch) ) {
						$css .= 'open';
						if ( $node['id'] == $open_branch[0] ) {
							$css .= ' active';
							$icon = TbHtml::icon(TbHtml::ICON_MINUS_SIGN, array('color' => TbHtml::ICON_COLOR_WHITE));
						} else {
							$icon = TbHtml::icon(TbHtml::ICON_MINUS_SIGN);
						}
					} else {
						$css .= 'closed';
						$icon = TbHtml::icon(TbHtml::ICON_PLUS_SIGN);
					}
				} else if ( $node['id'] == $open_branch[0] ) {
					$css = 'active';
				}

				$options=isset($node['htmlOptions']) ? $node['htmlOptions'] : array();
				if($css!=='')
				{
					if(isset($options['class']))
						$options['class'].=' '.$css;
					else
						$options['class']=$css;
				}

				if(isset($node['id'])) {
					$options['id']='node-'.$node['id'];
					$options['data-id']=$node['id'];
				}

				$content = '';
				if ( !empty($widgetOptions['nodeContent']) ) {
					$content = eval('return '.$widgetOptions['nodeContent'].';');
				}

				$html.=TbHtml::tag('li', $options, TbHtml::tag('span', array('class'=>'expand-button'), $icon.$node['label']).TbHtml::tag('div', array('class'=>'node-content'), $content) ,false);
				if(!empty($node['items']))
				{
					$html.="\n<ul>\n";
					$html.=self::saveDataAsHtml($node['items'], $open_branch, $widgetOptions);
					$html.="</ul>\n";
				}
				$html.=CHtml::closeTag('li')."\n";
			}
		}
		return $html;
	}

	/**
	 * Saves tree view data in JSON format.
	 * This method is typically used in dynamic tree view loading
	 * when the server code needs to send to the client the dynamic
	 * tree view data.
	 * @param array $data the data for the tree view (see {@link data} for possible data structure).
	 * @return string the JSON representation of the data
	 */
	public static function saveDataAsJson($data)
	{
		if(empty($data))
			return '[]';
		else
			return CJSON::encode($data);
	}
}