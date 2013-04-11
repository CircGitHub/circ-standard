<?php defined('C5_EXECUTE') or die(_("Access Denied."));

Loader::library("file/importer");
Loader::model("file_set");
Loader::model("file");

class ButtonBlockController extends BlockController {
	
	protected $btTable = "btCircButton";
	protected $btInterfaceWidth = "300";
	protected $btInterfaceHeight = "350";
    protected $btName = 'Circ Standard Button';
    protected $btDescription = 'Create Buttons';

    public function save(array $args)
    {
        if(empty($args['btn_text'])) {
            throw new Exception('Text is required.');
        }
        if (empty($args['btn_url'])) {
            throw new Exception('URL is required.');
        }
        $args = array_map(function($value) {return $value === '' ? null : $value;}, $args);

        parent::save($args);
    }

    public function view()
    {
        $id = '';
        if(!empty($this->record->css_id)) {
            $id = 'id="' . $this->record->css_id . '"';
        }
        if (!empty($this->record->css_class)) {
            $class = ' ' . $this->record->css_class;
        }

        $this->set('text', $this->record->btn_text);
        $this->set('url', $this->record->btn_url);
        $this->set('id', $id);
        $this->set('class', $class);
    }
}
