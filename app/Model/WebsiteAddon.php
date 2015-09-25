
<?php
	class WebsiteAddon extends AppModel {
	    public $belongsTo = array(
	        'Addon'
	        , 'Website'//=>array('type'=>"FULL")
	    );
	}
?>