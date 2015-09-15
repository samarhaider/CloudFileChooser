<?php

/**
 * Dropbox class file.
 *
 * @author Samar Haider <s.samar_haider@yahoo.com>
 * @version 0.1
 * @license http://www.opensource.org/licenses/bsd-license.php
 */

/**
  How to use:

  view:
  $this->widget('ext.DBFileChooser.DBFileChooser',
  array(
  'id' => 'dropbox-file-choose',
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
class DBFileChooser extends CWidget {

    public $id = "dropbox_id";
    public $itemTagText = 'Attach files from Dropbox';
    public $itemCssClass = '';
    public $itemTagName = 'a';
    public $app_key;
    public $linkType = 'preview'; // or "direct"
    public $multiselect = false; // or true
    public $extensions = array(); //['.pdf', '.doc', '.docx'];
    private $options = array();
    public $success;
    public $cancel;
    public $attributes = array();

//    public $clickOn;

    public function run() {
        Yii::app()->clientScript->registerScriptFile('https://www.dropbox.com/static/api/2/dropins.js', CClientScript::POS_HEAD, array('id' => 'dropboxjs', 'data-app-key' => $this->app_key));
        if (!isset($this->app_key) || empty($this->app_key)) {
            throw new CException('Dropbox: API must be defined.');
        }

        $this->options = array(
            'success' => $this->success,
            'cancel' => $this->cancel,
            'linkType' => $this->linkType,
            'multiselect' => $this->multiselect,
            'extensions' => $this->extensions,
        );

        echo '<div id="no_js' . $this->id . '"><noscript><p>Please enable JavaScript to use Dropbox Chooser.</p></noscript></div>';
        
        $element_attrs = $this->attributes;
        $element_attrs['id'] = $this->id;
        $element_attrs['class'] = $this->itemCssClass;
        
        echo CHtml::openTag($this->itemTagName, $element_attrs) . "\n";
        echo $this->itemTagText;
        echo CHtml::closeTag($this->itemTagName);

        $this->options = CJavaScript::encode($this->options);
        Yii::app()->getClientScript()->registerScript("DropBox_{$this->id}", "$('#{$this->id}').on('click', function (e) { Dropbox.choose({$this->options}); });", CClientScript::POS_LOAD);
//        Yii::app()->getClientScript()->registerScript("DropBox_{$this->id}", "if(!Dropbox.isBrowserSupported()){ alert('Your browser dont\'t support dropbox file chooser'); } else {  $('#{$this->id}').on('click', function (e) { Dropbox.choose({$this->options}); }); }", CClientScript::POS_LOAD);
    }

}
