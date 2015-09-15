<?php

/**
 * Box class file.
 *
 * @author Samar Haider <s.samar_haider@yahoo.com>
 * @version 0.1
 * @license http://www.opensource.org/licenses/bsd-license.php
 */

/**
  How to use:

  view:
  $this->widget('ext.CloudFileChooser.BoxFileChooser',
  array(
  'id' => 'box-file-choose',
  'itemCssClass' => 'noclass',
  'itemTagName' => 'a',
  'linkType' => 'preview',
  'multiselect' => false,
  'attributes' => array('href'=> 'javascript:void(0);'),
  'itemTagText' => 'Samar: Attach from dropbx',
  //'success'=>"js:function(files){ alert(files[0].name); }",
  //'cancel'=>"js:function(message){ alert(message); }"
  )
  ));

 */
class BoxFileChooser extends CWidget {

    public $id = "box_id";
    public $itemTagText = 'Attach files from Box';
    public $itemCssClass = '';
    public $itemTagName = 'a';
    public $clientId;
    public $linkType = 'shared'; // or "direct"
    public $multiselect = false; // or true
    public $extensions = array(); //['.pdf', '.doc', '.docx'];
    private $options = array();
    public $success;
    public $cancel;
    public $attributes = array();

//    public $clickOn;

    public function run() {
        Yii::app()->clientScript->registerScriptFile('https://app.box.com/js/static/select.js', CClientScript::POS_HEAD);
        if (!isset($this->clientId) || empty($this->clientId)) {
            throw new CException('Box: API must be defined.');
        }

        $this->options = array(
            'clientId' => $this->clientId,
            'linkType' => $this->linkType,
            'multiselect' => $this->multiselect,
        );

        echo '<div id="no_js' . $this->id . '"><noscript><p>Please enable JavaScript to use Box Chooser.</p></noscript></div>';

        $element_attrs = $this->attributes;
        $element_attrs['id'] = $this->id;
        $element_attrs['class'] = $this->itemCssClass;

        echo CHtml::openTag($this->itemTagName, $element_attrs) . "\n";
        echo $this->itemTagText;
        echo CHtml::closeTag($this->itemTagName);

        $this->options = CJavaScript::encode($this->options);
        
        $success = CJavaScript::encode($this->success);
        $cancel = CJavaScript::encode($this->cancel);
        
        Yii::app()->getClientScript()->registerScript("Box_{$this->id}", " var boxSelect = new BoxSelect({$this->options}); boxSelect.success({$success}); boxSelect.cancel({$cancel}); $('#{$this->id}').on('click', function (e) { boxSelect.launchPopup(); });", CClientScript::POS_LOAD);
    }

}
