/**
 * Namespace for labels.
 */
WCF.Label = {};

/**
 * Provides enhancements for ACP label management.
 */
WCF.Label.ACPList = Class.extend({
	/**
	 * input element
	 * @var	jQuery
	 */
	_labelInput: null,
	
	/**
	 * list of pre-defined label items
	 * @var	array<jQuery>
	 */
	_labelList: [ ],
	
	/**
	 * Intitializes the ACP label list.
	 */
	init: function() {
		this._labelInput = $('#label').keydown($.proxy(this._keyPressed, this)).keyup($.proxy(this._keyPressed, this)).blur($.proxy(this._keyPressed, this));
		
		$('#labelList').find('input[type="radio"]').each($.proxy(function(index, input) {
			var $input = $(input);
			
			// ignore custom values
			if ($input.prop('value') !== 'custom') {
				this._labelList.push($($input.next('span')));
			}
		}, this));
	},
	
	/**
	 * Renders label name as label or falls back to a default value if label is empty.
	 */
	_keyPressed: function() {
		var $text = this._labelInput.prop('value');
		if ($text === '') $text = WCF.Language.get('wcf.acp.label.defaultValue');
		
		for (var $i = 0, $length = this._labelList.length; $i < $length; $i++) {
			this._labelList[$i].text($text);
		}
	}
});

/**
 * Provides a preview for label selections.
 * 
 * @param	string		elementSelector
 * @param	boolean		previewOnInit
 */
WCF.Label.Preview = Class.extend({
	/**
	 * list of selections
	 * @var	object
	 */
	_elements: { },
	
	/**
	 * list of preview elements
	 * @var	object
	 */
	_previews: { },
	
	/**
	 * Creates a new label preview for affected select elements.
	 * 
	 * @param	string		elementSelector
	 * @param	boolean		previewOnInit
	 */
	init: function(elementSelector, previewOnInit) {
		var $elements = $(elementSelector);
		if (!$elements.length) {
			return;
		}
		
		$elements.each($.proxy(function(index, element) {
			var $element = $(element);
			var $elementID = $element.wcfIdentify();
			
			this._elements[$elementID] = $element;
			this._previews[$elementID] = $('&nbsp;<span />').hide().insertAfter($element);
			
			$element.change($.proxy(this._change, this));
			
			if (previewOnInit) {
				this._showPreview($elementID);
			}
		}, this));
	},
	
	/**
	 * Updates preview on change.
	 * 
	 * @param	object		event
	 */
	_change: function(event) {
		var $elementID = $(event.currentTarget).wcfIdentify();
		this._showPreview($elementID);
	},
	
	/**
	 * Shows (or hides) preview for labels.
	 * 
	 * @param	string		elementID
	 */
	_showPreview: function(elementID) {
		var $element = this._elements[elementID];
		
		// get selected option
		var $selectedOption = $element.children('option:selected');
		
		this._previews[elementID].removeClass().empty().hide();
		if ($selectedOption.prop('value')) {
			this._previews[elementID].addClass('wcf-label').addClass($selectedOption.data('cssClassName')).html($selectedOption.text()).show();
		}
	}
});